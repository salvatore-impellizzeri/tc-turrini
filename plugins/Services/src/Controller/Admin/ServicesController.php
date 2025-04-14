<?php
declare(strict_types=1);

namespace Services\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Query;

/**
 * Services Controller
 *
 * @property \Services\Model\Table\ServicesTable $Services
 * @method \Services\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
{

    public function get()
    {
		$this->queryOrder = ['Services.position' => 'ASC'];
		parent::get();
	}

	public function edit($id = null)
	{
		
		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];
		parent::edit($id);			

	}

}
