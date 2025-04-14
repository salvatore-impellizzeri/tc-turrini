<?php
declare(strict_types=1);

namespace SefUrls\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SefUrls Model
 *
 * @method \SefUrls\Model\Entity\SefUrl newEmptyEntity()
 * @method \SefUrls\Model\Entity\SefUrl newEntity(array $data, array $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[] newEntities(array $data, array $options = [])
 * @method \SefUrls\Model\Entity\SefUrl get($primaryKey, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \SefUrls\Model\Entity\SefUrl|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \SefUrls\Model\Entity\SefUrl[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SefUrlsTable extends Table
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

        $this->setTable('sef_urls');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('original')
            ->maxLength('original', 255)
            ->requirePresence('original', 'create')
            ->notEmptyString('original');

        $validator
            ->scalar('rewritten')
            ->maxLength('rewritten', 255)
            ->requirePresence('rewritten', 'create')
            ->notEmptyString('rewritten');

        $validator
            ->boolean('custom')
            ->notEmptyString('custom');

        $validator
            ->integer('code')
            ->notEmptyString('code');

        $validator
            ->scalar('plugin')
            ->maxLength('plugin', 255)
            ->requirePresence('plugin', 'create')
            ->notEmptyString('plugin');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 255)
            ->requirePresence('controller', 'create')
            ->notEmptyString('controller');

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->notEmptyString('action');

        $validator
            ->scalar('param')
            ->maxLength('param', 255)
            ->notEmptyString('param');

        $validator
            ->scalar('locale')
            ->maxLength('locale', 2)
            ->requirePresence('locale', 'create')
            ->notEmptyString('locale');

        return $validator;
    }
}
