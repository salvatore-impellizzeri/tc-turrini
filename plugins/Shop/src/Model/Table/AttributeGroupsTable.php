<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use Cake\Utility\Hash;

class AttributeGroupsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('attribute_groups');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'modified', '_status']]);
		$this->addBehavior('Sortable');


        $this->belongsTo('AttributeTypes', [
            'className' => 'Shop.AttributeTypes'
        ]);

        $this->hasMany('Attributes', [
            'className' => 'Shop.Attributes'
            ])
            ->setCascadeCallbacks(true)
            ->setDependent(true);

        $this->addBehavior('CounterCache', [
            'AttributeTypes' => ['attribute_group_count'],
        ]);

        $this->belongsToMany('ShopProducts', [
            'className' => 'Shop.ShopProducts',
            'joinTable' => 'attribute_groups_shop_products',
            'through' => 'Shop.AttributeGroupsShopProducts',
        ]);

        $this->belongsToMany('VariantShopProducts', [
            'className' => 'Shop.ShopProducts',
            'joinTable' => 'attribute_groups_shop_variants',
            'foreignKey' => 'attribute_group_id',
            'targetForeignKey' => 'shop_product_id',
            'through' => 'Shop.AttributeGroupsShopVariants',
        ]);


    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('attribute_type_id')
            ->maxLength('attribute_type_id', 255)
            ->requirePresence('attribute_type_id', 'create')
            ->notEmptyString('attribute_type_id');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('attribute_type_id', 'AttributeTypes'), ['errorField' => 'attribute_type_id']);
        return $rules;
    }

    // public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    // {
    //     if (!empty($entity->product_variant_count)) {
    //         // non faccio eliminare una colore se ha varianti collegate
    //         $event->stopPropagation();
    //     }
    // }


	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['AttributeGroups.title LIKE' => "%" . trim($key) . "%"]);
    }


}
