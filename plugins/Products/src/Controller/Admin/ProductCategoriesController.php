<?php
declare(strict_types=1);

namespace Products\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;

class ProductCategoriesController extends AppController
{


    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $maxDepth = $this->ProductCategories->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10;
            $parents = $this->ProductCategories->find('threaded')
                ->order(['ProductCategories.lft' => 'ASC'])
                ->where(['ProductCategories.level <' => $maxDepth -1]);

            $parents = $this->ProductCategories->formatTreeList($parents, spacer: '- ')->toArray();
            $this->set(compact('parents'));
        }
    }

    public function index()
    {

        $items = $this->ProductCategories->find('threaded')
            ->order(['ProductCategories.lft' => 'ASC'])
            ->find('translations')
            ->toArray();

        $this->set('maxDepth', $this->ProductCategories->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10);
        $this->set(compact('items'));
    }


    public function viewsortable($id) {

       /* 
       $item = $this->ProductCategories
                        ->findById($id)
                        ->contain('Products', function (Query $q) {
                            return $q->order(['ProductsProductCategories.position' => 'ASC']);
                        })
                        ->first();
        */

        $item = $this->ProductCategories
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
