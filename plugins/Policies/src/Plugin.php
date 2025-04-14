<?php
declare(strict_types=1);

namespace Policies;

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
		
		$routes->scope('/', function (RouteBuilder $builder) {
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
				$builder->connect('/' . ACTIVE_LANGUAGE . '/policies/{action}/*', ['plugin' => 'Policies', 'controller' => 'Policies']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/policies/{action}', ['plugin' => 'Policies', 'controller' => 'Policies']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/policies', ['plugin' => 'Policies', 'controller' => 'Policies', 'action' => 'index']);
				
			} else {
				$builder->connect('/policies/{action}/*', ['plugin' => 'Policies', 'controller' => 'Policies']);
				$builder->connect('/policies/{action}', ['plugin' => 'Policies', 'controller' => 'Policies']);
				$builder->connect('/policies', ['plugin' => 'Policies', 'controller' => 'Policies', 'action' => 'index']);

			}
			$builder->fallbacks();
		});
		
		$routes->prefix('Admin', function (RouteBuilder $builder) {
			$builder->connect('/policies/{action}/*', ['plugin' => 'Policies', 'controller' => 'Policies']);
			$builder->connect('/policies/{action}', ['plugin' => 'Policies', 'controller' => 'Policies']);
			$builder->connect('/policies', ['plugin' => 'Policies', 'controller' => 'Policies', 'action' => 'index']);
			
			$builder->fallbacks();		
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
