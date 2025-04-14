<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Core\Configure;
use Cake\Datasource\FactoryLocator;

return static function (RouteBuilder $routes) {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */

    $routes->setRouteClass(DashedRoute::class);

	$sefModel = FactoryLocator::get('Table')->get('SefUrls.SefUrls');

	if (!empty($_SERVER['REQUEST_URI'])) {
	
		$request = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
		$qs = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		$hash = parse_url($_SERVER['REQUEST_URI'], PHP_URL_FRAGMENT);

		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			$request = urldecode(substr($request, 3));
		}

		$sefUrl = $sefModel->find()->where(['rewritten' => $request, 'locale' => ACTIVE_LANGUAGE])->first();

		if(!empty($sefUrl)){

			if($sefUrl->code == 200) {

				$originalUrl = ['plugin' => $sefUrl->plugin, 'controller' => $sefUrl->controller, 'action' => $sefUrl->action];
				if(!empty($sefUrl->param)) {
					$splittedParams = explode('/', $sefUrl->param);
					foreach($splittedParams as $splittedParam) {
						$originalUrl[] = $splittedParam;
					}
				}

				// DOVREBBE servire per convertire in un formato corretto per il browser le lingue particolari, tipo il russo
				$rewrittenUrl = str_replace('%2F', '/', urlencode($sefUrl->rewritten));


				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					$routes->connect('/' . ACTIVE_LANGUAGE . '/' . $rewrittenUrl, $originalUrl);
				} else {
					$routes->connect('/' . $rewrittenUrl, $originalUrl);
				}

			} else if($sefUrl->code == 301) {

				$rewrittenUrl = $sefModel->find()->where(['original' => $sefUrl->original, 'locale' => ACTIVE_LANGUAGE, 'code' => 200])->first()->get('rewritten');

				if(!empty($qs)) $rewrittenUrl .= '?' . $qs;
				if(!empty($hash)) $rewrittenUrl .= '#' . $hash;
				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					header ('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . Configure::read('Setup.domain') . '/' . ACTIVE_LANGUAGE . '/' . $rewrittenUrl);
					exit();
				} else {
					header ('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . Configure::read('Setup.domain') . '/' . $rewrittenUrl);
					exit();
				}
			}

		} else {

			$nonSefUrl = $sefModel->find()->where(['original' => $request, 'locale' => ACTIVE_LANGUAGE, 'code' => 200])->first();

			if(!empty($nonSefUrl)){

				$rewrittenUrl = $nonSefUrl->rewritten;
				if(!empty($qs)) $rewrittenUrl .= '?' . $qs;
				if(!empty($hash)) $rewrittenUrl .= '#' . $hash;
				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					header ('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . Configure::read('Setup.domain') . '/' . ACTIVE_LANGUAGE . '/' . $rewrittenUrl);
					exit();
				} else {
					header ('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . Configure::read('Setup.domain') . '/' . $rewrittenUrl);
					exit();
				}
			}

		}

	}




    $routes->scope('/', function (RouteBuilder $builder) {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			$builder->connect('/' . ACTIVE_LANGUAGE . '/', Configure::read('Setup.home'));
		} else {
			$builder->connect('/', Configure::read('Setup.home'));
		}

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			$builder->connect('/' . ACTIVE_LANGUAGE . '/pages/*', 'Pages::display');
		} else {
			$builder->connect('/pages/*', 'Pages::display');
		}

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */

		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			$builder->connect('/' . ACTIVE_LANGUAGE . '/{controller}', ['action' => 'index']);
			$builder->connect('/' . ACTIVE_LANGUAGE . '/{controller}/{action}/*', []);
		} else {
			$builder->fallbacks();
		}
    });



    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
	 $routes->setExtensions(['json']);
     $routes->prefix('Admin', function (RouteBuilder $builder) {

        $builder->connect('/', ['prefix' => 'Admin', 'plugin' => 'CustomPages', 'controller' => Configure::read('Setup.adminHomeController'), 'action' => 'index']);

        $builder->fallbacks(DashedRoute::class);
     });

};
