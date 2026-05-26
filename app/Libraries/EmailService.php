<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected Email $mailer;
    protected array $config;

    public function __construct()
    {
        $this->config = [
            'protocol'  => getenv('EMAIL_PROTOCOL') ?: 'smtp',
            'SMTPHost'  => getenv('SMTP_HOST')       ?: 'smtp.gmail.com',
            'SMTPUser'  => getenv('SMTP_USER')       ?: '',
            'SMTPPass'  => getenv('SMTP_PASS')       ?: '',
            'SMTPPort'  => (int)(getenv('SMTP_PORT') ?: 587),
            'SMTPCrypto'=> getenv('SMTP_CRYPTO')     ?: 'tls',
            'mailType'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'fromEmail' => getenv('MAIL_FROM')       ?: 'noreply@ikelyanemed.com',
            'fromName'  => getenv('MAIL_FROM_NAME')  ?: 'IkelyaneMed',
        ];

        $this->mailer = \Config\Services::email();
        $this->mailer->initialize($this->config);
    }

    /** Email de bienvenue après inscription */
    public function sendWelcome(string $toEmail, string $clinicName, string $adminName): bool
    {
        $html = $this->template('Bienvenue sur IkelyaneMed !', "
            <p>Bonjour <strong>{$adminName}</strong>,</p>
            <p>Votre espace clinique <strong>{$clinicName}</strong> a été créé avec succès sur IkelyaneMed.</p>
            <p>Vous disposez maintenant de <strong>30 jours d'essai gratuit</strong> pour découvrir toutes nos fonctionnalités.</p>
            <div style='text-align:center;margin:32px 0'>
                <a href='".base_url('/login')."' style='background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;font-size:1rem'>
                    Accéder à mon espace →
                </a>
            </div>
            <p style='color:#64748b;font-size:.9rem'>Si vous avez des questions, contactez-nous à <a href='mailto:support@ikelyanemed.com'>support@ikelyanemed.com</a></p>
        ");

        return $this->send($toEmail, $adminName, 'Bienvenue sur IkelyaneMed — ' . $clinicName, $html);
    }

    /** Confirmation de rendez-vous */
    public function sendRdvConfirmation(array $rdv, array $patient, array $medecin, array $tenant): bool
    {
        $date    = date('d/m/Y', strtotime($rdv['date_rdv']));
        $heure   = substr($rdv['heure_rdv'], 0, 5);
        $docName = 'Dr. ' . $medecin['prenom'] . ' ' . $medecin['nom'];

        $html = $this->template('Rendez-vous confirmé', "
            <p>Bonjour <strong>{$patient['prenom']} {$patient['nom']}</strong>,</p>
            <p>Votre rendez-vous a été confirmé :</p>
            <div style='background:#f8fafc;border-radius:12px;padding:24px;margin:20px 0;border-left:4px solid #1a56db'>
                <table style='width:100%'>
                    <tr><td style='color:#64748b;padding:6px 0'>📅 Date</td><td style='font-weight:700'>{$date}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>⏰ Heure</td><td style='font-weight:700'>{$heure}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>👨‍⚕️ Médecin</td><td style='font-weight:700'>{$docName}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>🏥 Clinique</td><td style='font-weight:700'>{$tenant['nom']}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>📝 Motif</td><td style='font-weight:700'>{$rdv['motif']}</td></tr>
                </table>
            </div>
            <p style='color:#64748b;font-size:.9rem'>Pour annuler ou modifier ce rendez-vous, contactez la clinique au {$tenant['telephone']}.</p>
        ");

        return $this->send($patient['email'], $patient['prenom'].' '.$patient['nom'],
            "Confirmation RDV — {$date} à {$heure}", $html);
    }

    /** Rappel rendez-vous (24h avant) */
    public function sendRdvReminder(array $rdv, array $patient, array $medecin, array $tenant): bool
    {
        $date    = date('d/m/Y', strtotime($rdv['date_rdv']));
        $heure   = substr($rdv['heure_rdv'], 0, 5);
        $docName = 'Dr. ' . $medecin['prenom'] . ' ' . $medecin['nom'];

        $html = $this->template('⏰ Rappel — Rendez-vous demain', "
            <p>Bonjour <strong>{$patient['prenom']} {$patient['nom']}</strong>,</p>
            <p>Nous vous rappelons que vous avez un rendez-vous <strong>demain</strong> :</p>
            <div style='background:#fef3c7;border-radius:12px;padding:24px;margin:20px 0;border-left:4px solid #d97706'>
                <table style='width:100%'>
                    <tr><td style='color:#64748b;padding:6px 0'>📅 Date</td><td style='font-weight:700'>{$date}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>⏰ Heure</td><td style='font-weight:700'>{$heure}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>👨‍⚕️ Médecin</td><td style='font-weight:700'>{$docName}</td></tr>
                    <tr><td style='color:#64748b;padding:6px 0'>🏥 Clinique</td><td style='font-weight:700'>{$tenant['nom']}</td></tr>
                </table>
            </div>
            <p style='color:#64748b;font-size:.9rem'>En cas d'empêchement, merci de nous prévenir au {$tenant['telephone']}.</p>
        ");

        return $this->send($patient['email'], $patient['prenom'].' '.$patient['nom'],
            "⏰ Rappel RDV demain à {$heure} — {$tenant['nom']}", $html);
    }

    /** Confirmation création compte gratuit → envoyé au nouvel utilisateur */
    public function sendAccountCreatedToUser(
        string $toEmail,
        string $adminName,
        string $clinicName,
        string $password
    ): bool {
        $loginUrl = base_url('/login');
        $html = $this->template('Votre compte MyEcclesia a été créé !', "
            <p>Bonjour <strong>{$adminName}</strong>,</p>
            <p>Votre compte sur <strong>MyEcclesia</strong> a été créé avec succès. Voici vos identifiants de connexion :</p>
            <div style='background:#f0f9ff;border-radius:12px;padding:24px;margin:20px 0;border-left:4px solid #1a56db'>
                <table style='width:100%'>
                    <tr>
                        <td style='color:#64748b;padding:8px 0;width:140px'>Organisation</td>
                        <td style='font-weight:700;color:#0f172a'>{$clinicName}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Identifiant (login)</td>
                        <td style='font-weight:700;color:#0f172a'>{$toEmail}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Mot de passe</td>
                        <td style='font-weight:700;color:#0f172a'>{$password}</td>
                    </tr>
                </table>
            </div>
            <p style='color:#dc2626;font-size:.85rem'>Pour votre sécurité, nous vous recommandons de changer votre mot de passe dès votre première connexion.</p>
            <div style='text-align:center;margin:32px 0'>
                <a href='{$loginUrl}' style='background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700;font-size:1rem'>
                    Accéder à mon espace →
                </a>
            </div>
            <hr style='border:none;border-top:1px solid #e2e8f0;margin:32px 0'>
            <div style='text-align:center'>
                <p style='font-size:1rem;font-weight:700;color:#1a56db;margin:0'>MyEcclesia</p>
                <p style='color:#64748b;font-size:.85rem;margin:4px 0'>Votre partenaire de gestion digitale</p>
                <p style='color:#94a3b8;font-size:.8rem;margin:4px 0'>
                    <a href='mailto:info@myecclesia.org' style='color:#1a56db'>info@myecclesia.org</a> ·
                    <a href='https://www.myecclesia.org' style='color:#1a56db'>www.myecclesia.org</a>
                </p>
            </div>
        ");

        return $this->send($toEmail, $adminName, 'Votre compte MyEcclesia — ' . $clinicName, $html);
    }

    /** Notification création compte gratuit → envoyée à l'administrateur plateforme */
    public function sendAccountCreatedToAdmin(
        string $userEmail,
        string $adminName,
        string $clinicName,
        string $plan
    ): bool {
        $adminNotifEmail = getenv('ADMIN_NOTIFICATION_EMAIL') ?: 'info@myecclesia.org';
        $date = date('d/m/Y à H:i');

        $html = $this->template('Nouveau compte créé — ' . $clinicName, "
            <p>Un nouveau compte vient d'être créé sur la plateforme :</p>
            <div style='background:#f8fafc;border-radius:12px;padding:24px;margin:20px 0;border-left:4px solid #7c3aed'>
                <table style='width:100%'>
                    <tr>
                        <td style='color:#64748b;padding:8px 0;width:140px'>Organisation</td>
                        <td style='font-weight:700;color:#0f172a'>{$clinicName}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Responsable</td>
                        <td style='font-weight:700;color:#0f172a'>{$adminName}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Email</td>
                        <td style='font-weight:700;color:#0f172a'>{$userEmail}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Plan</td>
                        <td style='font-weight:700;color:#7c3aed;text-transform:uppercase'>{$plan}</td>
                    </tr>
                    <tr>
                        <td style='color:#64748b;padding:8px 0'>Date</td>
                        <td style='font-weight:700;color:#0f172a'>{$date}</td>
                    </tr>
                </table>
            </div>
            <hr style='border:none;border-top:1px solid #e2e8f0;margin:32px 0'>
            <div style='text-align:center'>
                <p style='font-size:1rem;font-weight:700;color:#1a56db;margin:0'>MyEcclesia</p>
                <p style='color:#64748b;font-size:.85rem;margin:4px 0'>Votre partenaire de gestion digitale</p>
                <p style='color:#94a3b8;font-size:.8rem;margin:4px 0'>
                    <a href='mailto:info@myecclesia.org' style='color:#1a56db'>info@myecclesia.org</a> ·
                    <a href='https://www.myecclesia.org' style='color:#1a56db'>www.myecclesia.org</a>
                </p>
            </div>
        ");

        return $this->send($adminNotifEmail, 'Admin MyEcclesia', '[Nouveau compte] ' . $clinicName . ' — ' . $userEmail, $html);
    }

    /** Formulaire de contact landing page */
    public function sendContactForm(string $fromName, string $fromEmail, string $sujet, string $message): bool
    {
        $adminEmail = env('MAIL_FROM', 'noreply@ikelyanemed.com');
        $html = $this->template("Nouveau message — {$sujet}", "
            <p><strong>De :</strong> {$fromName} ({$fromEmail})</p>
            <p><strong>Sujet :</strong> {$sujet}</p>
            <div style='background:#f8fafc;border-radius:12px;padding:20px;margin:20px 0;border-left:4px solid #1a56db;white-space:pre-line'>
                {$message}
            </div>
            <p style='color:#64748b;font-size:.85rem'>Répondre à : <a href='mailto:{$fromEmail}'>{$fromEmail}</a></p>
        ");
        return $this->send($adminEmail, 'IkelyaneMed', "[Contact] {$sujet} — {$fromName}", $html);
    }

    /** Réinitialisation du mot de passe */
    public function sendPasswordReset(string $toEmail, string $name, string $resetToken): bool
    {
        $resetUrl = base_url('/reset-password?token=' . $resetToken);
        $html = $this->template('Réinitialisation de votre mot de passe', "
            <p>Bonjour <strong>{$name}</strong>,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous :</p>
            <div style='text-align:center;margin:32px 0'>
                <a href='{$resetUrl}' style='background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:700'>
                    Réinitialiser mon mot de passe
                </a>
            </div>
            <p style='color:#64748b;font-size:.85rem'>Ce lien expire dans <strong>1 heure</strong>. Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
        ");

        return $this->send($toEmail, $name, 'Réinitialisation de votre mot de passe — IkelyaneMed', $html);
    }

    /** Template HTML générique */
    private function template(string $title, string $body): string
    {
        return "
        <!DOCTYPE html>
        <html lang='fr'>
        <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width'></head>
        <body style='margin:0;padding:0;background:#f1f5f9;font-family:Inter,Arial,sans-serif'>
            <table width='100%' cellpadding='0' cellspacing='0' style='background:#f1f5f9;padding:40px 0'>
                <tr><td align='center'>
                    <table width='600' cellpadding='0' cellspacing='0' style='background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)'>
                        <!-- Header -->
                        <tr>
                            <td style='background:linear-gradient(135deg,#1a56db,#7c3aed);padding:32px 40px;text-align:center'>
                                <div style='font-size:1.6rem;font-weight:800;color:#fff;letter-spacing:-1px'>IkelyaneMed</div>
                                <div style='color:rgba(255,255,255,.7);font-size:.85rem;margin-top:4px'>Gestion Médicale Intelligente</div>
                            </td>
                        </tr>
                        <!-- Body -->
                        <tr>
                            <td style='padding:40px'>
                                <h2 style='font-size:1.3rem;font-weight:700;color:#0f172a;margin-bottom:20px'>{$title}</h2>
                                <div style='color:#374151;line-height:1.7;font-size:.95rem'>
                                    {$body}
                                </div>
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td style='background:#f8fafc;padding:24px 40px;border-top:1px solid #e2e8f0;text-align:center'>
                                <p style='color:#94a3b8;font-size:.8rem;margin:0'>
                                    © " . date('Y') . " IkelyaneMed · Tous droits réservés<br>
                                    <a href='".base_url()."' style='color:#1a56db'>www.ikelyanemed.com</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td></tr>
            </table>
        </body>
        </html>";
    }

    private function send(string $to, string $name, string $subject, string $html): bool
    {
        try {
            $this->mailer->setTo($to, $name);
            $this->mailer->setFrom($this->config['fromEmail'], $this->config['fromName']);
            $this->mailer->setSubject($subject);
            $this->mailer->setMessage($html);
            return $this->mailer->send(false);
        } catch (\Exception $e) {
            log_message('error', 'EmailService error: ' . $e->getMessage());
            return false;
        }
    }
}
