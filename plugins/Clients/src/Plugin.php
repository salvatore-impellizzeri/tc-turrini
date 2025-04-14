<?php
declare(strict_types=1);

namespace Clients;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Console\CommandCollection;

class Plugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {

		$routes->scope('/', function (RouteBuilder $builder) {
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
				$builder->connect('/' . ACTIVE_LANGUAGE . '/clients/{action}/*', ['plugin' => 'Clients', 'controller' => 'Clients']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/clients/{action}', ['plugin' => 'Clients', 'controller' => 'Clients']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/clients', ['plugin' => 'Clients', 'controller' => 'Clients', 'action' => 'index']);

				$builder->connect('/' . ACTIVE_LANGUAGE . '/client-addresses/{action}/*', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/client-addresses/{action}', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/client-addresses', ['plugin' => 'Clients', 'controller' => 'ClientAddresses', 'action' => 'index']);
			} else {
				$builder->connect('/clients/{action}/*', ['plugin' => 'Clients', 'controller' => 'Clients']);
				$builder->connect('/clients/{action}', ['plugin' => 'Clients', 'controller' => 'Clients']);
				$builder->connect('/clients', ['plugin' => 'Clients', 'controller' => 'Clients', 'action' => 'index']);

				$builder->connect('/client-addresses/{action}/*', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
				$builder->connect('/client-addresses/{action}', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
				$builder->connect('/client-addresses', ['plugin' => 'Clients', 'controller' => 'ClientAddresses', 'action' => 'index']);	
			}
			$builder->fallbacks();

		});

		$routes->prefix('Admin', function (RouteBuilder $builder) {

			$builder->connect('/clients/{action}/*', ['plugin' => 'Clients', 'controller' => 'Clients']);
			$builder->connect('/clients/{action}', ['plugin' => 'Clients', 'controller' => 'Clients']);
			$builder->connect('/clients', ['plugin' => 'Clients', 'controller' => 'Clients', 'action' => 'index']);

			$builder->connect('/client-addresses/{action}/*', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
			$builder->connect('/client-addresses/{action}', ['plugin' => 'Clients', 'controller' => 'ClientAddresses']);
			$builder->connect('/client-addresses', ['plugin' => 'Clients', 'controller' => 'ClientAddresses', 'action' => 'index']);			

			$builder->fallbacks();

		});

        parent::routes($routes);
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Add your middlewares here

        return $middlewareQueue;
    }

    /**
     * Add commands for the plugin.
     *
     * @param \Cake\Console\CommandCollection $commands The command collection to update.
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands) : CommandCollection
    {
        // Add your commands here

        $commands = parent::console($commands);

        return $commands;
    }
}
