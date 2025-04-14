<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use Cake\Utility\Inflector;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $prefix = $this->request->getParam('prefix');

        if (!empty($prefix) && $prefix == 'Admin') {

            //carico gli helper
            $this->addHelper('Table');
            $this->addHelper('Backend');
            $this->addHelper('Frontend'); // ci serve per il formatPrice
            $this->addHelper('AssetCompress.AssetCompress');

        } else {
            //carico gli helper
            $this->addHelper('Frontend');
            $this->addHelper('Paginator');
            $this->addHelper('AssetCompress.AssetCompress');
        }


    }
}
