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
use Cake\Core\Configure;

class ShopProductsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('shop_products');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'description', 'modified', '_status']]);
		$this->addBehavior('Sortable');


        $this->hasMany('ShopProductVariants', [
            'className' => 'Shop.ShopProductVariants',
            'cascadeCallbacks' => true,
        ])->setDependent(true);

        $this->belongsTo('Brands', [
            'className' => 'Shop.Brands'
        ]);

        $this->belongsTo('VatRates', [
            'className' => 'Shop.VatRates'
        ]);

		$this->belongsToMany('ShopCategories', [
            'className' => 'Shop.ShopCategories',
            'joinTable' => 'shop_categories_products'
        ]);

        // attributi prodotto
        $this->belongsToMany('AttributeGroups', [
            'className' => 'Shop.AttributeGroups',
            'joinTable' => 'attribute_groups_shop_products',
            'through' => 'Shop.AttributeGroupsShopProducts',
            'cascadeCallbacks' => true
        ]);

        $this->belongsToMany('Attributes', [
            'className' => 'Shop.Attributes',
            'joinTable' => 'attributes_shop_products',
            'through' => 'Shop.AttributesShopProducts',
            'cascadeCallbacks' => true
        ]);

        // gruppi attributi varianti
        $this->belongsToMany('VariantAttributeGroups', [
            'className' => 'Shop.AttributeGroups',
            'joinTable' => 'attribute_groups_shop_variants',
            'foreignKey' => 'shop_product_id',
            'targetForeignKey' => 'attribute_group_id',
            'through' => 'Shop.AttributeGroupsShopVariants',
            'cascadeCallbacks' => true
        ]);

        $this->addBehavior('CounterCache', [
            'Brands' => ['product_count'],
            'VatRates' => ['product_count'],
        ]);


        $this->hasMany('Gallery', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['Gallery.model' => $this->getAlias(), 'Gallery.scope' => 'gallery']);

		$this->hasOne('Preview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['Preview.model' => $this->getAlias(), 'Preview.scope' => 'preview']);

		$this->hasOne('SocialPreview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['SocialPreview.model' => $this->getAlias(), 'SocialPreview.scope' => 'social_preview']);



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
            ->integer('brand_id')
            ->requirePresence('brand_id', 'create')
            ->notEmptyString('brand_id');

        $validator
            ->integer('vat_rate_id')
            ->requirePresence('vat_rate_id', 'create')
            ->notEmptyString('vat_rate_id');

        $validator
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('vat_rate_id', 'VatRates'), ['errorField' => 'vat_rate_id']);
        return $rules;
    }


    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        
        // TODO -> gestire passaggio da prodotto senza varianti a prodotto con varianti


        //controllo se ci sono modelli da "creare"
        $newFields = [
            'brand_id' => 'Brands'
        ];

        foreach ($newFields as $newField => $model) {
            
            if (!empty($entity->{$newField}) && $entity->{$newField} == -1) {
                if (empty($entity->{'_'.$newField})) {
                    $entity->setError($newField, __d('admin', 'This field cannot be left blank'));
                    $event->stopPropagation();
                    return false;
                } else {
                    // verifico se esiste già per ovviare al problema del doppio salvataggio
                    $exists = $this->{$model}->findByTitle($entity->{'_'.$newField})->first();

                    if ($exists) {
                        //collego al brand esistente
                        $entity->{$newField} = $exists->id;
                    } else {
                        //provo a creare l'entity
                        $newEntity = $this->{$model}->newEntity([
                            'title' => $entity->{'_'.$newField}
                        ]);
                        if (!$this->{$model}->save($newEntity)) {
                            $entity->setError($newField, __d('admin', 'This field cannot be left blank'));
                            $event->stopPropagation();
                            return false;
                        }

                        $entity->{$newField} = $newEntity->id;
                    }

                    
                }
            }
        }
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // ricalcolo il prezzo delle varianti se ho modificato l'aliquota
        if (!$entity->isNew() && $entity->isDirty('vat_rate_id') && !empty($entity->vat_rate_id)) {
            $productVariants = $this->ShopProductVariants->findByShopProductId($entity->id)->all();

            if (!empty($productVariants)) {
                $vatRate = $this->VatRates->get($entity->vat_rate_id);
                if (!empty($vatRate)) {
                    $vatRate = $vatRate->value;

                    foreach ($productVariants as $productVariant) {
                    
                        $priceFields = ['price', 'discounted_price'];
                        $vatIncluded = Configure::read('Shop.vatIncuded');
    
                        foreach ($priceFields as $field){
                            $startingField = $vatIncluded ? $field : $field.'_net';
                            $destinationField = $vatIncluded ? $field.'_net' : $field;
    
                            if (!empty($productVariant->{$startingField})) {
                                $decimalVat = $vatRate / 100;
                                $price = $productVariant->{$startingField};
                                $finalPrice = $vatIncluded ? $price / (1 + $decimalVat) : $price * (1 + $decimalVat);
                                $productVariant->{$destinationField} = $finalPrice;
                            } else {
                                $productVariant->{$destinationField} = null;
                            }
                        }
   
                        $this->ShopProductVariants->getEventManager()->off('Model.beforeMarshal');
                        $this->ShopProductVariants->getEventManager()->off('Model.afterMarshal');
                        $this->ShopProductVariants->getEventManager()->off('Model.beforeSave');
                        $this->ShopProductVariants->getEventManager()->off('Model.afterSave');

    
                        $this->ShopProductVariants->save($productVariant, ['checkRules' => false]);
                    }
    
                }
                
            }
        }


        // se il titolo prodotto è modificato aggiorno il titolo delle varianti 
        if (!$entity->isNew() && $entity->isDirty('title') && $entity->has_variants) {
            $productVariants = $this->ShopProductVariants->findByShopProductId($entity->id)
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
        return $query->where(['ShopProducts.title LIKE' => "%" . trim($key) . "%"]);
    }


}
