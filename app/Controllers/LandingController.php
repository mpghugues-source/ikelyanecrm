<?php

namespace App\Controllers;

use App\Libraries\EmailService;
use CodeIgniter\HTTP\RedirectResponse;

class LandingController extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (session()->get('logged_in')) {
            return redirect()->to($this->getDashboardRoute());
        }
        return view('layouts/landing', ['title' => 'IkelyaneMed — Gestion Médicale SaaS']);
    }

    public function contact()
    {
        $rules = [
            'nom'     => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'sujet'   => 'required',
            'message' => 'required|min_length[10]|max_length[2000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/#contact')->with('contact_error', $this->validator->getErrors());
        }

        $nom     = esc($this->request->getPost('nom'));
        $email   = esc($this->request->getPost('email'));
        $sujet   = esc($this->request->getPost('sujet'));
        $message = esc($this->request->getPost('message'));

        $sujets = [
            'demo'        => 'Demande de démo',
            'tarifs'      => 'Renseignement tarifs',
            'support'     => 'Support technique',
            'partenariat' => 'Partenariat',
            'autre'       => 'Autre',
        ];
        $sujetLabel = $sujets[$sujet] ?? $sujet;

        try {
            $emailService = new EmailService();
            $emailService->sendContactForm(
                $nom,
                $email,
                $sujetLabel,
                $message
            );
        } catch (\Exception $e) {
            log_message('error', 'Contact form email error: ' . $e->getMessage());
        }

        return redirect()->to('/#contact')->with('contact_success', true);
    }

    private function getDashboardRoute(): string
    {
        return match (session()->get('role')) {
            'admin', 'super_admin', 'secretaire' => '/admin/dashboard',
            'medecin'  => '/medecin/dashboard',
            'patient'  => '/patient/dashboard',
            default    => '/login',
        };
    }
}
