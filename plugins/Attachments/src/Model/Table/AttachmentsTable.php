<?php
declare(strict_types=1);

namespace Attachments\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AttachmentsTable extends Table
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

        $this->setTable('attachments');
        $this->setDisplayField('filename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');	

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
            ->scalar('filename')
            ->maxLength('filename', 255)
			->notEmptyString('filename');      

        $validator            
            ->notEmptyString('path');

        return $validator;
    }
	
	public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
				
		// quando modifichiamo il nome del file, andiamo ad eliminare i file e le versioni compresse associate
		if(!$entity->isNew() && $entity->isDirty('filename')) {
						
			$oldFileName = $entity->getOriginal('filename');
			$newFilename = $entity->get('filename');
			$ext = $entity->ext;
			
			if(strtolower($oldFileName) != strtolower($newFilename)) {
				// rinomina il file sia nella versione desktop che mobile perché i due file rappresentano lo stesso oggetto 			
				$this->_renameOriginalFile($oldFileName, $newFilename, $ext);				
			}
			
		}		
		
	}
	
	public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
			
		// quando eliminiamo una riga dal database, andiamo ad eliminare i file e le versioni compresse associate
		$fileName = $entity->filename;
		$ext = $entity->ext;

		$this->_deleteOriginalFile($fileName, $ext);		
				
	}
	
	
	// recupera il file originale e lo rinomina evitando conflitti
	private function _renameOriginalFile($oldFileName, $newFileName, $ext){
		
		$ret = null;
		
		if(!empty($oldFileName) && !empty($newFileName) && !empty($ext)) {
						
			$baseUploadDir = UPLOADED_FILES;			
			$dir = $baseUploadDir;
			
			$finalName = $newFileName;
			
			$newPath = $dir . $newFileName . '.' . $ext;	
			$conflict = true;
			
			if(!is_file($newPath)) {
				$conflict = false;
			} else {
				$index = 1;				
				// se il file esiste proviamo a salvarlo con un suffisso _index
				while($conflict && $index <= 50) {
					$finalName = $newFileName . '_' . $index;
					$newPath = $dir . $finalName . '.' . $ext;
					if(!is_file($newPath)) {
						$conflict = false;
					}
					$index++;
				}

				// se ancora dopo 50 tentativi non si è risolto il conflitto, associamo un suffisso univoco
				if($conflict) {
					$bytes = random_bytes(6);
					$suffix = bin2hex($bytes);
					$finalName = $newFileName . '_' . $suffix;
					$newPath = $dir . $finalName . '.' . $ext;
				}					
			}
			
			rename($dir . $oldFileName . '.' . $ext, $dir . $finalName . '.' . $ext);
			$ret = $finalName;
			
		}

	}
	
	
	private function _deleteOriginalFile($fileName, $ext){

		$filesToDelete = glob(UPLOADED_FILES . "{$fileName}.{$ext}");		
		
		if(!empty($filesToDelete)) {
			foreach($filesToDelete as $file) {
				if(is_file($file)) {
					unlink($file);
				}
			}	
		}
		
	}

	
}
