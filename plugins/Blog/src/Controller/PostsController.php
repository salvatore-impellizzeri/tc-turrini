<?php
declare(strict_types=1);

namespace Blog\Controller;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\Http\Exception\NotFoundException;


class PostsController extends AppController
{

	public function index(){

		$items = $this->Posts->find()
			->where(['Posts.published' => 1])
			->contain([
				'Preview',
				'Images',
				'Attachments'
            ]);

		$this->set('items', $this->paginate($items, [
			'limit' => 12,
			'order' => ['Post.date' => 'DESC']
		]));

		$this->set('pageTitle', __d('blog', 'seo title'));
		$this->set('seoDescription', __d('blog', 'seo description'));
	}

	public function view($id = null)
    {

        if(!isset($id)) throw new NotFoundException(__('Not found'));

		$conditions = [
			'Posts.id' => $id,
			'Posts.published' => true
		];

		//tolgo il controllo su published per la preview
		if (!empty($this->request->getQuery('forcePreview'))) {
			$conditions = [
				'Posts.id' => $id
			];
		}



		$item = $this->Posts->find()
			->contain([
				'Images' => 'ResponsiveImages',
				'Tags' => [
	                'queryBuilder' => function (Query $q) {
	                    return $q->order(['position' => 'ASC']);
	                }
	            ],
				'ContentBlocks' => [
					'sort' => ['ContentBlocks.position' => 'ASC'],
					'Images' => 'ResponsiveImages',
					'ContentBlockTypes'
				]
	 		])
			->where($conditions)
			->first();

        if(empty($item)) throw new NotFoundException(__('Not found'));


        $this->set(compact('item'));
		$this->setSeoFields($item);
		$this->setImages($item);
		$this->setAttachments($item);
    }

}
