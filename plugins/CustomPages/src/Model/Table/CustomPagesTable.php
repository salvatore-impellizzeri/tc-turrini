<?php
declare(strict_types=1);

namespace CustomPages\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;


class CustomPagesTable extends AppTable
{

    public $layouts = [
        'home',
        'contatti'
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('custom_pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => [
            'title',
            'string_1',
            'string_2',
            'string_3',
            'string_4',
            'string_5',
            'string_6',
            'string_7',
            'string_8',
            'string_9',
            'string_10',
            'text_1',
            'text_2',
            'text_3',
            'text_4',
            'text_5',
            'text_6',
            'text_7',
            'text_8',
            'text_9',
            'text_10',
            'seotitle',
            'seodescription',
            'seokeywords',
            'modified',
            '_status'
        ]]);
		$this->addBehavior('SefUrl');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
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
            ->scalar('layout')
            ->maxLength('layout', 255)
            ->requirePresence('layout', 'create')
            ->notEmptyString('layout');

        $validator
            ->scalar('seotitle')
            ->maxLength('seotitle', 255)
            ->allowEmptyString('seotitle');

        $validator
            ->scalar('seokeywords')
            ->maxLength('seokeywords', 255)
            ->allowEmptyString('seokeywords');

        $validator
            ->scalar('seodescription')
            ->maxLength('seodescription', 255)
            ->allowEmptyString('seodescription');

        return $validator;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['CustomPages.title LIKE' => "%" . trim($key) . "%"]);
    }

}
