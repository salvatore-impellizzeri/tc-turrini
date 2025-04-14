<?php

declare(strict_types=1);

namespace Contacts\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contacts Model
 *
 * @method \Contacts\Model\Entity\Contact newEmptyEntity()
 * @method \Contacts\Model\Entity\Contact newEntity(array $data, array $options = [])
 * @method \Contacts\Model\Entity\Contact[] newEntities(array $data, array $options = [])
 * @method \Contacts\Model\Entity\Contact get($primaryKey, $options = [])
 * @method \Contacts\Model\Entity\Contact findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Contacts\Model\Entity\Contact patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Contacts\Model\Entity\Contact[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Contacts\Model\Entity\Contact|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Contacts\Model\Entity\Contact saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Contacts\Model\Entity\Contact[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Contacts\Model\Entity\Contact[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Contacts\Model\Entity\Contact[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Contacts\Model\Entity\Contact[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContactFormsTable extends Table
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

        $this->setTable('contact_forms');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ContactFields', [
            'foreignKey' => 'contact_form_id',
            'className' => 'Contacts.ContactFields',
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
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->email('mailto')
            ->maxLength('mailto', 255)
            ->requirePresence('mailto', 'create')
            ->notEmptyString('mailto');


        return $validator;
    }

    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['ContactForms.title LIKE' => "%" . trim($key) . "%"]);
    }
}
