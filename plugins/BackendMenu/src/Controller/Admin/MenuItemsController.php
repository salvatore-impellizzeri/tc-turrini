<?php
declare(strict_types=1);

namespace BackendMenu\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\Http\Exception\MethodNotAllowedException;

/**
 * Categories Controller
 *
 * @property \Catalog\Model\Table\CategoriesTable $Categories
 * @method \Catalog\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MenuItemsController extends AppController
{

    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $maxDepth = $this->MenuItems->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10;
            $parents = $this->MenuItems->find('threaded')
                ->order(['MenuItems.lft' => 'ASC'])
                ->where(['MenuItems.level <' => $maxDepth -1]);

            $parents = $this->MenuItems->formatTreeList($parents, spacer: '- ')->toArray();
            $this->set(compact('parents'));
        }
    }

    public function add()
    {
        $menuId = $this->request->getQuery('backend_menu_id');
        if (empty($menuId)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $menu = $this->MenuItems->Menus->findById($menuId)->firstOrFail();

        if (empty($menu)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('backend_menu_id')];
        }

        $this->set(compact('menu'));
		parent::add();
    }

    public function edit($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('backend_menu_id')];
        }

        $this->queryContain = ['Menus'];

        parent::edit($id);
    }

    public function list($id = null)
    {
        $menu = $this->MenuItems->Menus->findById($id)->firstOrFail();

        $items = $this->MenuItems->find('threaded')
            ->where(['MenuItems.backend_menu_id' => $id])
            ->order(['MenuItems.lft' => 'ASC'])
            ->toArray();

        $this->set('maxDepth', $this->MenuItems->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10);
        $this->set(compact('menu', 'items'));
    }

    // sovrascrivo il delete per il redirect
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $item = $this->dbTable->findById($id)->first();

        if (!empty($item)) {
            $this->actionRedirect = ['action' => 'list', $item->backend_menu_id];
        }

        parent::delete($id);
    }




}
