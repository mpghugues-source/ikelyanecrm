<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ─── PAGE D'ACCUEIL / LANDING ──────────────────────────────────
$routes->get('/', 'LandingController::index');

// ─── AUTHENTIFICATION ────────────────────────────────────────────
$routes->get('login',                 'Auth\AuthController::index');
$routes->post('login',                'Auth\AuthController::login');
$routes->get('logout',                'Auth\AuthController::logout');
$routes->get('forgot-password',       'Auth\AuthController::forgotPassword');
$routes->post('forgot-password',      'Auth\AuthController::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth\AuthController::resetPassword/$1');
$routes->post('reset-password',       'Auth\AuthController::updatePassword');

// ─── INSCRIPTION ─────────────────────────────────────────────────
$routes->post('contact',  'LandingController::contact');
$routes->get('register',  'Auth\RegisterController::index');
$routes->post('register', 'Auth\RegisterController::store');

// ─── ABONNEMENT ──────────────────────────────────────────────────
$routes->get('abonnement/expire',     'SubscriptionController::expired',  ['filter' => 'auth']);
$routes->get('abonnement/inactif',    'SubscriptionController::inactive', ['filter' => 'auth']);
$routes->post('abonnement/renouveler','SubscriptionController::renew',    ['filter' => 'auth']);

// ─── PAIEMENT ────────────────────────────────────────────────────
$routes->get('payment/checkout',  'PaymentController::checkout');
$routes->post('payment/set-cycle','PaymentController::setCycle');
$routes->get('payment/verify',    'PaymentController::verify');
$routes->post('payment/webhook',  'PaymentController::webhook');
$routes->get('payment/success',   'PaymentController::success');
$routes->get('payment/failed',    'PaymentController::failed');

// ─── SUPER ADMIN ─────────────────────────────────────────────────
$routes->group('superadmin', ['filter' => 'auth:super_admin'], static function ($routes) {
    $routes->get('dashboard',                    'SuperAdmin\DashboardController::index');

    // Tenants
    $routes->get('tenants',                       'SuperAdmin\TenantController::index');
    $routes->get('tenants/create',                'SuperAdmin\TenantController::create');
    $routes->post('tenants/store',                'SuperAdmin\TenantController::store');
    $routes->get('tenants/(:num)/view',           'SuperAdmin\TenantController::view/$1');
    $routes->get('tenants/(:num)/edit',           'SuperAdmin\TenantController::edit/$1');
    $routes->post('tenants/(:num)/update',        'SuperAdmin\TenantController::update/$1');
    $routes->get('tenants/(:num)/toggle',         'SuperAdmin\TenantController::toggleStatus/$1');
    $routes->post('tenants/(:num)/subscription',  'SuperAdmin\TenantController::updateSubscription/$1');
    $routes->get('tenants/(:num)/delete',         'SuperAdmin\TenantController::delete/$1');

    // Utilisateurs (tous tenants)
    $routes->get('users',               'SuperAdmin\UsersController::index');
    $routes->get('users/(:num)/toggle', 'SuperAdmin\UsersController::toggle/$1');

    // Abonnements & Plans
    $routes->get('subscriptions',                       'SuperAdmin\SubscriptionsController::index');
    $routes->get('subscriptions/plans/create',          'SuperAdmin\SubscriptionsController::create');
    $routes->post('subscriptions/plans/store',          'SuperAdmin\SubscriptionsController::store');
    $routes->get('subscriptions/plans/(:num)/edit',     'SuperAdmin\SubscriptionsController::edit/$1');
    $routes->post('subscriptions/plans/(:num)/update',  'SuperAdmin\SubscriptionsController::update/$1');
    $routes->get('subscriptions/plans/(:num)/delete',   'SuperAdmin\SubscriptionsController::delete/$1');
    $routes->get('subscriptions/plans/(:num)/toggle',   'SuperAdmin\SubscriptionsController::toggle/$1');

    // Statistiques & Paramètres
    $routes->get('stats',           'SuperAdmin\StatsController::index');
    $routes->get('settings',        'SuperAdmin\SettingsController::index');
    $routes->post('settings/save',  'SuperAdmin\SettingsController::save');
});

