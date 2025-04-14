<?php
declare(strict_types=1);

namespace Menu\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class MenuItemsTable extends Table
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

        $this->setTable('menu_items');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree', [
            'scope' => ['menu_id'],
            'maxDepth' => Configure::read('Setup.menu_depth'),
            'level' => 'level'
        ]);

        $this->belongsTo('ParentMenuItems', [
            'className' => 'Menu.MenuItems',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildMenuItems', [
            'className' => 'Menu.MenuItems',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('Menus', [
            'foreignKey' => 'menu_id',
            'className' => 'Menu.Menus',
        ]);

        $this->addBehavior('MultiTranslatable', ['fields' => ['title', 'modified', '_status']]);
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
            ->integer('backend_menu_id')
            ->notEmptyString('backend_menu_id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->integer('position')
            ->notEmptyString('position');

        $validator
            ->boolean('blank')
            ->notEmptyString('blank');

        $validator
            ->boolean('custom')
            ->notEmptyString('custom');

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
        $rules->add($rules->existsIn('parent_id', 'ParentMenuItems'), ['errorField' => 'parent_id']);
        $rules->add($rules->existsIn('menu_id', 'Menus'), ['errorField' => 'menu_id']);

        return $rules;
    }

}
