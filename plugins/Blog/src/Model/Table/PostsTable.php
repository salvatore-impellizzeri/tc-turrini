<?php
declare(strict_types=1);

namespace Blog\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

class PostsTable extends AppTable
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

        $this->setTable('posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('SefUrl');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);

        $this->hasMany('ContentBlocks.ContentBlocks', [
            'foreignKey' => 'ref',
            'dependent' => true,
            'sort' => [
                'ContentBlocks.position' => 'ASC'
            ],
            'conditions' => [
                'ContentBlocks.plugin' => 'Blog',
                'ContentBlocks.model' => 'Posts'
            ]
        ]);


        $this->belongsToMany('Tags', [
            'className' => 'Blog.Tags',
        ]);

        $this->hasOne('Preview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setDependent(true)
			->setCascadeCallbacks(true)
			->setConditions(['Preview.model' => 'Posts', 'Preview.scope' => 'preview']);

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
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyString('date');    

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
        return $query->where(['Posts.title LIKE' => "%" . trim($key) . "%"]);
    }

}
