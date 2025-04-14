<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class AttributesController extends AppController
{

    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $attributeGroups = $this->Attributes->AttributeGroups->find('list');
			$this->set(compact('attributeGroups'));
        }
        
    }

    public function add()
    {
        $attributeGroupId = $this->request->getQuery('attribute_group_id');
        if (empty($attributeGroupId)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $attributeGroup = $this->Attributes->AttributeGroups->findById($attributeGroupId)->firstOrFail();

        if (empty($attributeGroup)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('attribute_group_id')];
        }

        $this->set(compact('attributeGroup'));
		parent::add();
    }

    public function edit($id = null)
    {

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('attribute_group_id')];
        }

        $this->queryContain = [
			'AttributeGroups'
		];

        parent::edit($id);
    }

	public function get()
    {
		
        $this->queryContain = ['AttributeGroups'];

        // se passo il parametro productId mi recupera solo quelli collegati al prodotto
        if (!empty($this->request->getQuery('productId'))){
            $productId = $this->request->getQuery('productId');
            $this->queryInnerJoins = [
                'ShopProducts' => [
                    'ShopProducts.id' => $productId
                ]
            ];
            $this->queryContain = [];
        }

		parent::get();
	}

    public function deleteRecord()
	{
        $this->deleteFailMessage = __dx('shop', 'admin', 'connected products delete error');
        parent::deleteRecord();
    }


    public function list($id = null)
    {
        $item = $this->Attributes->AttributeGroups->findById($id)->firstOrFail();

        $this->set(compact('item'));
    }

    // restituisce solo gli attributi di tipo colore
    public function colorCheckbox()
	{
		$default = [
			'page' => 0,
			'rowsPerPage' => 50,
			'search' => false,
			'checked' => []
		];  

		$query = $this->request->getData();
		$options = array_merge($default, $query);
		extract($options);

		if (empty($search)) {
			$records = $this->dbTable->find();
		} else {
			$records = $this->dbTable->find('filtered', options: ['key' => $search]);
		}

        $records->innerJoinWith('AttributeGroups', function($q){
            return $q->where(['AttributeGroups.attribute_type_id' => 2]);
        });

		//restituisce le righe
		$records->contain($this->queryContain);
		$this->set('total', $records->count());


        $records->limit((int)$rowsPerPage);
        $records->offset($page * (int)$rowsPerPage);
        $records = $records->all()->toArray();



		$this->set(compact('checked', 'records'));
		$this->viewBuilder()->setLayout('ajax');
		$this->viewBuilder()->setTemplate('/Admin/Common/checkbox');
	}

}
