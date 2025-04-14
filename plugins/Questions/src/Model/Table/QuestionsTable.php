<?php
declare(strict_types=1);

namespace Questions\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;


class QuestionsTable extends AppTable
{
    
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('questions');
        $this->setDisplayField('question');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        
		$this->addBehavior('MultiTranslatable', ['fields' => ['question', 'answer', 'modified', '_status']]);
		$this->addBehavior('Sortable');

    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('question')
            ->maxLength('question', 255)
            ->requirePresence('question', 'create')
            ->notEmptyString('question');
			
        $validator
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

}
