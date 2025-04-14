<?php

declare(strict_types=1);

namespace Contacts\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Event\EventInterface;

class ContactsTable extends Table
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

        $this->setTable('contacts');
        $this->setPrimaryKey('id');

        $this->belongsTo('ContactForms', [
            'foreignKey' => 'contact_form_id',
            'className' => 'Contacts.ContactForms',
        ]);

        $this->addBehavior('Timestamp');
    }


    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['Contacts.id LIKE' => "%" . trim($key) . "%"]);
    }


    //trasformo i campi in un json
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        $bodyData = $data->getArrayCopy();
  
        unset($bodyData['id'], $bodyData['contact_form_id'], $bodyData['contact_form_hash']);

        //tolgo il token antispam
        $keys = array_keys($bodyData);
        $results = preg_grep('/^token_\d*$/', $keys);
        if (!empty($results)) {
            foreach ($results as $token) unset($bodyData[$token]);
        }

        //trasformo gli altri campi in un json
        $data['body'] = json_encode($bodyData);

    }

    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, $primary) {
		
		return $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
					
			return $results->map(function ($row) {
				 
                // mappo i campi del body come campi normali
				if(!empty($row) && !empty($row->body)) {
                    $data = json_decode($row->body, true);
                    foreach ($data as $key => $value){
                        $row->{$key} = $value;
                    }

                    $row->body = null;
				}

				return $row;
			});
			
		});
		
	}
}
