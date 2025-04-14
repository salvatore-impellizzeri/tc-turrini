<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

class ShopCategoriesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);
        
        // configurazione tabella
        $this->setTable('shop_categories');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', [
            'maxDepth' => 3,
            'level' => 'level'
        ]);
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'description', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);
		$this->addBehavior('SefUrl');

        // relazioni (le relazioni con Images e Attachments sono create automaticamente se si estende AppTable)
        $this->belongsTo('ParentShopCategories', [
            'className' => 'Shop.ShopCategories',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildShopCategories', [
            'className' => 'Shop.ShopCategories',
            'foreignKey' => 'parent_id',
        ]);
        
        $this->belongsToMany('ShopProducts', [
            'className' => 'Shop.ShopProducts',
            'joinTable' => 'shop_categories_products'
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
        $rules->add($rules->existsIn('parent_id', 'ParentShopCategories'), ['errorField' => 'parent_id']);

        return $rules;
    }


	// restituisce un array con gli id dei figli di una categoria compresa se stessa
	public function getAllChildren($id = null){
		
		$children = null;
		
		if(!empty($id)){
			
			$category = $this->findById($id)->first();

			
			if(!empty($category)){			
				$children = $this->find('list')
                    ->where(['lft >=' => $category->lft, 'rght <=' => $category->rght])
                    ->select(['id'])
                    ->toArray();
			}
		}
		
		return $children;
	
	}

}
