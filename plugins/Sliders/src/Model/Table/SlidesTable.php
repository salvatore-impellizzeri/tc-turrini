<?php
declare(strict_types=1);

namespace Sliders\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

class SlidesTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('slides');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		$this->addBehavior('MultiTranslatable', ['fields' => ['title', 'subtitle', 'intro_title', 'text', 'cta', 'url', 'modified', '_status']]);

        $this->addBehavior('Sortable', [
            'scope' => 'slider_id'
        ]);

        $this->belongsTo('Sliders', [
            'foreignKey' => 'slider_id',
            'className' => 'Sliders.Sliders',
        ]);

		$this->hasOne('Preview', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setConditions(['Preview.model' => 'Slides', 'Preview.scope' => 'preview']);
    }


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
            ->integer('position')
            ->notEmptyString('position');

        $validator
            ->boolean('published')
            ->notEmptyString('published');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('slider_id', 'Sliders'), ['errorField' => 'slider_id']);

        return $rules;
    }
}
