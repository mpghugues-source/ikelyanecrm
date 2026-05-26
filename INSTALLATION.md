# IkeylaneMed — Guide d'installation

## Prérequis
- PHP 8.1+
- MySQL 8.0+
- Composer
- Apache/Nginx avec mod_rewrite activé

## Installation en 5 étapes

### 1. Installer les dépendances
```bash
cd ikelyanemed
composer install
```

### 2. Configurer la base de données
Créer la base de données :
```sql
CREATE DATABASE ikelyanemed_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Modifier `.env` avec vos informations :
```
database.default.hostname = localhost
database.default.database = ikelyanemed_db
database.default.username = root
database.default.password = votre_mot_de_passe
```

### 3. Exécuter les migrations
```bash
php spark migrate
```

### 4. Peupler la base de données (données de démo)
```bash
php spark db:seed DatabaseSeeder
```

### 5. Configurer le serveur web

**Option A — Serveur de développement intégré:**
```bash
php spark serve
# Accessible sur http://localhost:8080
```

**Option B — Apache (VirtualHost):**
```apache
<VirtualHost *:80>
    ServerName ikelyanemed.local
    DocumentRoot /var/www/ikelyanemed/public

    <Directory /var/www/ikelyanemed/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Option C — Nginx:**
```nginx
server {
    listen 80;
    server_name ikelyanemed.local;
    root /var/www/ikelyanemed/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
}
```

### Fichier .htaccess (public/)
Créer `/public/.htaccess` :
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

---

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@ikelyanemed.com | Admin@2024 |
| Médecin | dr.benaissa@ikelyanemed.com | Medecin@2024 |
| Patient | fatima.amrani@email.com | Patient@2024 |

---

## Structure du projet

```
ikelyanemed/
├── app/
│   ├── Config/          # Configuration (Routes, Filters, Database)
│   ├── Controllers/
│   │   ├── Auth/        # Authentification
│   │   ├── Admin/       # Dashboard admin, patients, médecins, RDV, ordonnances, factures
│   │   ├── Medecin/     # Espace médecin
│   │   └── Patient/     # Espace patient
│   ├── Filters/         # AuthFilter, RoleFilter
│   ├── Models/          # 13 modèles (Patient, Doctor, Appointment, Prescription, Invoice...)
│   ├── Database/
│   │   ├── Migrations/  # 8 fichiers de migration
│   │   └── Seeds/       # DatabaseSeeder
│   └── Views/
│       ├── layouts/     # Layouts (main, auth) + partials (sidebar, topbar)
│       ├── auth/        # Login, mot de passe oublié
│       ├── dashboard/   # Tableaux de bord (admin, médecin, patient)
│       ├── patients/    # CRUD patients + dossier médical
│       ├── appointments/# Liste, calendrier FullCalendar, création
│       ├── doctors/     # CRUD médecins
│       ├── prescriptions/# Ordonnances + impression
│       ├── invoices/    # Facturation + impression
│       ├── admin/       # Gestion utilisateurs, paramètres
│       └── patient/     # Espace patient (RDV, dossier, ordonnances)
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/app.css  # Styles custom
│       └── js/app.js    # JavaScript (sidebar, calculs facture, ordonnance)
├── writable/            # Cache, logs, sessions
├── .env                 # Configuration
└── composer.json
```

---

## Fonctionnalités

### Rôles utilisateurs
- **Super Admin** — Accès total
- **Admin / Secrétaire** — Gestion complète (patients, RDV, médecins, facturation)
- **Médecin** — Agenda, patients, ordonnances
- **Patient** — Espace personnel (RDV, dossier, ordonnances, factures)

### Modules
1. **Authentification** — Login sécurisé, sessions, RBAC
2. **Tableau de bord** — Statistiques, graphiques (Chart.js), RDV du jour
3. **Gestion patients** — Dossier médical complet (antécédents, allergies, groupe sanguin)
4. **Rendez-vous** — Liste + Calendrier interactif (FullCalendar), statuts en temps réel
5. **Médecins** — Profils, spécialités, horaires, tarifs
6. **Ordonnances** — Création avec médicaments, impression professionnelle
7. **Facturation** — Devis/factures, calcul automatique, encaissement, impression
8. **Administration** — Gestion utilisateurs, paramètres clinique

### Technologies
- PHP CodeIgniter 4.x
- MySQL 8.0+
- Bootstrap 5.3
- FullCalendar 6.x (calendrier des RDV)
- Chart.js 4.x (graphiques dashboard)
- Bootstrap Icons 1.11
