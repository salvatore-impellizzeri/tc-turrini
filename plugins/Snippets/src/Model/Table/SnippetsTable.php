<?php
declare(strict_types=1);

namespace Snippets\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;


class SnippetsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('snippets');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'title_2', 'title_3', 'text', 'text_2', 'text_3', 'modified', '_status']]);

        $this->hasOne('Preview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['Preview.model' => $this->getAlias(), 'Preview.scope' => 'preview']);
		//$this->addBehavior('SefUrl');
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
            ->allowEmptyString('text');


        return $validator;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Snippets.title LIKE' => "%" . trim($key) . "%"]);
    }

}
