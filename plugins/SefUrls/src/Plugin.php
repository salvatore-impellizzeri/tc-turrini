<?php
declare(strict_types=1);

namespace SefUrls;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Console\CommandCollection;


class Plugin extends BasePlugin
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
    }

    public function routes(RouteBuilder $routes): void
    {
		
		$routes->plugin('SefUrls', function (RouteBuilder $routes) {			
					
			$routes->connect('/{action}/*', ['controller' => 'SefUrls']);
			$routes->connect('/{action}', ['controller' => 'SefUrls']);
			$routes->connect('/', ['controller' => 'SefUrls', 'action' => 'index']);
			$routes->fallbacks();			
			
		});		
		
		$routes->prefix('Admin', function (RouteBuilder $routes) {
			$routes->plugin('SefUrls', function (RouteBuilder $routes) {			
				$routes->connect('/{action}/*', ['controller' => 'SefUrls']);
				$routes->connect('/{action}', ['controller' => 'SefUrls']);
				$routes->connect('/', ['controller' => 'SefUrls', 'action' => 'index']);
				$routes->fallbacks();						
			});	
		});

        parent::routes($routes);
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Add your middlewares here

        return $middlewareQueue;
    }

    public function console(CommandCollection $commands) : CommandCollection
    {
        // Add your commands here

        $commands = parent::console($commands);

        return $commands;
    }
}
