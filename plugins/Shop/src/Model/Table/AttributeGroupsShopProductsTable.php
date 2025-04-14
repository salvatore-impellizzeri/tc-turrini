<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;


class AttributeGroupsShopProductsTable extends AppTable
{

    public function initialize(array $config): void
    {

		// configurazione tabella
        $this->setTable('attribute_groups_shop_products');


        $this->belongsTo('AttributeGroups', [
            'className' => 'Shop.AttributeGroups'
        ]);

        $this->belongsTo('ShopProducts', [
            'className' => 'Shop.ShopProducts'
        ]);

        $this->addBehavior('CounterCache', [
            'ShopProducts' => ['attribute_count']
        ]);

    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        $attributes = $this->ShopProducts->Attributes->find()
            ->where(['Attributes.attribute_group_id' => $entity->attribute_group_id])
            ->matching('ShopProducts', function ($q) use ($entity){
                return $q->where(['ShopProducts.id' => $entity->shop_product_id]);
            })
            ->all();

        if ($attributes->count()) {
            $ids = $attributes->extract('id')->toArray();

            // cancello le relazioni tra prodotto e attributi di questo gruppo
            $joinTable = TableRegistry::getTableLocator()->get('Shop.AttributesShopProducts');
            $joinTable->deleteAll(['attribute_id IN' => $ids]);
        }    

        
        

    }



}
