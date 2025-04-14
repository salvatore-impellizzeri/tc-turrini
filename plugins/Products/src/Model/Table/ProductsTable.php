<?php
declare(strict_types=1);

namespace Products\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use Cake\Utility\Hash;

class ProductsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('products');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'text', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);
		$this->addBehavior('SefUrl');
		$this->addBehavior('Sortable');



		$this->belongsTo('ProductCategories', ['className' => 'Products.ProductCategories']);


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
            ->integer('product_category_id')
            ->notEmptyString('product_category_id');


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
        $rules->add($rules->existsIn('product_category_id', 'ProductCategories'), ['errorField' => 'product_category_id']);
        return $rules;
    }

	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Products.title LIKE' => "%" . trim($key) . "%"]);
    }


}
