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

class AttributeTypesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('attribute_types');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('Sortable');


        $this->hasMany('AttributeGroups', [
            'className' => 'Shop.AttributeGroups'
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

        return $validator;
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // non si possono eliminare
        $event->stopPropagation();
    }


	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['AttributeTypes.title LIKE' => "%" . trim($key) . "%"]);
    }


}
