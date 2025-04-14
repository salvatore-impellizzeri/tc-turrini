<?php
declare(strict_types=1);

namespace Policies\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class PoliciesTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('policies');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'text', 'modified', '_status']]);		
		$this->addBehavior('SefUrl');
    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')            
            ->requirePresence('title', 'create')
            ->notEmptyString('title', __d('admin', 'This field cannot be left blank'));

        $validator
            ->scalar('text')
            ->maxLength('text', 16777215)
            ->requirePresence('text', 'create')
            ->notEmptyString('text', __d('admin', 'This field cannot be left blank'));


        return $validator;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Policies.title LIKE' => "%" . trim($key) . "%"]);
    }
		
}
