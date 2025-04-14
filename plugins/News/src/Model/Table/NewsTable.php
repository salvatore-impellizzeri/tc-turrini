<?php
declare(strict_types=1);

namespace News\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;


class NewsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('news');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'excerpt', 'text', 'seotitle', 'seodescription', 'seokeywords', 'modified', '_status']]);
		$this->addBehavior('SefUrl');

        $this->hasOne('Preview', ['className' => 'Images.Images'])
        ->setForeignKey('ref')
        ->setConditions(['Preview.model' => 'News', 'Preview.scope' => 'preview']);

    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyString('date');

        $validator
            ->scalar('text')
            ->maxLength('text', 16777215)
            ->requirePresence('text', 'create')
            ->notEmptyString('text');


        return $validator;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['News.title LIKE' => "%" . trim($key) . "%"]);
    }

}
