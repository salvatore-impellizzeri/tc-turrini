<?php

declare(strict_types=1);

namespace Contacts\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;
use Cake\Event\EventInterface;

class ContactFieldsTable extends Table
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

        $this->setTable('contact_fields');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('MultiTranslatable', ['fields' => ['label']]);

        $this->addBehavior('Sortable', [
            'scope' => 'contact_form_id'
        ]);

        $this->belongsTo('ContactForms', [
            'foreignKey' => 'contact_form_id',
            'className' => 'Contacts.ContactForms',
        ]);
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
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');


        $validator
            ->scalar('label')
            ->maxLength('label', 255)
            ->requirePresence('label', 'create')
            ->notEmptyString('label');

        $validator
            ->scalar('field')
            ->maxLength('field', 255)
            ->requirePresence('field', 'create')
            ->notEmptyString('field');


        return $validator;
    }

    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['ContactFields.title LIKE' => "%" . trim($key) . "%"]);
    }

    public function beforeSave(EventInterface $event, $entity, $options)
    {   
        if ($entity->isDirty('field')) {
            $entity->field = strtolower(Text::slug($entity->field));
        }
        
    }
}
