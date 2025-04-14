<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class AttributeGroupsController extends AppController
{

    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {

            $attributeTypes = $this->AttributeGroups->AttributeTypes->find('list');
			$this->set(compact('attributeTypes'));

        }
        
    }

	public function get()
    {
		$this->queryOrder = ['AttributeGroups.position' => 'ASC'];
        $this->queryContain = ['AttributeTypes'];


        // se passo il parametro productId mi recupera solo quelli collegati al prodotto
        if (!empty($this->request->getQuery('productId'))){

            $productId = $this->request->getQuery('productId');
            $matchingModel = empty($this->request->getQuery('variant')) ? 'ShopProducts' : 'VariantShopProducts';
            $orderModel = empty($this->request->getQuery('variant')) ? 'AttributeGroupsShopProducts' : 'AttributeGroupsShopVariants';
            $this->queryMatchings = [
                $matchingModel => [
                    "{$matchingModel}.id" => $productId
                ]
            ];
            $this->queryContain = [];
            $this->queryOrder = ["$orderModel.position" => 'ASC'];
        }

		parent::get();
	}

    public function deleteRecord()
	{
        $this->deleteFailMessage = __dx('shop', 'admin', 'connected attributes delete error');
        parent::deleteRecord();
    }

}
