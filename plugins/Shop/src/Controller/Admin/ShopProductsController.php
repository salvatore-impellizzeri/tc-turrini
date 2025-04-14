<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;
use Cake\Http\Exception\UnauthorizedException;
use Cake\View\JsonView;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;


class ShopProductsController extends AppController
{
    public function viewClasses(): array
	{
		return [JsonView::class];
	}

	public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit', 'index'])) {

            $shopCategories = $this->ShopProducts->ShopCategories->find('threaded')
                ->order(['ShopCategories.lft' => 'ASC']);

            $shopCategories = $this->ShopProducts->ShopCategories->formatTreeList($shopCategories, spacer: '- ')->toArray();

            $brands = $this->ShopProducts->Brands->find('list')
                ->order(['Brands.title' => 'ASC']);

			$this->set(compact('shopCategories', 'brands'));

        }

        if (in_array($this->request->getParam('action'), ['edit'])) {
            $vatRates = $this->ShopProducts->VatRates->find('list')
                ->order(['VatRates.value' => 'DESC']);

			$this->set(compact('vatRates'));

        }

        
    }


	public function get()
    {
		$this->queryOrder = ['ShopProducts.position' => 'ASC'];
		$this->queryContain = [
            'Brands'
		];

        //ignoro questo filtro per impostare condizioni custom
        $this->ignoreFilters = ['shop_category_id'];
        $query = $this->request->getQuery();
        if (!empty($query['filters'])){
            $filters = json_decode($query['filters'], true);
            if (!empty($filters['shop_category_id'])) {
                $shopCategories = $this->ShopProducts->ShopCategories->getAllChildren($filters['shop_category_id']);
                if (!empty($shopCategories)) {
                    $this->queryMatchings = [
                        'ShopCategories' => [
                            'ShopCategories.id IN' => array_keys($shopCategories)
                        ]
                    ];
                    $this->queryGroup = ['ShopProducts.id'];
                }
            }
        }

		parent::get();
	}

    // riscrivo il metodo add per creare bozze redirect su edit
    public function add()
	{   
        // verfico se ci sono aliquote iva
        $vatRate = $this->ShopProducts->VatRates->findByIsDefault(true)->first();
    
        if (empty($vatRate)) {
            $this->Flash->error(__dx('shop', 'admin', 'no vat rates error'));
            return $this->redirect(['action' => 'index']);
        }

		$item = $this->ShopProducts->newEmptyEntity();
        $item->draft = true;
        $item->vat_rate_id = $vatRate->id;
 
        

        $this->ShopProducts->save($item);
        return $this->redirect(['action' => 'edit', $item->id]);
        
	}


	public function edit($id = null)
    {

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
            'Attachments',
            
            'ShopCategories' => [
                'queryBuilder' => function (Query $q) {
                    return $q->order(['position' => 'ASC']);
                }
            ],
            'ShopProductVariants' => [
                'queryBuilder' => function (Query $q) {
                    return $q->where(['ShopProductVariants.is_default' => true]);
                }
            ]
		];

        parent::edit($id);
    }

    // collega un prodotto ad un gruppo attributi esistente
    public function linkAttributeGroup()
	{
        $this->_linkAttributeGroup('AttributeGroups');
	}

    // collega un prodotto ad un gruppo attributi esistente (varianti)
    public function linkVariantAttributeGroup()
	{
        $this->_linkAttributeGroup('VariantAttributeGroups');
	}

    protected function _linkAttributeGroup($targetModel)
    {
        $data = $this->request->getData();
        $requiredData = [
            'attribute_group_id',
            'shop_product_id',
            'position'
        ];

        foreach ($requiredData as $requiredField) {
            if (!array_key_exists($requiredField, $data)) throw new UnauthorizedException("Error Processing Request");
        }

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = [''];
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attributeGroup = $this->ShopProducts->{$targetModel}->get($data['attribute_group_id']);

            if (!empty($shopProduct) && !empty($attributeGroup)) {
                $attributeGroup->_joinData = new Entity(['position' => $data['position']], ['markNew' => true]);

                $this->ShopProducts->{$targetModel}->link($shopProduct, [$attributeGroup]);
                $response = $attributeGroup;
            } else {
                throw new UnauthorizedException("Error Processing Request");
            }
	

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}
    }

    // scollega un prodotto ad un gruppo attributi esistente
    public function unlinkAttributeGroup()
	{
        $this->_unlinkAttributeGroup('AttributeGroups');
	}

    // scollega un prodotto ad un gruppo attributi esistente (varianti)
    public function unlinkVariantAttributeGroup()
	{
        $this->_unlinkAttributeGroup('VariantAttributeGroups');
	}


    protected function _unlinkAttributeGroup($targetModel)
	{

        $data = $this->request->getData();
        $requiredData = [
            'attribute_group_id',
            'shop_product_id',
        ];

        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = 'fail';
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attributeGroup = $this->ShopProducts->{$targetModel}->get($data['attribute_group_id']);

            if (!empty($shopProduct) && !empty($attributeGroup)) {
                //TODO svuotare tutti i collegamenti con i valori
                $this->ShopProducts->{$targetModel}->unlink($shopProduct, [$attributeGroup]);
                $response = 'success';
            } else {
                throw new UnauthorizedException("Error Processing Request");
            }
	

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}
	
	}


    // crea gruppo attributi e lo collega al prodotto
    public function addAttributeGroup()
    {
        $this->_addAttributeGroup('AttributeGroups');
    }

     // crea gruppo attributi e lo collega al prodotto (varianti)
     public function addVariantAttributeGroup()
     {
         $this->_addAttributeGroup('VariantAttributeGroups');
     }

    protected function _addAttributeGroup($targetModel)
    {
        $data = $this->request->getData();
        $requiredData = [
            'shop_product_id',
            'position'
        ];

        foreach ($requiredData as $requiredField) {
            if (!array_key_exists($requiredField , $data)) throw new UnauthorizedException("Error Processing Request");
        }    

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = [
                'success' => false,
                'record' => null,
                'errors' => null
            ];
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attributeGroup = $this->ShopProducts->{$targetModel}->newEntity([
                'title' => $data['title'],
                'attribute_type_id' => $data['attribute_type_id']
            ]);

            if (!$this->ShopProducts->{$targetModel}->save($attributeGroup)){
                $response['errors'] = $attributeGroup->getErrors();
            } else {
                if (!empty($shopProduct) && !empty($attributeGroup)) {
                    $attributeGroup->_joinData = new Entity(['position' => $data['position']], ['markNew' => true]);
                    $this->ShopProducts->{$targetModel}->link($shopProduct, [$attributeGroup]);
                    $response['success'] = true;
                    $response['record'] = $attributeGroup;
                } else {
                    throw new UnauthorizedException("Error Processing Request");
                }
            }

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}
    }

    public function sortAttributeGroups()
    {
        $this->_sortAttributeGroups('AttributeGroupsShopProducts');
    }

    public function sortVariantAttributeGroups()
    {
        $this->_sortAttributeGroups('AttributeGroupsShopVariants');
    }

    protected function _sortAttributeGroups($joinTable)
    {
        $data = $this->request->getData();
        $requiredData = [
            'attribute_group_ids',
            'shop_product_id',
        ];

        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }

        if ($this->request->is('post')) {
            $joinTable = TableRegistry::getTableLocator()->get('Shop.'.$joinTable);
            $response = 'fail';

            $items = $joinTable->find()
                ->where([
                    'shop_product_id' => $data['shop_product_id'],
                    'attribute_group_id IN' => $data['attribute_group_ids'],
                ])
                ->order([
                    "FIELD(attribute_group_id, " . implode(',', $data['attribute_group_ids']) . ")" // SQL personalizzato per mantenere l'ordine
                ])
                ->all();

            foreach ($items as $i => $item) {
                $item->position = $i;
            } 

            if ($joinTable->saveMany($items)) $response = 'success';

            $this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
        }

    }


    // collega un prodotto ad un gruppo attributi esistente
    public function linkAttribute()
	{

        $data = $this->request->getData();
        $requiredData = [
            'attribute_id',
            'shop_product_id',
        ];

        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = [''];
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attribute = $this->ShopProducts->Attributes->get($data['attribute_id']);
            
            if (!empty($shopProduct) && !empty($attribute)) {
                $this->ShopProducts->Attributes->link($shopProduct, [$attribute]);
                $response = $attribute;
            } else {
                throw new UnauthorizedException("Error Processing Request");
            }
	

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}

	}

    // scollega un prodotto ad un gruppo attributi esistente
    public function unlinkAttribute()
	{

        $data = $this->request->getData();
        $requiredData = [
            'attribute_id',
            'shop_product_id',
        ];
        
        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }


		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = 'fail';
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attribute = $this->ShopProducts->Attributes->get($data['attribute_id']);
            
            if (!empty($shopProduct) && !empty($attribute)) {
                $this->ShopProducts->Attributes->unlink($shopProduct, [$attribute]);
                $response = 'success';
            } else {
                throw new UnauthorizedException("Error Processing Request");
            }
	

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}
	
	}
    


    // crea un attributo e lo collega al prodotto
    public function addAttribute(){
        $data = $this->request->getData();
        $requiredData = [
            'shop_product_id',
            'attribute_group_id'
        ];

        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }    

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = [
                'success' => false,
                'record' => null,
                'errors' => null
            ];
            $shopProduct = $this->ShopProducts->get($data['shop_product_id']);
            $attribute = $this->ShopProducts->Attributes->newEntity([
                'title' => $data['title'],
                'attribute_group_id' => $data['attribute_group_id']
            ]);

            if (!$this->ShopProducts->Attributes->save($attribute)){
                $response['errors'] = $attribute->getErrors();
            } else {
                if (!empty($shopProduct) && !empty($attribute)) {
                    //$attributeGroup->_joinData = new Entity(['position' => $data['position']], ['markNew' => true]);
                    $this->ShopProducts->Attributes->link($shopProduct, [$attribute]);
                    $response['success'] = true;
                    $response['record'] = $attribute;
                } else {
                    throw new UnauthorizedException("Error Processing Request");
                }
            }

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}
    }

    public function addVariant() {
        if ($this->request->is('post')) {
            
            $shopProductVariant = $this->ShopProducts->ShopProductVariants->newEntity($this->request->getData());


            if (!$this->ShopProducts->ShopProductVariants->save($shopProductVariant)){
                $response['errors'] = $shopProductVariant->getErrors();
                $this->Flash->error(__d('admin', 'Save error'));
            } else {
                $response['success'] = true;
                $response['record'] = $this->ShopProducts->ShopProductVariants->findById($shopProductVariant->id)->contain('Attributes')->first();
                $this->Flash->success(__d('admin', 'Save success'));
            }

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');

        }
    }

    public function deleteVariant()
	{

		$query = $this->request->getQuery();
		if (!empty($query['id'])) {
			$item = $this->ShopProducts->ShopProductVariants->findById($query['id'])->first();
			if ($this->ShopProducts->ShopProductVariants->delete($item)) {
				$this->set('result', ['delete' => true]);
                $this->Flash->success(__d('admin', 'Delete success'));
			} else {
                $this->set('result', [
                    'delete' => false,
                    'message' => __d('admin', 'Delete error')
                ]);
            }
            $this->viewBuilder()->setOption('serialize', 'result');
            return;
		}

		throw new UnauthorizedException("Error Processing Request");
	}

    // cancella tutte le varianti di un prodotto
    public function deleteAllVariants()
	{

		$productId = $this->request->getData('id');

		if ($productId) {
			$items = $this->ShopProducts->ShopProductVariants->findByShopProductId($productId)->all();

            if ($items->count()) {
                $success = true;
                foreach ($items as $item) {
                    $tmp = $this->ShopProducts->ShopProductVariants->delete($item);
                    if (!$tmp) $success = false;
                }
                $this->set('result', ['delete' => $success]);
            } else {
                $this->set('result', ['delete' => true]);
            }

            $this->viewBuilder()->setOption('serialize', 'result');
            return;
		}

		throw new UnauthorizedException("Error Processing Request");
	}

    //imposta le varianti come incomplete
    public function setVariantsIncomplete()
	{

		$productId = $this->request->getData('id');
        if ($this->request->is('post')) {
            $response = 'fail';

            if ($productId) {
                $this->ShopProducts->ShopProductVariants->updateAll(
                    [
                        'incomplete' => true,
                    ],
                    [
                        'shop_product_id' => $productId
                    ]
                );

                $response = 'success';
                
            }
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', 'response');
            return;
        }

		throw new UnauthorizedException("Error Processing Request");
	}

    // scollega tutte le varianti di un prodotto dagli attributi di un gruppo
    public function unlinkAllAttributes()
	{

        $data = $this->request->getData();
        $requiredData = [
            'attribute_group_id',
            'shop_product_id',
        ];
        
        foreach ($requiredData as $requiredField) {
            if (empty($data[$requiredField])) throw new UnauthorizedException("Error Processing Request");
        }


		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post')) {
            
			$response = 'fail';

            $attributes = $this->ShopProducts->ShopProductVariants->Attributes->find('list')
                ->where(['Attributes.attribute_group_id' => $data['attribute_group_id']])
                ->all()
                ->toArray();

            $shopProductVariants = $this->ShopProducts->ShopProductVariants->find('list')
                ->where(['ShopProductVariants.shop_product_id' => $data['shop_product_id']])
                ->all()
                ->toArray();
            
        
            
            if (count($shopProductVariants) && count($attributes)) {
                $joinRows = $this->ShopProducts->ShopProductVariants->AttributesShopVariants->find()
                    ->where([
                        'attribute_id IN' => array_keys($attributes),
                        'shop_product_variant_id IN' => array_keys($shopProductVariants)
                    ])
                    ->all();

                if ($joinRows->count()) {
                    $deleted = $this->ShopProducts->ShopProductVariants->AttributesShopVariants->deleteMany($joinRows);
                    $response = $deleted ? 'success' : 'fail';
                }
            } 

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
            return;
		} 

        throw new UnauthorizedException("Error Processing Request");
	
	}

    public function testUnlink() {
        


        
     
        
        


        
        die('test');
    }



}
