<?php
declare(strict_types=1);

namespace ContentBlocks\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;


class ContentBlocksTable extends AppTable
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

        $this->setTable('content_blocks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ContentBlockTypes', [
            'className' => 'ContentBlocks.ContentBlockTypes',
        ]);

		$this->addBehavior('MultiTranslatable', ['fields' => [
            'title_1',
            'title_2',
            'title_3',
            'title_4',
            'title_5',
            'title_6',
            'title_7',
            'title_8',
            'title_9',
            'title_10',
            'text_1',
            'text_2',
            'text_3',
            'text_4',
            'text_5',
            'text_6',
            'text_7',
            'text_8',
            'text_9',
            'text_10',
            '_status'
        ]]);



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


        return $validator;
    }



    public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where(['ContentBlocks.title LIKE' => "%" . trim($key) . "%"]);
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
			
		// quando eliminiamo una riga dal database, andiamo ad eliminare le immagini e gli allegati associati
        // questo è necessario perche le relazioni tra immagini e ContentBlocks sono definite in modo diverso

        $relatedImages = $this->Images->findByModelAndRef($entity->model, $entity->ref)
            ->where([
                'scope LIKE' => "[{$entity->id}]%" 
            ])
            ->all();

        if ($relatedImages->count()){
            $this->Images->deleteMany($relatedImages);
        } 
        
        $relatedAttachments = $this->Attachments->findByModelAndRef($entity->model, $entity->ref)
            ->where([
                'scope LIKE' => "[{$entity->id}]%" 
            ])
            ->all();

        if ($relatedAttachments->count()){
            $this->Attachments->deleteMany($relatedAttachments);
        }   
				
	}


}
