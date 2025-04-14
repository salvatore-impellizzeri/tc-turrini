<?php
declare(strict_types=1);

namespace CustomPages\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Event\EventInterface;


class CustomPagesController extends AppController
{

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if ($this->request->getParam('action') == 'add') {
            $this->set('layouts', array_combine($this->CustomPages->layouts, $this->CustomPages->layouts));
            $this->viewBuilder()->setTemplate('add');
        }


        if ($this->request->getParam('action') == 'edit') {
            $item = $this->viewBuilder()->getVar('item');
            $this->viewBuilder()->setTemplate($item->layout);
        }

    }


    public function get()
    {
		$this->queryOrder = ['CustomPages.title' => 'ASC'];
        $this->queryFields = ['id', 'title', 'layout', 'created', 'published'];
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
