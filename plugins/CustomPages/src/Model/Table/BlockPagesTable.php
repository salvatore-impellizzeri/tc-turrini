<?php
declare(strict_types=1);

namespace CustomPages\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

class BlockPagesTable extends AppTable
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

        $this->setTable('block_pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('SefUrl');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'seotitle', 'seokeywords', 'seodescription', 'modified', '_status']]);

        $this->hasMany('ContentBlocks.ContentBlocks', [
            'foreignKey' => 'ref',
            'dependent' => true,
            'sort' => [
                'ContentBlocks.position' => 'ASC'
            ],
            'conditions' => [
                'ContentBlocks.plugin' => 'CustomPages',
                'ContentBlocks.model' => 'BlockPages'
            ]
        ]);


        $this->hasOne('SocialPreview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['SocialPreview.model' => $this->getAlias(), 'SocialPreview.scope' => 'social_preview']);
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
        return $query->where(['BlockPages.title LIKE' => "%" . trim($key) . "%"]);
    }

}
