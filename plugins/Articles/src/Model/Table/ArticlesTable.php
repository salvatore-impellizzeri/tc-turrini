<?php
declare(strict_types=1);

namespace Articles\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @method \Articles\Model\Entity\Article newEmptyEntity()
 * @method \Articles\Model\Entity\Article newEntity(array $data, array $options = [])
 * @method \Articles\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \Articles\Model\Entity\Article get($primaryKey, $options = [])
 * @method \Articles\Model\Entity\Article findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Articles\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Articles\Model\Entity\Article[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Articles\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Articles\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
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

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
		
		$this->hasMany('Images', ['className' => 'Images.Images'])
				->setForeignKey('ref')
				->setDependent(true)
				->setCascadeCallbacks(true)
				->setConditions(['Images.model' => 'Articles']);	
				
		$this->hasMany('Attachments', ['className' => 'Attachments.Attachments'])
				->setForeignKey('ref')
				->setDependent(true)
				->setCascadeCallbacks(true)
				->setConditions(['Attachments.model' => 'Articles']);
				
        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'modified', '_status']]);		
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
            ->maxLength('title', 255, 'titolo troppo lungo')
            ->requirePresence('title', 'create')
            ->notEmptyString('title');
			
		$validator
            ->time('time');            

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
        return $query->where(['Articles.title LIKE' => "%" . trim($key) . "%"]);
    }
		
}
