<?php
declare(strict_types=1);

namespace Events\Controller;

use DateTime;
use App\Controller\AppController;


class EventsController extends AppController
{


	public function index()
	{
		$this->loadComponent('Paginator');
		$items = $this->Events->find()
			->where([
				'Events.published' => 1,
				'Events.date >' => new DateTime('today')
			])
			->order(['Events.date' => 'ASC'])
			->contain(['Preview']);

        $this->set('items', $this->Paginator->paginate($items, [
			'limit' => 10,
			'order' => ['Events.created' => 'DESC']
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
