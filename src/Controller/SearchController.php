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
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\I18n\I18n;
use Cake\Datasource\FactoryLocator;


class SearchController extends AppController
{

	public $tableless = true;

    public function find(){
        $this->request->allowMethod(['ajax']);
        $key = $this->request->getData('key', '');
        $key = trim($key);

        $results = [];
		
		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			I18n::setLocale(ACTIVE_LANGUAGE);
		}

        
        
		if (strlen($key) > 1) {

            
			$productsTable = FactoryLocator::get('Table')->get('Products.Products');			
			
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {	
				
				$conditions = [
					'Products.published' => true,
					"MATCH(ProductsTranslation.title, ProductCategoriesTranslation.title) AGAINST(:key IN BOOLEAN MODE)"
				];
				$rating = "MATCH(ProductsTranslation.title, ProductCategoriesTranslation.title) AGAINST(:key IN BOOLEAN MODE)";
			} else {
				
				$conditions = [
					'Products.published' => true,
					"MATCH(Products.title, ProductCategories.title) AGAINST(:key IN BOOLEAN MODE)"
				];
				$rating = "MATCH(Products.title, ProductCategories.title) AGAINST(:key IN BOOLEAN MODE)";
			}

            $products = $productsTable->find()
                ->contain([
                    'Preview',
                    'ProductCategories'
                ])
                ->where($conditions)
                ->selectAlso([
                    'rating' => $rating,
                ])
                ->bind(':key', $key.'*', 'string')
                
                ->order(['rating' => 'DESC', 'Products.created' => 'DESC'])
                ->all();				
            $this->set(compact('products'));

        }
        
        $this->viewBuilder()->setClassName('Ajax');	

    }


}
