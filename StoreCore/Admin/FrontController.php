<?php
namespace StoreCore\Admin;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

use StoreCore\AbstractController;
use StoreCore\Admin\AccessControlWhitelist;
use StoreCore\Registry;
use StoreCore\ResponseFactory;
use StoreCore\Route;
use StoreCore\Database\RouteFactory;
use StoreCore\Session;

/**
 * Administration Front Controller
 *
 * @author    Ward van der Put <Ward.van.der.Put@storecore.org>
 * @copyright Copyright © 2015–2019 StoreCore™
 * @license   https://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\Core
 * @version   1.0.0-beta.1
 */
class FrontController extends AbstractController implements LoggerAwareInterface
{
    /**
     * @var string VERSION
     *   Semantic Version (SemVer).
     */
    const VERSION = '1.0.0-beta.1';

    /**
     * @param \StoreCore\Registry $registry
     * @return self
     */
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);

        // Run the installer on an incomplete installation.
        if (!defined('STORECORE_GUID')) {
            $this->install();
        }

        // Check the whitelist.
        $whitelist = new AccessControlWhitelist($this->Registry);
        $whitelist->check();

        // Check if there is a user signed in.
        if ($this->Session->has('User')) {
            $this->User = $this->Session->get('User');
        } else {
            $factory = new ResponseFactory();
            $response = $factory->createResponse(302);
            $response->redirect('/admin/sign-in/');
            exit;
        }

        // Find a matching route or route collection.
        $route = false;
        switch ($this->Request->getRequestTarget()) {
            case '/admin/sign-out/':
                $route = new Route('/admin/sign-out/', '\StoreCore\Admin\User', 'signOut');
                break;
            default:
                $router = new RouteFactory($this->Registry);
                $route = $router->find($this->Request->getRequestTarget());
                if ($route === null) {
                    $route = false;
                }
                break;
        }

        if ($route !== false) {
            $this->Registry->set('Route', $route);
            $route->dispatch();
        } else {
            $this->Logger->debug('Unknown admin route: ' . $this->Request->getRequestTarget());
            $factory = new ResponseFactory();
            $response = $factory->createResponse(404);
            $response->addHeader('HTTP/1.1 404 Not Found');
            $response->output();
            exit;
        }
    }

    /**
     * Run the installer if the Installer.php class file exists.
     *
     * @param void
     * @return void
     */
    public function install()
    {
        if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'Installer.php')) {
            $this->Logger->warning('Installer loaded.');
            $route = new Route('/install/', '\StoreCore\Admin\Installer');
            $route->dispatch();
        } else {
            $this->Logger->notice('StoreCore core class file Installer.php not found.');
        }
        exit;
    }

    /**
     * Set a logger.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *   PSR-3 “Logger Interface” compliant logger object.
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->Logger = $logger;
        $this->Registry->set('Logger', $this->Logger);
    }
}
