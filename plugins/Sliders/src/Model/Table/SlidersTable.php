<?php
declare(strict_types=1);

namespace Sliders\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class SlidersTable extends Table
{
    
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sliders');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'modified', '_status']]);		

        $this->hasMany('Slides', [
            'foreignKey' => 'slider_id',
            'className' => 'Sliders.Slides',
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
