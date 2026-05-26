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
    public bool   $forceGlobalSecureRequests = true;
    public bool   $CSPEnabled        = false;
    public string|array $proxyIPs   = ['172.16.0.0/12', '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13', '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22'];
    public string $CSRFTokenName    = 'csrf_token_name';
    public string $CSRFHeaderName   = 'X-CSRF-TOKEN';
    public string $CSRFCookieName   = 'csrf_cookie_name';
    public int    $CSRFExpire       = 7200;
    public bool   $CSRFRegenerate   = true;
    public array  $CSRFExcludeURIs  = [];
    public string $CSRFSameSite     = 'Lax';
    public string $cookiePrefix     = '__Secure-';
    public string $cookieDomain     = 'ikelyanecrm.com';
    public string $cookiePath       = '/';
    public bool   $cookieSecure     = true;
    public bool   $cookieHTTPOnly   = true;
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
