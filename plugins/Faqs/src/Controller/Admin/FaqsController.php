<?php
declare(strict_types=1);

namespace Faqs\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Query;

/**
 * Faqs Controller
 *
 * @property \Faqs\Model\Table\FaqsTable $Faqs
 * @method \Faqs\Model\Entity\Faq[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FaqsController extends AppController
{

    public function get()
    {
		$this->queryOrder = ['Faqs.position' => 'ASC'];
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
