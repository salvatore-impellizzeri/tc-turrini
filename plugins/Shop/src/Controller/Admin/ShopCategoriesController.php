<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;

class ShopCategoriesController extends AppController
{


    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $maxDepth = $this->ShopCategories->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10;
            $parents = $this->ShopCategories->find('threaded')
                ->order(['ShopCategories.lft' => 'ASC'])
                ->where(['ShopCategories.level <' => $maxDepth -1]);

            $parents = $this->ShopCategories->formatTreeList($parents, spacer: '- ')->toArray();
            $this->set(compact('parents'));
        }
    }

    public function index()
    {

        $items = $this->ShopCategories->find('threaded')
            ->order(['ShopCategories.lft' => 'ASC'])
            ->find('translations')
            ->toArray();

        $this->set('maxDepth', $this->ShopCategories->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10);
        $this->set(compact('items'));
    }


    public function viewsortable($id) {

       /* 
       $item = $this->ShopCategories
                        ->findById($id)
                        ->contain('Products', function (Query $q) {
                            return $q->order(['ProductsShopCategories.position' => 'ASC']);
                        })
                        ->first();
        */

        $item = $this->ShopCategories
                        ->findById($id)
                        ->first();

        if(!empty($item)){
            $this->set('item', $item);
        } else {
            $this->Flash->error(__d('admin', 'Not found'));
            return $this->redirect($this->actionRedirect);
        }

    }

}
