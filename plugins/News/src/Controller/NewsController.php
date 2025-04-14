<?php
declare(strict_types=1);

namespace News\Controller;

use App\Controller\AppController;


class NewsController extends AppController
{



	public function index()
	{

		$items = $this->News->find()
			->where(['News.published' => 1])
			->contain(['Preview']);

        $this->set('items', $this->paginate($items, [
			'limit' => 10,
			'order' => ['News.created' => 'DESC']
		]));
	}


	public function view($id = null)
	{

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];
		parent::view($id);

	}

}
