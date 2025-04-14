<?php
declare(strict_types=1);

namespace Snippets;

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
				$builder->connect('/' . ACTIVE_LANGUAGE . '/snippets/{action}/*', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/snippets/{action}', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
				$builder->connect('/' . ACTIVE_LANGUAGE . '/snippets', ['plugin' => 'Snippets', 'controller' => 'Snippets', 'action' => 'index']);
				
			} else {
				$builder->connect('/snippets/{action}/*', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
				$builder->connect('/snippets/{action}', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
				$builder->connect('/snippets', ['plugin' => 'Snippets', 'controller' => 'Snippets', 'action' => 'index']);

			}

			$builder->fallbacks();
		});

		$routes->prefix('Admin', function (RouteBuilder $builder) {
			$builder->connect('/snippets/{action}/*', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
			$builder->connect('/snippets/{action}', ['plugin' => 'Snippets', 'controller' => 'Snippets']);
			$builder->connect('/snippets', ['plugin' => 'Snippets', 'controller' => 'Snippets', 'action' => 'index']);

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
