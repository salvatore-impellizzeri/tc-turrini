<?php
declare(strict_types=1);

namespace Services\Controller;

use App\Controller\AppController;


class ServicesController extends AppController
{



	public function index()
	{
		$items = $this->Services->find()
			->where(['Services.published' => 1])
			->order(['Services.position' => 'ASC'])
			->contain(['Preview'])
			->all();

        $this->set(compact('items'));
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
