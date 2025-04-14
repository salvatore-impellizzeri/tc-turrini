<?php
declare(strict_types=1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
//use Cake\Http\Middleware\EncryptedCookieMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;
use Cake\ORM\Behavior\TranslateBehavior;
use Cake\ORM\Behavior\Translate\ShadowTableStrategy;
use App\Middleware\CachedAssetMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
	implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
		//die('migrazione in corso');
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

		// imposta le shadow table come comportamento di default delle traduzioni
		TranslateBehavior::setDefaultStrategyClass(ShadowTableStrategy::class);

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here

		// autenticazione
		$this->addPlugin('Authentication');

		// compressione asset js e css e interfacciamento con lessc.php
		$this->addPlugin('AssetCompress');

		// caricamento ulteriori plugin del CMS
		$this->addPlugin('Articles');
        $this->addPlugin('Images');
		$this->addPlugin('Attachments');
        $this->addPlugin('Services');
        $this->addPlugin('BackendMenu');
        $this->addPlugin('ContentBlocks');
        $this->addPlugin('Blog');
        $this->addPlugin('CustomPages');
        $this->addPlugin('Menu');
        $this->addPlugin('Contacts');
        $this->addPlugin('Snippets');
        $this->addPlugin('Sliders');
        $this->addPlugin('News');
        $this->addPlugin('Events');
        $this->addPlugin('Policies');
        $this->addPlugin('Cookies');
        $this->addPlugin('Products');
        $this->addPlugin('Clients');
        $this->addPlugin('SefUrls');
        $this->addPlugin('CsvView');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {


		$csrf = new CsrfProtectionMiddleware();


		$middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

			->add(new CachedAssetMiddleware())

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/controllers/middleware.html#cross-site-request-forgery-csrf-middleware
            ->add($csrf)

			/*->add(new EncryptedCookieMiddleware(
				['CookieAuth', 'FrontendCookieAuth'],
				Configure::read('Security.cookieKey')
			))*/

			->add(new AuthenticationMiddleware($this));

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

	public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
	{

		if ($request->getParam('prefix') == 'Admin') {
			// backend

			$authenticationService = new AuthenticationService([
				'unauthenticatedRedirect' => Router::url('/admin/users/login'),
				'queryParam' => 'redirect',
			]);


			// Load identifiers, ensure we check email and password fields
			$authenticationService->loadIdentifier('Authentication.Password', [
				'fields' => [
					'username' => 'username',
					'password' => 'password',
				]
			]);

			$authenticationService->loadAuthenticator('Authentication.Cookie', [
				'fields' => [
					'username' => 'username',
					'password' => 'password',
				],
			    'cookie' => [
					// cookie expires in 365 days from now
					'expires' => \Cake\Chronos\Chronos::now()->addDays(365)
				]
			]);

			// Load the authenticators, you want session first
			$authenticationService->loadAuthenticator('Authentication.Session');


			// Configure form data check to pick email and password
			$authenticationService->loadAuthenticator('Authentication.Form', [
				'fields' => [
					'username' => 'username',
					'password' => 'password',
				],
				'loginUrl' => Router::url('/admin/users/login'),
			]);

		} else {

			$unauthenticatedRedirectUrl = Router::url('/clients/login');
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
				$unauthenticatedRedirectUrl = Router::url('/'.ACTIVE_LANGUAGE.'/clients/login');
			}

			// AUTENTICAZIONE FRONTEND
			$authenticationService = new AuthenticationService([
				'unauthenticatedRedirect' => $unauthenticatedRedirectUrl,
				'queryParam' => 'redirect',
			]);

			// Load identifiers, ensure we check email and password fields
			$authenticationService->loadIdentifier('Authentication.Password', [
				'resolver' => [
			        'className' => 'Authentication.Orm',
			        'userModel' => 'Clients.Clients',
					'finder' => 'enabled',
			    ],
				'fields' => [
					'username' => 'email',
					'password' => 'password',
				]
			]);



			// Load the authenticators
			$authenticationService->loadAuthenticator('Authentication.Session', [
				'sessionKey' => 'FrontendAuth'
			]);

			$authenticationService->loadAuthenticator('Authentication.Cookie', [
				'fields' => [
					'username' => 'email',
					'password' => 'password',
				],
			    'cookie' => [
					// cookie expires in 365 days from now
					'name' => 'FrontendCookieAuth',
					'expires' => \Cake\Chronos\Chronos::now()->addDays(365)
				]
			]);


			// Configure form data check to pick email and password
			$authenticationService->loadAuthenticator('Authentication.Form', [
				'fields' => [
					'username' => 'email',
					'password' => 'password',
				],
				'loginUrl' => $unauthenticatedRedirectUrl,
			]);

		}



		return $authenticationService;
	}

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Cake/Repl');
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
