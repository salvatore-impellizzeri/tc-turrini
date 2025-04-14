<?php
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;

if (!empty($id)) {
    $menuItems = $this->getTableLocator()->get('BackendMenu.MenuItems');
    $query = $menuItems->find('threaded')
        ->where(['MenuItems.backend_menu_id' => $id])
        ->order(['MenuItems.lft' => 'ASC'])
        ->all();

    debug($query->all());

}
?>
