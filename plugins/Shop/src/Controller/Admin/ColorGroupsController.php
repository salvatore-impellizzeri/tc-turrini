<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class ColorGroupsController extends AppController
{


    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $attributes = $this->ColorGroups->Attributes->find('list')
                ->innerJoinWith('AttributeGroups', function($q){
                    return $q->where(['AttributeGroups.attribute_type_id' => 2]);
                })
                ->all();

            // elenco di attributi colore ancora da collegare
            $notConnectedAttributes = $this->ColorGroups->Attributes->find('list')
                ->innerJoinWith('AttributeGroups', function($q){
                    return $q->where(['AttributeGroups.attribute_type_id' => 2]);
                })
                ->notMatching('ColorGroups')
                ->all();

            $this->set(compact('attributes', 'notConnectedAttributes'));
        }
    }

	public function get()
    {
		$this->queryOrder = ['ColorGroups.position' => 'ASC'];
		parent::get();
	}

    public function edit($id = null)
	{

		$this->queryContain = [
            'Attributes' => [
                'queryBuilder' => function (Query $q) {
                    return $q->order(['AttributesColorGroups.position' => 'ASC']);
                }
            ]
		];
		parent::edit($id);

	}

    public function deleteRecord()
	{
        $this->deleteFailMessage = __dx('shop', 'admin', 'connected attributes delete error');
        parent::deleteRecord();
    }

}
