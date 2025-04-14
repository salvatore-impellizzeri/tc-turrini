<?php
declare(strict_types=1);

namespace Faqs\Controller;

use App\Controller\AppController;


class FaqsController extends AppController
{



	public function index()
	{
		$items = $this->Faqs->find()
			->where(['Faqs.published' => 1])
			->order(['Faqs.position' => 'ASC'])
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
