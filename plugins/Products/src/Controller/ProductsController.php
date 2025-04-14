<?php
declare(strict_types=1);

namespace Products\Controller;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\Http\Exception\NotFoundException;
use Cake\Collection\Collection;

class ProductsController extends AppController
{

	public function index()
	{
		$items = $this->Products->find()
			->where([
				'Products.published' => true
			])
			->contain([
				'Preview'
			])
			->all();

		$this->set(compact('items'));
	}

	public function view($id = null, $variantId = null)
    {

        if(!isset($id)) throw new NotFoundException(__('Not found'));

		//condizioni di default
		$conditions = [
			'Products.id' => $id,
			'Products.published' => true
		];

		//tolgo il controllo su published per la preview
		if (!empty($this->request->getQuery('forcePreview'))) {
			$conditions = [
				'Products.id' => $id
			];
		}


		$item = $this->Products->find()
			->contain([
				'Images' => 'ResponsiveImages',
	 		])
			->where($conditions)
			->first();

        if(empty($item)) throw new NotFoundException(__('Not found'));


        $this->set(compact('item'));
		$this->setSeoFields($item);
		$this->setImages($item);
		$this->setAttachments($item);
    }



}
