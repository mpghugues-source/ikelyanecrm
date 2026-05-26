#!/usr/bin/env bash
##############################################################
# IkelyaneMed — Configuration interactive des variables .env
# Exécuter sur le serveur APRÈS deploy.sh :
#   bash deploy/setup_env.sh
##############################################################
set -euo pipefail

ENV_FILE="/var/www/ikelyanemed/.env"

if [ ! -f "${ENV_FILE}" ]; then
    echo "Erreur : ${ENV_FILE} introuvable. Lancer deploy.sh d'abord."
    exit 1
fi

echo ""
echo "═══════════════════════════════════════════════════════"
echo "  IkelyaneMed — Configuration des clés de production"
echo "═══════════════════════════════════════════════════════"
echo ""

# ── 1. Clé de chiffrement ──────────────────────────────────
if grep -q 'CHANGE_ME_32_BYTE_HEX_KEY' "${ENV_FILE}"; then
    echo "[1/3] Génération de la clé de chiffrement..."
    cd /var/www/ikelyanemed
    ENC_KEY=$(php8.1 spark key:generate --show 2>/dev/null \
        | grep -oP 'hex2bin:[a-f0-9]+' | head -1)
    if [ -n "${ENC_KEY}" ]; then
        sed -i "s|hex2bin:CHANGE_ME_32_BYTE_HEX_KEY|${ENC_KEY}|g" "${ENV_FILE}"
        echo "    ✓ Clé générée automatiquement"
    else
        echo "    Impossible de générer automatiquement."
        read -rp "    Entrer la clé (hex2bin:xxxx) : " ENC_KEY
        sed -i "s|hex2bin:CHANGE_ME_32_BYTE_HEX_KEY|${ENC_KEY}|g" "${ENV_FILE}"
    fi
else
    echo "[1/3] Clé de chiffrement : déjà configurée ✓"
fi

# ── 2. SMTP ────────────────────────────────────────────────
echo ""
echo "[2/3] Configuration SMTP (Hostinger)"
echo "    Serveur : smtp.hostinger.com:587 (TLS)"
echo "    Adresse email d'envoi (ex: noreply@ikelyanemed.com) :"
read -rp "    SMTP_USER : " SMTP_USER_VAL
read -rsp "    SMTP_PASS : " SMTP_PASS_VAL
echo ""
read -rp "    MAIL_FROM (email expéditeur, même ou différent) [$SMTP_USER_VAL] : " MAIL_FROM_VAL
MAIL_FROM_VAL="${MAIL_FROM_VAL:-$SMTP_USER_VAL}"

sed -i "s|SMTP_USER.*=.*|SMTP_USER       = ${SMTP_USER_VAL}|" "${ENV_FILE}"
sed -i "s|SMTP_PASS.*=.*CHANGE_ME.*|SMTP_PASS       = ${SMTP_PASS_VAL}|" "${ENV_FILE}"
sed -i "s|MAIL_FROM.*=.*noreply@ikelyanemed.com|MAIL_FROM       = ${MAIL_FROM_VAL}|" "${ENV_FILE}"
echo "    ✓ SMTP configuré"

# ── 3. Flutterwave ─────────────────────────────────────────
echo ""
echo "[3/3] Clés Flutterwave"
echo "    Obtenir sur : https://app.flutterwave.com → Settings → API Keys"
echo "    Mode TEST : clés commençant par FLWPUBK-TEST et FLWSECK-TEST"
echo "    Mode LIVE : clés commençant par FLWPUBK-LIVE et FLWSECK-LIVE"
echo ""
read -rp "    FLW_PUBLIC_KEY  : " FLW_PUB
read -rsp "    FLW_SECRET_KEY  : " FLW_SEC
echo ""
read -rp "    FLW_WEBHOOK_SECRET (valeur libre, min 16 chars) : " FLW_WH

sed -i "s|FLW_PUBLIC_KEY.*=.*CHANGE_ME.*|FLW_PUBLIC_KEY     = ${FLW_PUB}|" "${ENV_FILE}"
sed -i "s|FLW_SECRET_KEY.*=.*CHANGE_ME.*|FLW_SECRET_KEY     = ${FLW_SEC}|" "${ENV_FILE}"
sed -i "s|FLW_WEBHOOK_SECRET.*=.*CHANGE_ME.*|FLW_WEBHOOK_SECRET = ${FLW_WH}|" "${ENV_FILE}"
echo "    ✓ Flutterwave configuré"

# ── Vérification finale ────────────────────────────────────
echo ""
REMAINING=$(grep -c 'CHANGE_ME' "${ENV_FILE}" 2>/dev/null || echo 0)
if [ "${REMAINING}" -gt 0 ]; then
    echo "  ⚠  ${REMAINING} variable(s) CHANGE_ME encore présente(s) :"
    grep 'CHANGE_ME' "${ENV_FILE}" | sed 's/=.*//'
else
    echo "  ✓ Toutes les variables sont configurées."
fi

# ── Rappel URL webhook Flutterwave ─────────────────────────
BASE_URL=$(grep '^app.baseURL' "${ENV_FILE}" | sed "s/app.baseURL\s*=\s*//;s/'//g;s/\"//g;s/\s//g")
echo ""
echo "  → URL webhook Flutterwave à enregistrer dans le dashboard :"
echo "    ${BASE_URL}payment/webhook"
echo ""
echo "  → Configurer dans Flutterwave : Settings → Webhooks → Add webhook"
echo "    Secret hash : FLW_WEBHOOK_SECRET que vous venez d'entrer"
echo ""
echo "═══════════════════════════════════════════════════════"
echo "  Configuration terminée. Redémarrer PHP-FPM :"
echo "  systemctl restart php8.1-fpm"
echo "═══════════════════════════════════════════════════════"
