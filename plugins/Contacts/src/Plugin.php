<?php
declare(strict_types=1);

namespace Contacts;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Console\CommandCollection;
use Cake\Utility\Inflector;

/**
 * Plugin for CustomPages
 */
class Plugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach custom-pages.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
    }


    public function routes(RouteBuilder $routes): void
    {

        $controllers = ['Contacts', 'ContactForms', 'ContactFields'];
        

		$routes->scope('/', function (RouteBuilder $builder) use ($controllers) {

			foreach ($controllers as $controller) {
				$dashedRoute = Inflector::dasherize($controller);
                if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
                    $builder->connect('/' . ACTIVE_LANGUAGE . '/' . $dashedRoute . '/{action}/*', ['plugin' => $this->name, 'controller' => $controller]);
                    $builder->connect('/' . ACTIVE_LANGUAGE . '/' . $dashedRoute . '/{action}', ['plugin' => $this->name, 'controller' => $controller]);
                    $builder->connect('/' . ACTIVE_LANGUAGE . '/' . $dashedRoute . '', ['plugin' => $this->name, 'controller' => $controller, 'action' => 'index']);
                } else {
                    $builder->connect('/' . $dashedRoute . '/{action}/*', ['plugin' => $this->name, 'controller' => $controller]);
                    $builder->connect('/' . $dashedRoute . '/{action}', ['plugin' => $this->name, 'controller' => $controller]);
                    $builder->connect('/' . $dashedRoute . '', ['plugin' => $this->name, 'controller' => $controller, 'action' => 'index']);
                }
			}

			$builder->fallbacks();
		});

		$routes->prefix('Admin', function (RouteBuilder $builder) use ($controllers) {
            foreach ($controllers as $controller) {
                $dashedRoute = Inflector::dasherize($controller);
                $builder->connect('/' . $dashedRoute . '/{action}/*', ['plugin' => $this->name, 'controller' => $controller]);
    			$builder->connect('/' . $dashedRoute . '/{action}', ['plugin' => $this->name, 'controller' => $controller]);
    			$builder->connect('/' . $dashedRoute , ['plugin' => $this->name, 'controller' => $controller, 'action' => 'index']);
            }


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
