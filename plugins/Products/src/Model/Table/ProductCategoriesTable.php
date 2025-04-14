<?php
declare(strict_types=1);

namespace Products\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

class ProductCategoriesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);
        
        // configurazione tabella
        $this->setTable('product_categories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', [
            'maxDepth' => 2,
            'level' => 'level'
        ]);
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'text', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);
		$this->addBehavior('SefUrl');

        // relazioni (le relazioni con Images e Attachments sono create automaticamente se si estende AppTable)
        $this->belongsTo('ParentProductCategories', [
            'className' => 'Products.ProductCategories',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildProductCategories', [
            'className' => 'Products.ProductCategories',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'product_category_id',
            'className' => 'Products.Products',
        ]);
        $this->belongsToMany('Products', [
            'className' => 'Products.Products',
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
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }


    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('parent_id', 'ParentProductCategories'), ['errorField' => 'parent_id']);

        return $rules;
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options){
        
        $products_number = $this->Products->find()->where(['Products.product_category_id' => $entity->id])->count();

    	if($products_number) return false;        

    }

	// restituisce un array con gli id dei figli di una categoria compresa se stessa
	public function getAllChildren($id = null){
		
		$children = null;
		
		if(!empty($id)){
			
			$product_category = $this->findById($id);
			
			if(!empty($product_category)){			
				$children = $this->find()
                            ->where(['lft >=' => $product_category->lft, 'rght <=' => $product_category->rght])
							->select(['id'])
                            ->toList();
			}
		}
		
		return $children;
	
	}

}
