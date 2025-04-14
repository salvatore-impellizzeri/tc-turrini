<?php
declare(strict_types=1);

namespace Menu;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Console\CommandCollection;

/**
 * Plugin for Menu
 */
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


		$routes->prefix('Admin', function (RouteBuilder $builder) {
			$builder->connect('/menus/{action}/*', ['plugin' => 'Menu', 'controller' => 'Menus']);
			$builder->connect('/menus/{action}', ['plugin' => 'Menu', 'controller' => 'Menus']);
			$builder->connect('/menus', ['plugin' => 'Menu', 'controller' => 'Menus', 'action' => 'index']);

			$builder->connect('/menu-items/{action}/*', ['plugin' => 'Menu', 'controller' => 'MenuItems']);
			$builder->connect('/menu-items/{action}', ['plugin' => 'Menu', 'controller' => 'MenuItems']);
			$builder->connect('/menu-items', ['plugin' => 'Menu', 'controller' => 'MenuItems', 'action' => 'index']);

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
