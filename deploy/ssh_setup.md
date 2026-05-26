# Accès SSH au serveur 144.91.103.251

## Option A — Depuis cette machine (si vous avez déjà un accès console/VNC)

Sur le **serveur** (via VNC ou console Hetzner/OVH/autre) :

```bash
# 1. Autoriser temporairement l'authentification par mot de passe
sed -i 's/^#*PasswordAuthentication.*/PasswordAuthentication yes/' /etc/ssh/sshd_config
systemctl reload sshd

# 2. Depuis votre poste local, copier votre clé publique
#    (remplacer USER par root ou votre utilisateur)
ssh-copy-id root@144.91.103.251
# → saisir le mot de passe : Bonjour123

# 3. Tester la connexion par clé
ssh root@144.91.103.251

# 4. Redésactiver l'auth par mot de passe (sécurité)
sed -i 's/^PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl reload sshd
```

## Option B — Générer une paire de clés si vous n'en avez pas

```bash
# Sur votre poste local
ssh-keygen -t ed25519 -C "ikelyanemed-deploy" -f ~/.ssh/ikelyanemed_key

# Afficher la clé publique à coller sur le serveur
cat ~/.ssh/ikelyanemed_key.pub
```

Sur le **serveur** (console VNC) :
```bash
mkdir -p /root/.ssh
echo "VOTRE_CLE_PUBLIQUE_ICI" >> /root/.ssh/authorized_keys
chmod 700 /root/.ssh
chmod 600 /root/.ssh/authorized_keys
```

Connexion ensuite :
```bash
ssh -i ~/.ssh/ikelyanemed_key root@144.91.103.251
```

## Déploiement une fois SSH configuré

```bash
# Depuis cette machine (où le code est dans /root/ikelyanemed)
ssh root@144.91.103.251 "mkdir -p /var/www/ikelyanemed"

# Copier le projet sur le serveur
rsync -avz --exclude='.git' --exclude='vendor' \
    /root/ikelyanemed/ root@144.91.103.251:/root/ikelyanemed/

# Lancer le script de déploiement
ssh root@144.91.103.251 "bash /root/ikelyanemed/deploy/deploy.sh"
```

## Après déploiement — éditer le .env

```bash
ssh root@144.91.103.251
nano /var/www/ikelyanemed/.env
```

Valeurs à remplir :
- `email.SMTPPass` → mot de passe email
- `FLW_PUBLIC_KEY` → depuis app.flutterwave.com
- `FLW_SECRET_KEY` → depuis app.flutterwave.com
- `FLW_WEBHOOK_SECRET` → valeur libre, à copier aussi dans Flutterwave dashboard
  (Webhook URL : `http://144.91.103.251/payment/webhook`)
