<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;
use Cake\Utility\Hash;
use Cake\ORM\RulesChecker;

class VatRatesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

		// configurazione tabella
        $this->setTable('vat_rates');
        $this->setDisplayField('value');
        $this->setPrimaryKey('id');

        // behavior
		$this->addBehavior('Timestamp');

        $this->hasMany('ShopProducts', [
            'className' => 'Shop.ShopProducts'
        ]);

    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->boolean('is_default')
            ->allowEmptyString('is_default');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // verifico che almeno una aliquota sia di default
        $rules->add(
            function ($entity, $options) {
                if ($entity->is_default) return true;

                if (!$entity->isNew()) {
                    $exists = $this->find()->where([
                        'is_default' => true,
                        'id <>' => $entity->id
                    ])->first();
                } else {
                    $exists = $this->findByIsDefault(true)->first();
                }
                
                return !empty($exists);
            },
            'atLeastOneDefault',
            [
                'errorField' => 'is_default',
                'message' => __dx('shop', 'admin', 'at least one vat rate should be default'),
            ]
        );

        return $rules;
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options){
        if ($entity->isDirty('is_default') && !empty($entity->id)) {
            $this->updateAll(
                [  // fields
                    'is_default' => false,
                ],
                [  // conditions
                    'is_default' => true,
                    'id <>' => $entity->id
                ]
            );
        }
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
        return $query->where(['VatRates.value LIKE' => "%" . trim($key) . "%"]);
    }


}
