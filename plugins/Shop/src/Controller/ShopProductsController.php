<?php
declare(strict_types=1);

namespace Shop\Controller;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\Http\Exception\NotFoundException;
use Cake\Collection\Collection;
use Cake\Core\Configure;

class ShopProductsController extends AppController
{

	public function index()
	{
        $productsPerPage = Configure::read('Shop.productsPerPage');
        $productsCount = $this->ShopProducts->ShopProductVariants->find('defaultVariant')
            ->count();

        $this->set(compact('productsCount', 'productsPerPage'));
	}

    public function get() 
    {
        $page = $this->request->getQuery('page', 1);
        $limit = Configure::read('Shop.productsPerPage');

        $products = $this->ShopProducts->ShopProductVariants->find('defaultVariant')
            ->limit($limit)
            ->page((int)$page)
            
            ->order([
                'ShopProducts.position' => 'ASC'
            ])
            ->all();

        $this->viewBuilder()->setClassName('Ajax');
        $this->set(compact('products'));
    }





}
