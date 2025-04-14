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

class BrandsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('brands');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'description', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);
		$this->addBehavior('SefUrl');
		$this->addBehavior('Sortable');


        $this->hasMany('ShopProducts', [
            'className' => 'Shop.ShopProducts'
        ]);


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
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {

        $rules->add($rules->isUnique(['title']));

        return $rules;
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!empty($entity->product_count)) {
            // non faccio eliminare una categoria non vuota 
            $event->stopPropagation();
        }
    }


	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Brands.title LIKE' => "%" . trim($key) . "%"]);
    }


}
