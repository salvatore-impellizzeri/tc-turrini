<?php
namespace Menu\View\Cell;

use Cake\View\Cell;

class MenuCell extends Cell
{
    public function display($menuId, $options = [])
    {
        $conditions = [
            'MenuItems.menu_id' => $menuId,
            'MenuItems.published' => 1
        ];

        $menuItems = $this->getTableLocator()->get('Menu.MenuItems');
        $query = $menuItems->find('threaded')
            ->where($conditions)
            ->order(['MenuItems.lft' => 'ASC']);

        $this->set('menuItems', $query->toArray());
        $this->set(compact('options'));
    }

}
?>
