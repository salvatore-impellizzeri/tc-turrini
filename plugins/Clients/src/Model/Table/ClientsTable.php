<?php
declare(strict_types=1);

namespace Clients\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Collection\Hash;
use Cake\ORM\Rule\IsUnique;
use Cake\Core\Configure;
use Cake\Utility\Text;
use Cake\Utility\Security;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Stripe\Stripe;
use Stripe\Customer;



class ClientsTable extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('clients');
        $this->setDisplayField('fullname');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


    }


    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('fullname')
            ->requirePresence('fullname', 'create')
            ->notEmptyString('fullname');

		$validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
			->add('email', 'unique_if_not_guest', [
                'rule' =>  function($value, $context){
					$id = $context['data']['id'] ?? null;
					$email = $value;

                    if(!empty($id)) {
                        $existing = $this->find()
                            ->where([
                                'email' => $email,
                                'id !=' => $id
                            ])
                            ->first();
                    } else {
                        $existing = $this->find()
                            ->where([
                                'email' => $email
                            ])
                            ->first();
                    }

                    if(!empty($existing)) {
                        return false;
                    }

					return true;
                },
                'message' => __d('clients', 'this email is already in use'),
            ]);

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->regex('password', '^(?=.*\d).{8,}$^', __d('clients', 'password hint'));

        $validator
            ->scalar('repeat_password')
            ->requirePresence('repeat_password', 'create')
            ->notEmptyString('repeat_password')
            ->add('repeat_password', 'unique_if_not_guest', [
                'rule' =>  function($value, $context){

					$password = $context['data']['password'] ?? null;
					$repeatPassword = $value;

					return $password == $repeatPassword;
                },
                'message' => __d('clients', 'password does not match'),
            ]);


        $validator
            ->boolean('privacy')
            ->equals('privacy', true, __d('cake', 'This field is required'))
            ->requirePresence('privacy', 'create')
            ->notEmptyString('privacy', __d('clients', 'This field is required'));

        return $validator;
    }


    public function validationPassword(Validator $validator): Validator
    {



        $validator
            ->scalar('password')
            ->requirePresence('password')
            ->regex('password', '^(?=.*\d).{8,}$^', __d('clients', 'password hint'));

        return $validator;
    }



	public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
		$entity->locale = ACTIVE_LANGUAGE;
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = trim($options['key']);
        return $query->where([
			'OR' => [
				$this->getAlias() . '.fullname LIKE' => "%" . $key . "%",
				$this->getAlias() . '.email LIKE' => "%" . $key . "%"
			]
		]);
    }


    public function findEnabled(Query $query, array $options)
    {
        return $query->where([
            'Clients.enabled' => true,
		]);
    }



}
