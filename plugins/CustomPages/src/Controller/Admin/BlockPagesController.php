<?php
declare(strict_types=1);

namespace CustomPages\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Event\EventInterface;


class BlockPagesController extends AppController
{

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if ($this->request->getParam('action') == 'add') {
            $this->viewBuilder()->setTemplate('add');
        }

    }


    public function get()
    {
		$this->queryOrder = ['BlockPages.title' => 'ASC'];
        $this->queryFields = ['id', 'title', 'created', 'published'];
		parent::get();
	}

    public function edit($id = null)
	{
		
		$this->queryContain = [
			'Images' => [
                'ResponsiveImages'
            ],
            'Attachments',
            'ContentBlocks' => [
                'ContentBlockTypes'
            ]
		];
		parent::edit($id);			

	}

}
