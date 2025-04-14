<?php
declare(strict_types=1);

namespace Events\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;


class EventsController extends AppController
{


	public function edit($id = null)
	{

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];
		parent::edit($id);

	}

	public function get()
	{
		$this->queryOrder = ['Events.date' => 'ASC'];
		parent::get();
	}

}
