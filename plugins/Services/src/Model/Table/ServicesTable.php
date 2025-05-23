<?php
declare(strict_types=1);

namespace Services\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

class ServicesTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('services');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'text', 'seotitle', 'seodescription', 'seokeywords', 'modified', '_status']]);
		$this->addBehavior('SefUrl');
        $this->addBehavior('Sortable');

        $this->hasOne('Preview', ['className' => 'Images.Images'])
        ->setForeignKey('ref')
        ->setConditions(['Preview.model' => 'Services', 'Preview.scope' => 'preview']);

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
            ->scalar('excerpt')
            ->allowEmptyString('excerpt');

        $validator
            ->scalar('text')
            ->maxLength('text', 16777215)
            ->allowEmptyString('text');

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
        return $query->where(['Services.title LIKE' => "%" . trim($key) . "%"]);
    }
}
