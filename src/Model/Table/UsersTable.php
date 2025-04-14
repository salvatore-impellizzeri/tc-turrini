<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsersTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('username')
            ->maxLength('username', 40)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->integer('group_id')
            ->requirePresence('group_id', 'create')
            ->notEmptyString('group_id');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 40)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->regex('password', '^(?=.*\d).{8,}$^', __d('admin', 'strong password'));

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->dateTime('last_login')
            ->allowEmptyDateTime('last_login');

        return $validator;
    }

    public function validationPassword(Validator $validator): Validator
    {



        $validator
            ->add('user_password', 'custom', [
                'rule'=>  function($value, $context){
                    $user = $this->get(Router::getRequest()->getAttribute('identity')->getIdentifier());
                    if ($user) {
                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message' => __d('admin', 'wrong user password'),
            ])
            ->notEmpty('current_password');



        $validator
            ->scalar('password')
            ->requirePresence('password')
            ->regex('password', '^(?=.*\d).{8,}$^', __d('admin', 'strong password'));

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn('group_id', 'Groups'), ['errorField' => 'group_id']);

        return $rules;
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{
		// quando riattiviamo un utente andiamo a impostare a 0 i tentativi di login falliti
		if($entity->isDirty('active')) {
            if(!empty($entity->active)) $entity->set('failed_login_attempts', 0);
		}

        return true;
	}

    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where([
            'OR' => [
                $this->getAlias() . '.username LIKE' => "%" . trim($key) . "%",
                $this->getAlias() . '.email LIKE' => "%" . trim($key) . "%",
            ]

        ]);
    }

}