// ─── ADMIN (tenant admin) ─────────────────────────────────────────
$routes->group('admin', ['filter' => 'auth:admin,manager'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('abonnement','Admin\BillingController::index');

    // Gestion des utilisateurs du tenant
    $routes->get('users',                 'Admin\UserController::index');
    $routes->get('users/create',          'Admin\UserController::create');
    $routes->post('users/store',          'Admin\UserController::store');
    $routes->get('users/(:num)/edit',     'Admin\UserController::edit/$1');
    $routes->post('users/(:num)/update',  'Admin\UserController::update/$1');
    $routes->get('users/(:num)/delete',   'Admin\UserController::delete/$1');

    // Paramètres du tenant
    $routes->get('settings',        'Admin\SettingsController::index');
    $routes->post('settings/save',  'Admin\SettingsController::save');

    // Reports
    $routes->get('reports', 'Admin\ReportController::index');
});

// ─── PROFIL (tous les rôles connectés) ───────────────────────────
$routes->group('profile', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/',         'ProfileController::index');
    $routes->post('update',   'ProfileController::update');
    $routes->post('password', 'ProfileController::changePassword');
});

// ─── MESSAGERIE ──────────────────────────────────────────────────
$routes->group('messages', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/',              'MessageController::index');
    $routes->get('create',         'MessageController::create');
    $routes->post('store',         'MessageController::store');
    $routes->get('(:num)/view',    'MessageController::view/$1');
    $routes->post('(:num)/delete', 'MessageController::delete/$1');
});

// ─── NOTIFICATIONS ────────────────────────────────────────────────
$routes->get('api/notifications',           'NotificationController::getJson',  ['filter' => 'auth']);
$routes->post('api/notifications/read-all', 'NotificationController::markRead', ['filter' => 'auth']);
$routes->get('notifications/read/(:num)',   'NotificationController::markOneRead/$1', ['filter' => 'auth']);

// ─── CRM ─────────────────────────────────────────────────────────
$routes->group('crm', ['filter' => 'auth:admin,manager,commercial,support'], static function ($routes) {
    $routes->get('/',   'Crm\DashboardController::index');

    // Contacts
    $routes->get('contacts',                     'Crm\ContactController::index');
    $routes->get('contacts/create',              'Crm\ContactController::create');
    $routes->post('contacts/store',              'Crm\ContactController::store');
    $routes->get('contacts/(:num)/view',         'Crm\ContactController::view/$1');
    $routes->get('contacts/(:num)/edit',         'Crm\ContactController::edit/$1');
    $routes->post('contacts/(:num)/update',      'Crm\ContactController::update/$1');
    $routes->get('contacts/(:num)/delete',       'Crm\ContactController::delete/$1');

    // Leads & Opportunités
    $routes->get('leads',                        'Crm\LeadController::index');
    $routes->get('leads/kanban',                 'Crm\LeadController::kanban');
    $routes->get('leads/create',                 'Crm\LeadController::create');
    $routes->post('leads/store',                 'Crm\LeadController::store');
    $routes->get('leads/(:num)/view',            'Crm\LeadController::view/$1');
    $routes->get('leads/(:num)/edit',            'Crm\LeadController::edit/$1');
    $routes->post('leads/(:num)/update',         'Crm\LeadController::update/$1');
    $routes->get('leads/(:num)/delete',          'Crm\LeadController::delete/$1');
    $routes->post('leads/update-status',         'Crm\LeadController::updateStatus');

    // Activités
    $routes->get('activities',                   'Crm\ActivityController::index');
    $routes->get('activities/create',            'Crm\ActivityController::create');
    $routes->post('activities/store',            'Crm\ActivityController::store');
    $routes->get('activities/(:num)/edit',       'Crm\ActivityController::edit/$1');
    $routes->post('activities/(:num)/update',    'Crm\ActivityController::update/$1');
    $routes->get('activities/(:num)/complete',   'Crm\ActivityController::complete/$1');
    $routes->get('activities/(:num)/delete',     'Crm\ActivityController::delete/$1');

    // Cas / Tickets
    $routes->get('cases',                        'Crm\CaseController::index');
    $routes->get('cases/create',                 'Crm\CaseController::create');
    $routes->post('cases/store',                 'Crm\CaseController::store');
    $routes->get('cases/(:num)/view',            'Crm\CaseController::view/$1');
    $routes->get('cases/(:num)/edit',            'Crm\CaseController::edit/$1');
    $routes->post('cases/(:num)/update',         'Crm\CaseController::update/$1');
    $routes->get('cases/(:num)/delete',          'Crm\CaseController::delete/$1');

    // Campagnes
    $routes->get('campaigns',                    'Crm\CampaignController::index');
    $routes->get('campaigns/create',             'Crm\CampaignController::create');
    $routes->post('campaigns/store',             'Crm\CampaignController::store');
    $routes->get('campaigns/(:num)/edit',        'Crm\CampaignController::edit/$1');
    $routes->post('campaigns/(:num)/update',     'Crm\CampaignController::update/$1');
    $routes->get('campaigns/(:num)/delete',      'Crm\CampaignController::delete/$1');
});

