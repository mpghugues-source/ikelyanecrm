<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;
class App extends BaseConfig
{
    public string $baseURL = 'https://ikelyanecrm.com/';
    public array  $allowedHostnames = [];
    public string $indexPage        = '';
    public string $uriProtocol      = 'REQUEST_URI';
    public string $permittedURIChars= 'a-z 0-9~%.:_\-';
    public string $defaultLocale    = 'fr';
    public bool   $negotiateLocale  = false;
    public array  $supportedLocales = ['fr', 'en'];
    public string $appTimezone      = 'Africa/Algiers';
    public string $charset          = 'UTF-8';
    public bool   $forceGlobalSecureRequests = false;
    public bool   $CSPEnabled        = false;
    public string|array $proxyIPs   = [];
    public string $CSRFTokenName    = 'csrf_token_name';
    public string $CSRFHeaderName   = 'X-CSRF-TOKEN';
    public string $CSRFCookieName   = 'csrf_cookie_name';
    public int    $CSRFExpire       = 7200;
    public bool   $CSRFRegenerate   = true;
    public array  $CSRFExcludeURIs  = [];
    public string $CSRFSameSite     = 'Lax';
    public string $cookiePrefix     = '';
    public string $cookieDomain     = '';
    public string $cookiePath       = '/';
    public bool   $cookieSecure     = false;
    public bool   $cookieHTTPOnly   = false;
    public string $cookieSameSite   = 'Lax';
    public string $salt             = '';
    public string $sessionDriver            = 'CodeIgniter\Session\Handlers\DatabaseHandler';
    public string $sessionCookieName        = 'ikelyanecrm_session';
    public int    $sessionExpiration        = 7200;
    public string $sessionSavePath          = 'ci_sessions';
    public bool   $sessionMatchIP           = false;
    public int    $sessionTimeToUpdate      = 300;
    public bool   $sessionRegenerateDestroy = false;
    public string $encryptionKey = 'IkelyaneCRM2024SecretKeyXvZ9mNpQ';
}
