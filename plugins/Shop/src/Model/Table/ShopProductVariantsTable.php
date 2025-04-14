<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\ORM\Rule\IsUnique;
use Cake\Collection\Collection;

class ShopProductVariantsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('shop_product_variants');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'description', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);
		$this->addBehavior('SefUrl');
		$this->addBehavior('Sortable', [
            'scope' => ['shop_product_id']
        ]);


        $this->belongsTo('ShopProducts', [
            'className' => 'Shop.ShopProducts'
        ]);

        $this->belongsToMany('Attributes', [
            'className' => 'Shop.Attributes',
            'joinTable' => 'attributes_shop_variants',
            'through' => 'Shop.AttributesShopVariants',
            'cascadeCallbacks' => true
        ]);


        $this->addBehavior('CounterCache', [
            'ShopProducts' => ['product_variant_count'],
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
            ->scalar('sku')
            ->maxLength('sku', 255)
            ->requirePresence('sku', 'create')
            ->notEmptyString('sku');

        $validator
            ->integer('shop_product_id')
            ->requirePresence('shop_product_id', 'create')
            ->notEmptyString('shop_product_id');

        $validator
            ->boolean('published')
            ->add('published', 'custom', [ // la variante di default deve essere pubblicata
                'rule' => function ($value, $context) {
                    if (empty($context['data']['is_default'])){
                        return true;
                    } else {
                        return !empty($value);
                    }
                },
                'message' => __dx('shop', 'admin', 'default variant should be published')
            ]);
       

        $validator
            ->boolean('is_default')
            ->allowEmptyString('is_default');

        $validator
            ->integer('stock')
            ->notEmptyString('stock');

        $validator
            ->numeric('price')
            ->notEmptyString('price');

        $validator
            ->numeric('discounted_price')
            ->allowEmptyString('discounted_price');

        $validator
            ->numeric('price_net')
            ->notEmptyString('price_net');

        $validator
            ->numeric('discounted_price_net')
            ->allowEmptyString('discounted_price_net');

        $validator
            ->numeric('weight')
            ->notEmptyString('weight');

        return $validator;
    }


    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('shop_product_id', 'ShopProducts'), ['errorField' => 'shop_product_id']);
        $rules->add($rules->isUnique(['sku']));


        //verifico che almeno una variante sia impostata come default
        $rules->add(
            function ($entity, $options) {
                if ($entity->is_default) return true;
                if (!$entity->isNew()) {
                    $exists = $this->find()->where([
                        'shop_product_id' => $entity->shop_product_id, 
                        'is_default' => true,
                        'id <>' => $entity->id
                    ])->first();
                } else {
                    $exists = $this->findByShopProductIdAndIsDefault($entity->shop_product_id, true)->first();
                }
                
                return !empty($exists);
            },
            'atLeastOneDefault',
            [
                'errorField' => 'is_default',
                'message' => __dx('shop', 'admin', 'at least one variant should be default'),
            ]
        );

        //conteggio correttezza numero attributi passati
        $rules->add(function ($entity, $options) {
            if (!empty($entity->shop_product_id)) {

                $shopProduct = $this->ShopProducts->get($entity->shop_product_id);
                if (!empty($shopProduct)) {
                    if (!$shopProduct->has_variants){
                        //prodotto senza varianti -> non conto attributi
                        return true;
                    } else {
                        //verifico il numero di attributi passati
                        return $shopProduct->variant_attribute_count == count($entity->attributes);
                    }
                }
                return false;
            }
        }, 'wrongAttributesCount');
        return $rules;
    }


    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        
   
        // calcolo prezzi netti o ivati a seconda della configurazione
        if (!empty($data['shop_product_id'])) {
            
            $product = $this->ShopProducts->findById($data['shop_product_id'])->contain('VatRates')->first();

            if (!empty($product)) {
                // campi a default
                if (!$product->has_variants ) {
                    //pubblico la variante unica per i prodotti senza variante
                    $data['published'] = true;
                }
                if (!$product->has_variants || ($product->has_variants && !$product->product_variant_count)) {
                    // se il prodotto non ha varianti o è la prima variante la imposto come predefinita
                    $data['is_default'] = true;
                }
            }


            if (!empty($product) && !empty($product->vat_rate)){
                $vatRate = $product->vat_rate->value;
                $priceFields = ['price', 'discounted_price'];
                $vatIncluded = Configure::read('Shop.vatIncuded');

                foreach ($priceFields as $field){
                    $startingField = $vatIncluded ? $field : $field.'_net';
                    $destinationField = $vatIncluded ? $field.'_net' : $field;

                    if (!empty($data[$startingField])) {
                        $decimalVat = $vatRate / 100;
                        $price = $data[$startingField];
                        $finalPrice = $vatIncluded ? $price / (1 + $decimalVat) : $price * (1 + $decimalVat);
                        $data[$destinationField] = $finalPrice;
                    } else {
                        $data[$destinationField] = null;
                    }
                }
                
            }

        }



        
        // correggo gli attributi per la creazione

        if (!empty($data['attributes'])) {
        
            foreach ($data['attributes'] as $i => $attribute) {

                if (!empty($attribute['id'])) {
                    if ($attribute['id'] == 'new'){
                        unset($data['attributes'][$i]['id']);
                    } else {
                        unset($data['attributes'][$i]['title']);
                    }
                } else {
                    // hack per gestire variante vuota
                    unset($data['attributes'][$i]['id']);
                }
                
            }
        }

    }

    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $data, ArrayObject $options)
    {   
        $continue = true;

        // intervengo per riordinare gli errori sugli attributi rispettando l'ordine in data
        if (!empty($entity->attributes) && !empty($data['attributes'])) {
            $entityAttributes = new Collection($entity->attributes);
            $entity->attributes = [];

            foreach ($data['attributes'] as $attribute) {
                
                $match =  $entityAttributes->firstMatch([
                    'attribute_group_id' => $attribute['attribute_group_id']
                ]);

                if (empty($match->id) && empty($match->title)) {
                    $match->setError('id', __d('admin', 'This field cannot be left blank'));
                }
            
                $entity->attributes[] = $match;

            }
            
        }
        
    
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        $title = [];
        $updateTitle = true;

        if (empty($entity->shop_product_id)) return false;
        $product = $this->ShopProducts->get($entity->shop_product_id);
        if (empty($product)) return false;

        $title[] = $product->title;


        if ($product->has_variants) {
            // se sono presenti attributi nei dati li aggiungo al titolo... altrimenti non aggiorno il titolo
            if (!empty($entity->attributes)) {
                foreach ($entity->attributes as $i => $attribute) {
                    $title[] = $attribute->title;
                }
            } else {
                $updateTitle = false;
            }    
        }

        //gestione custom errori attributi
        $checkForDuplicates = $product->has_variants; // tutta la logica vale solo per prodotti con varianti

        if ($product->has_variants) {
            $matchingConditions = [];
            if (!empty($entity->attributes)) {
                foreach ($entity->attributes as $i => $attribute) {
                    if ($attribute->isNew() || $attribute->hasErrors()){
                        //in caso di errori o nuovi attributi non serve verificare se la variante è duplicata
                        $checkForDuplicates = false;
                        continue;
                    }
    
                    $matchingConditions[] = $attribute->id;
                }
            }
        }
       

        if ($checkForDuplicates) {
            //controllo se esiste una variante duplicata
            $query  = $this->findByShopProductId($entity->shop_product_id);
            if (!$entity->isNew()) {
                $query->where(['ShopProductVariants.id <>' => $entity->id]);
            }
            
            foreach ($matchingConditions as $i => $attributeId){
                $query->join([
                    'table' => 'attributes_shop_variants',
                    'alias' => 'attribute_'.$i,
                    'type' => 'INNER',
                    'conditions' => "attribute_$i.attribute_id = $attributeId AND attribute_$i.shop_product_variant_id = ShopProductVariants.id",
                ]);
            }

            $duplicate = $query->first();

            if (!empty($duplicate)) {
                // variante duplicata -> invalido i campi
                foreach ($entity->attributes as $i => $attribute) {
                    $entity->attributes[$i]->setErrors(['id' => ['_duplicated' => __dx('shop', 'admin', 'duplicated variant')]]);
                }
                return false;
            }
        
        }

    
        if($updateTitle) $entity->title = implode(' ', $title);

        // imposto il campo incomplete a false
        $entity->incomplete = false;
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // tolgo il predefinito dalle altre varianti
        if ($entity->isDirty('is_default') && !empty($entity->id)) {
            $this->updateAll(
                [  // fields
                    'is_default' => false,
                ],
                [  // conditions
                    'shop_product_id' => $entity->shop_product_id,
                    'is_default' => true,
                    'id <>' => $entity->id
                ]
            );
        }
    }


    // aggiorna il titolo di una variante tenendo conto di eventuali attributi
    public function updateTitle($id) {
        $entity = $this->findById($id)
            ->contain([
                'ShopProducts' => [
                    'VariantAttributeGroups'  => function(SelectQuery $q) {
                        return $q->order(['AttributeGroupsShopVariants.position' => 'ASC']);
                    }
                ],
                'Attributes'
            ])
            ->first();

        if (empty($entity) || empty($entity->shop_product)) return false;

        $title = [];
        $title[] = $entity->shop_product->title;

        if ($entity->shop_product->has_variants) {
            // se sono presenti attributi nei dati li aggiungo al titolo... altrimenti non aggiorno il titolo
            if (!empty($entity->attributes) && !empty($entity->shop_product->variant_attribute_groups)) {

                $attributes = new Collection($entity->attributes);
                
                // passo dai gruppi per rispettare l'ordine nel salavataggio del titolo
                foreach ($entity->shop_product->variant_attribute_groups as $i => $attributeGroup) {
                    $match = $attributes->firstMatch([
                        'attribute_group_id' => $attributeGroup->id
                    ]);
                    if (empty($match)) return false;
                    $title[] = $match->title;
                }
            } else {
                return false;
            }
        }


        $newTitle = implode(' ', $title);
        $entity->title = $newTitle;
        $this->getEventManager()->off('Model.beforeMarshal');
        $this->getEventManager()->off('Model.afterMarshal');
        $this->getEventManager()->off('Model.beforeSave');
        //$this->getEventManager()->off('Model.afterSave'); // questo lo lascio per salvare il sef url

        return $this->save($entity, ['checkRules' => false]);



    }

    // trova il prodotto con variante di default
    public function findDefaultVariant(Query $query, array $options)
    {
        extract($options);
        $query->contain('Preview')
            ->contain([
                'ShopProducts' => [
                    'Preview',
                    'Brands'
                ]
            ])
            ->where([
                'ShopProductVariants.published' => true,
                'ShopProductVariants.is_default' => true,
                'ShopProducts.published' => true
            ]);
            

        return $query;
    }


	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['ShopProductVariants.title LIKE' => "%" . trim($key) . "%"]);
    }


}
