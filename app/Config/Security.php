<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF protection method.
     * Options: 'cookie' or 'session'
     */
    public string $csrfProtection = 'session';

    public bool $tokenRandomize = false;

    /**
     * Regenerate the CSRF token on every request.
     */
    public bool $regenerate = false;

    /**
     * CSRF token name used in forms.
     */
    public string $tokenName = 'csrf_token_name';

    /**
     * CSRF header name (for AJAX calls).
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * CSRF cookie name.
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * Token expiration in seconds. 0 = session lifetime.
     */
    public int $expires = 7200;

    /**
     * Redirect on failure instead of returning a 403.
     */
    public bool $redirect = false;

    /**
     * URIs to exclude from CSRF check (e.g. API endpoints).
     */
    public array $excludeURIs = [
        'admin/appointments/*/status',
        'medecin/appointments/*/status',
        'api/*',
    ];
}