// ─── ERP ─────────────────────────────────────────────────────────
$routes->group('erp', ['filter' => 'auth:admin,manager'], static function ($routes) {
    $routes->get('/', 'Erp\DashboardController::index');

    // Fournisseurs
    $routes->get('suppliers',                    'Erp\SupplierController::index');
    $routes->get('suppliers/create',             'Erp\SupplierController::create');
    $routes->post('suppliers/store',             'Erp\SupplierController::store');
    $routes->get('suppliers/(:num)/edit',        'Erp\SupplierController::edit/$1');
    $routes->post('suppliers/(:num)/update',     'Erp\SupplierController::update/$1');
    $routes->get('suppliers/(:num)/delete',      'Erp\SupplierController::delete/$1');

    // Inventaire
    $routes->get('inventory',                              'Erp\InventoryController::index');
    $routes->get('inventory/create',                       'Erp\InventoryController::create');
    $routes->post('inventory/store',                       'Erp\InventoryController::store');
    $routes->get('inventory/(:num)/edit',                  'Erp\InventoryController::edit/$1');
    $routes->post('inventory/(:num)/update',               'Erp\InventoryController::update/$1');
    $routes->get('inventory/(:num)/movement',              'Erp\InventoryController::movement/$1');
    $routes->post('inventory/(:num)/movement/store',       'Erp\InventoryController::storeMovement/$1');
    $routes->get('inventory/(:num)/delete',                'Erp\InventoryController::delete/$1');

    // Bons de Commande
    $routes->get('purchase-orders',                        'Erp\PurchaseOrderController::index');
    $routes->get('purchase-orders/create',                 'Erp\PurchaseOrderController::create');
    $routes->post('purchase-orders/store',                 'Erp\PurchaseOrderController::store');
    $routes->get('purchase-orders/(:num)/view',            'Erp\PurchaseOrderController::view/$1');
    $routes->post('purchase-orders/(:num)/status',         'Erp\PurchaseOrderController::updateStatus/$1');
    $routes->get('purchase-orders/(:num)/cancel',          'Erp\PurchaseOrderController::delete/$1');

    // Finance
    $routes->get('finance/transactions',                   'Erp\FinanceController::transactions');
    $routes->post('finance/transactions/store',            'Erp\FinanceController::storeTransaction');
    $routes->get('finance/transactions/(:num)/delete',     'Erp\FinanceController::deleteTransaction/$1');
    $routes->get('finance/accounts',                       'Erp\FinanceController::accounts');
    $routes->post('finance/accounts/store',                'Erp\FinanceController::storeAccount');
    $routes->get('finance/accounts/(:num)/delete',         'Erp\FinanceController::deleteAccount/$1');
    $routes->get('finance/budgets',                        'Erp\FinanceController::budgets');
    $routes->post('finance/budgets/store',                 'Erp\FinanceController::storeBudget');
    $routes->get('finance/budgets/(:num)/delete',          'Erp\FinanceController::deleteBudget/$1');

    // Projets
    $routes->get('projects',                               'Erp\ProjectController::index');
    $routes->get('projects/create',                        'Erp\ProjectController::create');
    $routes->post('projects/store',                        'Erp\ProjectController::store');
    $routes->get('projects/(:num)/view',                   'Erp\ProjectController::view/$1');
    $routes->get('projects/(:num)/edit',                   'Erp\ProjectController::edit/$1');
    $routes->post('projects/(:num)/update',                'Erp\ProjectController::update/$1');
    $routes->get('projects/(:num)/cancel',                 'Erp\ProjectController::delete/$1');
    $routes->post('projects/(:num)/tasks/store',           'Erp\ProjectController::storeTask/$1');
    $routes->post('projects/(:num)/tasks/(:num)/update',   'Erp\ProjectController::updateTask/$1/$2');
    $routes->get('projects/(:num)/tasks/(:num)/delete',    'Erp\ProjectController::deleteTask/$1/$2');
});

// ─── LANGUE ──────────────────────────────────────────────────────
$routes->get('lang/(:alpha)', 'LanguageController::switch/$1');
