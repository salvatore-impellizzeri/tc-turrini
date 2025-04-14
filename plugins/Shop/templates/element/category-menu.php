<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

$categories = FactoryLocator::get('Table')->get('Products.ProductCategories')->find('threaded')
    ->where(['ProductCategories.published' => true])
    ->select(['id', 'title', 'lft', 'rght', 'parent_id'])
    ->order(['ProductCategories.lft' => 'ASC'])
    ->all();

echo $this->Frontend->recursiveMenu($categories, [
    'controller' => 'product-categories',
    'class' => 'category-menu',
    'current' => $currentCategory ?? null,
    'showAllLink' => '/products/index',
    'showAllLabel' => __d($po, 'all products')
]);
?>
