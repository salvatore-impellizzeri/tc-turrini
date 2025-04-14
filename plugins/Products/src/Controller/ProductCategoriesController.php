<?php
declare(strict_types=1);

namespace Products\Controller;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\NotFoundException;

class ProductCategoriesController extends AppController
{

	// view solo per robe nostre, per vini non ci dovrebbe passare
	public function view($id = null)
    {


        if(empty($id)) throw new NotFoundException(__('Not found'));

		$item = $this->ProductCategories->find()
			->where([
				'ProductCategories.published' => true,
				'ProductCategories.id' => $id
			])
			->first();

		if (empty($item)) throw new NotFoundException(__('Not found'));


		$subcategories = $this->ProductCategories->find('list')
			->where([
				'ProductCategories.lft >' => $item->lft,
				'ProductCategories.rght <' => $item->rght,
				'ProductCategories.published' => true
			])
			->toArray();

		$subcategories = array_merge([$item->id], array_keys($subcategories));


		$products = $this->ProductCategories->Products->find()
			->where(['product_category_id' => $subcategories], ['product_category_id' => 'integer[]'])
			->contain(['Preview'])
			->all();

        $this->set(compact('item', 'products'));
		$this->setSeoFields($item);
    }

}
