<?php
declare(strict_types=1);

namespace Menu\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;


class MenuItemsController extends AppController
{

    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $maxDepth = $this->MenuItems->behaviors()->get('Tree')->getConfig('maxDepth') ?? 10;
            $parents = $this->MenuItems->find('threaded')
                ->order(['MenuItems.lft' => 'ASC'])
                ->where(['MenuItems.level <' => $maxDepth - 1]);

                if($this->request->getParam('action') == 'add'){
                    $parents->where(['MenuItems.menu_id' => $this->request->getQuery('menu_id')]);
                }else if ($this->request->getParam('action') == 'edit'){
                    $item = $this->viewBuilder()->getVar('item');
                    $parents->where(['MenuItems.menu_id' => $item->menu_id]);
                }

            $parents = $this->MenuItems->formatTreeList($parents, spacer: "- ")->toArray();

            $this->set(compact('parents'));

            $this->set('urls', $this->buildLinks());
        }
    }

    public function add()
    {
        $menuId = $this->request->getQuery('menu_id');
        if (empty($menuId)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $menu = $this->MenuItems->Menus->findById($menuId)->firstOrFail();

        if (empty($menu)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('menu_id')];
        }

        $this->set(compact('menu'));
		parent::add();
    }

    public function edit($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('menu_id')];
        }

        $this->queryContain = ['Menus'];

        parent::edit($id);
    }

    public function list($id = null)
    {
        $menu = $this->MenuItems->Menus->findById($id)->firstOrFail();

        $items = $this->MenuItems->find('threaded')->find('translations')
            ->where(['MenuItems.menu_id' => $id])
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
            $this->actionRedirect = ['action' => 'list', $item->menu_id];
        }

        parent::delete($id);
    }

    // costruisce i link per la tendina
    protected function buildLinks(){
        $links = [];

        //pagine
        $customPagesLinks = $this->buildCustomPagesLinks();
        if (!empty($customPagesLinks)) $links[__d('admin', 'menu custom pages')] = $customPagesLinks;

        //pagine componibili
        $blockPagesLinks = $this->buildBlockPagesLinks();
        if (!empty($blockPagesLinks)) $links[__d('admin', 'menu block pages')] = $blockPagesLinks;

        //funzionalità
        $pluginLinks = $this->buildPluginLinks();
        $pluginLinks = array_merge(['/' => 'Homepage'], $pluginLinks);
        if (!empty($pluginLinks)) $links[__d('admin', 'menu functions')] = $pluginLinks;

        //categories
        $customPagesLinks = $this->buildProductCategoriesLinks();
        if (!empty($customPagesLinks)) $links[__d('admin', 'menu product categories')] = $customPagesLinks;

        return $links;
    }

    protected function buildPluginLinks(){
        $links = [];

        //recupero i link
        $plugins = Plugin::loaded();
        
        if (!empty($plugins)) {
            foreach ($plugins as $pluginName) {


                $configPath = Plugin::configPath($pluginName);

                

                // controllo che esista la cartella config
                if (!file_exists($configPath)) continue;

                $configFileDir = scandir($configPath);

				if (!empty($configFileDir)) {
                    $configFile = in_array('setup.php', $configFileDir);
				}

                if(!empty($configFile)){
                    //carico il file di configurazione del plugin
                    Configure::load("$pluginName.setup", 'default');
                    $menulinks = Configure::read("$pluginName.menulinks");
                    if (!empty($menulinks)){
                        foreach ($menulinks as $value) {
                            $links[$value['url']] = $value['title'];
                        }
                    }
                }

            }
        }
        return $links;
    }

    protected function buildCustomPagesLinks(){
        $links = [];

        $dbTable =  $this->getTableLocator()->get('CustomPages.CustomPages');
        $customPages = $dbTable->find('list')->toArray();

        if (!empty($customPages)) {
            foreach ($customPages as $id => $title) {
                $links['/custom-pages/view/'.$id] = $title;
            }
        }

        return $links;
    }


    protected function buildBlockPagesLinks(){
        $links = [];

        $dbTable =  $this->getTableLocator()->get('CustomPages.BlockPages');
        $customPages = $dbTable->find('list')->toArray();

        if (!empty($customPages)) {
            foreach ($customPages as $id => $title) {
                $links['/block-pages/view/'.$id] = $title;
            }
        }

        return $links;
    }


    protected function buildProductCategoriesLinks(){
        $links = [];

        $dbTable =  $this->getTableLocator()->get('Products.ProductCategories');
        $productCategories = $dbTable->find('list')
            ->order(['ProductCategories.lft' => 'ASC'])
            ->toArray();

        if (!empty($productCategories)) {
            foreach ($productCategories as $id => $title) {
                $links['/product-categories/view/'.$id] = $title;
            }
        }

        return $links;
    }




}
