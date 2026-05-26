#!/usr/bin/env bash
##############################################################
# IkelyaneMed — Script de déploiement (première installation)
# Exécuter sur le serveur en root ou avec sudo :
#   bash deploy/deploy.sh
##############################################################
set -euo pipefail

APP_DIR="/var/www/ikelyanemed"
PHP="php8.1"
NGINX_AVAILABLE="/etc/nginx/sites-available/ikelyanemed"
NGINX_ENABLED="/etc/nginx/sites-enabled/ikelyanemed"

echo "==> [1/9] Mise à jour des paquets système"
apt-get update -qq

echo "==> [2/9] Installation PHP 8.1, Nginx, MySQL, extensions"
apt-get install -y -qq \
    nginx \
    mysql-server \
    php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring \
    php8.1-xml php8.1-curl php8.1-intl php8.1-gd \
    php8.1-zip php8.1-bcmath php8.1-opcache \
    unzip git curl

echo "==> [3/9] Copie des fichiers de l'application"
rsync -a --exclude='.git' --exclude='deploy' --exclude='node_modules' \
    /root/ikelyanemed/ "${APP_DIR}/"

echo "==> [4/9] Permissions"
chown -R www-data:www-data "${APP_DIR}"
find "${APP_DIR}" -type f -exec chmod 644 {} \;
find "${APP_DIR}" -type d -exec chmod 755 {} \;
chmod -R 775 "${APP_DIR}/writable"
# Bloquer l'accès direct aux répertoires sensibles
chmod 700 "${APP_DIR}/app" "${APP_DIR}/system" 2>/dev/null || true

echo "==> [5/9] Configuration Nginx"
cp /root/ikelyanemed/deploy/nginx.conf "${NGINX_AVAILABLE}"
ln -sf "${NGINX_AVAILABLE}" "${NGINX_ENABLED}"
# Supprimer le site par défaut s'il existe
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

echo "==> [6/9] Base de données MySQL"
DB_PASS=$(openssl rand -base64 24)
mysql -u root <<SQL
CREATE DATABASE IF NOT EXISTS ikelyanemed_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'ikelyanemed_user'@'localhost'
    IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ikelyanemed_db.* TO 'ikelyanemed_user'@'localhost';
FLUSH PRIVILEGES;
SQL
echo "   Mot de passe DB généré : ${DB_PASS}"
echo "   → Mettre à jour database.default.password dans .env"

echo "==> [7/9] Fichier .env"
if [ ! -f "${APP_DIR}/.env" ]; then
    cp /root/ikelyanemed/deploy/env.production "${APP_DIR}/.env"
    sed -i "s/CHANGE_ME_DB_PASSWORD/${DB_PASS}/g" "${APP_DIR}/.env"
    # Générer la clé de chiffrement
    cd "${APP_DIR}"
    ENC_KEY=$(${PHP} spark key:generate --show 2>/dev/null | grep 'hex2bin:' | head -1 | tr -d ' ')
    if [ -n "${ENC_KEY}" ]; then
        sed -i "s|hex2bin:CHANGE_ME_32_BYTE_HEX_KEY|${ENC_KEY}|g" "${APP_DIR}/.env"
        echo "   Clé de chiffrement générée automatiquement."
    else
        echo "   ATTENTION : générer manuellement la clé avec : php spark key:generate"
    fi
    echo "   .env créé. Éditer les valeurs CHANGE_ME restantes (SMTP, Flutterwave)."
else
    echo "   .env déjà présent, ignoré."
fi
chmod 600 "${APP_DIR}/.env"

echo "==> [8/9] Migrations & Seeder"
cd "${APP_DIR}"
${PHP} spark migrate --all
${PHP} spark db:seed DatabaseSeeder

# Avertissement si des CHANGE_ME restent dans .env
REMAINING=$(grep -c 'CHANGE_ME' "${APP_DIR}/.env" 2>/dev/null || echo 0)
if [ "${REMAINING}" -gt 0 ]; then
    echo ""
    echo "  ⚠  ${REMAINING} variable(s) CHANGE_ME encore présente(s) dans .env :"
    grep 'CHANGE_ME' "${APP_DIR}/.env" | sed 's/=.*//'
    echo "  → Éditer : nano ${APP_DIR}/.env"
fi

echo "==> [9/9] Optimisations PHP production"
# OPcache
cat > /etc/php/8.1/fpm/conf.d/99-ikelyanemed.ini <<INI
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
upload_max_filesize=20M
post_max_size=22M
max_execution_time=60
memory_limit=256M
INI
systemctl restart php8.1-fpm

echo ""
echo "✓ Déploiement terminé !"
echo "  → Accès : http://144.91.103.251/"
echo "  → Admin      : admin@ikelyanemed.com       / Admin@IkelyaneMed24"
echo "  → SuperAdmin : superadmin@ikelyanemed.com / SuperAdmin@2024"
echo ""
echo "  Actions restantes :"
echo "  1. Éditer ${APP_DIR}/.env (SMTP + Flutterwave keys)"
echo "  2. Configurer le domaine DNS → puis : certbot --nginx -d ikelyanemed.com"
echo "  3. Décommenter le bloc HTTPS dans nginx.conf"
