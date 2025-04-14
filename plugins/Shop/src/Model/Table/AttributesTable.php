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

class AttributesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('attributes');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title','modified', '_status']]);
        $this->addBehavior('Sortable', [
            'scope' => 'attribute_group_id'
        ]);

        $this->belongsToMany('ShopProducts', [
            'className' => 'Shop.ShopProducts',
            'joinTable' => 'attributes_shop_products',
            'through' => 'Shop.AttributesShopProducts',
        ]);

        $this->belongsToMany('ShopProductVariants', [
            'className' => 'Shop.ShopProductVariants',
            'joinTable' => 'attributes_shop_variants',
            'through' => 'Shop.AttributesShopVariants',
        ]);

        $this->belongsTo('AttributeGroups', [
            'className' => 'Shop.AttributeGroups'
        ]);

        $this->belongsToMany('ColorGroups', [
            'className' => 'Shop.ColorGroups',
            'joinTable' => 'attributes_color_groups',
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
            ->integer('attribute_group_id')
            ->requirePresence('attribute_group_id', 'create')
            ->notEmptyString('attribute_group_id');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('attribute_group_id', 'AttributeGroups'), ['errorField' => 'attribute_group_id']);

        $rules->add($rules->isUnique(
            ['title', 'attribute_group_id'],
            __dx('shop', 'admin', 'duplicated attribute title')
        ));

        return $rules;
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!empty($entity->product_count) || !empty($entity->product_variant_count)) {
            // non faccio eliminare un attributo collegato a prodotti o varianti
            $event->stopPropagation();
        }
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {   
        // se modifico il titolo di un attributo vado ad aggiornare il titolo delle varianti collegate
        if (!$entity->isNew() && $entity->isDirty('title')) {
            $attributeId = $entity->id;
            $productVariants = $this->ShopProductVariants->find()
                ->innerJoinWith('Attributes', function ($q) use ($attributeId) {
                    return $q->where(['Attributes.id' => $attributeId]);
                })
                ->select('id')
                ->all();

            if (!empty($productVariants)) {
                foreach ($productVariants as $productVariant) {
                    $this->ShopProductVariants->updateTitle($productVariant->id);
                }
            }
        }

    }


	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Attributes.title LIKE' => "%" . trim($key) . "%"]);
    }


}
