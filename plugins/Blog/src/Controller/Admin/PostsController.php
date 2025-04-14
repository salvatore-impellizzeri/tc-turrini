<?php
declare(strict_types=1);

namespace Blog\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Routing\Router;
use Cake\ORM\Query;


class PostsController extends AppController
{

    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $tags = $this->Posts->Tags->find('list')->all();
            $this->set(compact('tags'));
        }
    }
    
    public function edit($id = null)
	{

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments',
            'ContentBlocks' => [
                'ContentBlockTypes'
            ],
            'Tags' => [
                'queryBuilder' => function (Query $q) {
                    return $q->order(['position' => 'ASC']);
                }
            ]
		];
		parent::edit($id);

	}

}
