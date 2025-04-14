<?php
declare(strict_types=1);

namespace Snippets\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;

/**
 * Snippets Controller
 *
 * @property \Snippets\Model\Table\SnippetsTable $Snippets
 * @method \Snippets\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SnippetsController extends AppController
{


	public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);

		if ($this->request->getParam('action') == 'edit') {
			$item = $this->ViewBuilder()->getVar('item');

			if (!empty($item->layout)){
				$this->ViewBuilder()->setTemplate($item->layout);
			}

		}

    }

	public function get()
    {
		$this->queryOrder = ['Snippets.title' => 'ASC'];
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
