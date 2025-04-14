<?php
declare(strict_types=1);

namespace Cookies\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CookieTypesTable extends Table
{
    
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('cookie_types');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'text', 'modified', '_status']]);	

		$this->addBehavior('Sortable');		

        $this->hasMany('Cookies', [
            'foreignKey' => 'cookie_type_id',
            'className' => 'Cookies.Cookies',
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

}
