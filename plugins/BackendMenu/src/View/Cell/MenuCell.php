<?php
namespace BackendMenu\View\Cell;

use Cake\View\Cell;

class MenuCell extends Cell
{
    public function display($menuId,$userGroup)
    {
        $conditions = [
            'MenuItems.backend_menu_id' => $menuId,
            'MenuItems.published' => 1
        ];

        if ($userGroup != 1) {
            $conditions['MenuItems.superadmin_only'] = false;
        }


        $menuItems = $this->getTableLocator()->get('BackendMenu.MenuItems');
        $query = $menuItems->find('threaded')
            ->where($conditions)
            ->order(['MenuItems.lft' => 'ASC']);

        $this->set('menuItems', $query->toArray());
    }
}
?>
