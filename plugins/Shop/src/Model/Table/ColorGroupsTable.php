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

class ColorGroupsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('color_groups');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'modified', '_status']]);
		$this->addBehavior('Sortable');


        $this->belongsToMany('Attributes', [
            'className' => 'Shop.Attributes',
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
            ->scalar('code')
            ->maxLength('code', 7)
            ->requirePresence('code', 'create')
            ->notEmptyString('code');



        return $validator;
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
        return $query->where(['ColorGroups.title LIKE' => "%" . trim($key) . "%"]);
    }


}
