<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form', 'text'];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        \Config\Services::language()->setLocale(session()->get('locale') ?? 'fr');
    }

    protected function getTenantId(): int
    {
        return (int) session()->get('tenant_id');
    }

    protected function getUserId(): int
    {
        return (int) session()->get('user_id');
    }

    protected function getMedecinId(): int
    {
        return (int) session()->get('medecin_id');
    }
}
