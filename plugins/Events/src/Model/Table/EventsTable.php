<?php
declare(strict_types=1);

namespace Events\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;


class EventsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');


        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'text', 'seotitle', 'seodescription', 'seokeywords', 'modified', '_status']]);
		$this->addBehavior('SefUrl');


        $this->hasOne('Preview', ['className' => 'Images.Images'])
        ->setForeignKey('ref')
        ->setConditions(['Preview.model' => 'Events', 'Preview.scope' => 'preview']);

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


        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date', __d('admin', 'This field cannot be left blank'));


        return $validator;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Events.title LIKE' => "%" . trim($key) . "%"]);
    }

}
