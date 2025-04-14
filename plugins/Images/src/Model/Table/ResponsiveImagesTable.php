<?php
declare(strict_types=1);

namespace Images\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Images Model
 *
 *
 * @method \Images\Model\Entity\Image newEmptyEntity()
 * @method \Images\Model\Entity\Image newEntity(array $data, array $options = [])
 * @method \Images\Model\Entity\Image[] newEntities(array $data, array $options = [])
 * @method \Images\Model\Entity\Image get($primaryKey, $options = [])
 * @method \Images\Model\Entity\Image findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Images\Model\Entity\Image patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Images\Model\Entity\Image[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Images\Model\Entity\Image|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Images\Model\Entity\Image saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Images\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Images\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Images\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Images\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResponsiveImagesTable extends Table
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

        $this->setTable('responsive_images');
        $this->setDisplayField('filename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
		
		$this->belongsTo('Images', [
            'foreignKey' => 'image_id',
            'className' => 'Images.Images',
        ]);

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
	
	public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
				
		// quando modifichiamo il nome del file, andiamo ad eliminare i file e le versioni compresse associate
		if(!$entity->isNew() && $entity->isDirty('filename')) {
						
			$oldFileName = $entity->getOriginal('filename');
			$newFilename = $entity->get('filename');
			$ext = $entity->ext;
			
			if(strtolower($oldFileName) != strtolower($newFilename)) {
				// rinomina il file
				// ATTENZIONE: il finalName potrebbe non corrispondere al newFilename desiderato in caso di conflitti di file
				// per questo lo restituiamo e salviamo anche a database quello corrispondente al percorso fisico
				$finalName = $this->_renameOriginalFile($oldFileName, $newFilename, $ext);
				if(!empty($finalName)) {
					$entity->filename = $finalName;
					$entity->relative_path = $finalName . '.' . $ext;
					$this->_deleteCompressedFiles($oldFileName, $ext);
				}
				
			}
			
		}		
		
	}
	
	public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
			
		// quando eliminiamo una riga dal database, andiamo ad eliminare i file e le versioni compresse associate
		$fileName = $entity->filename;
		$ext = $entity->ext;

		$this->_deleteOriginalFile($entity->path);
		$this->_deleteCompressedFiles($entity->path, $entity->ext);
				
	}
	
	
	// recupera il file originale e lo rinomina evitando conflitti
	private function _renameOriginalFile($oldFileName, $newFileName, $ext){
		
		$ret = null;
		
		if(!empty($oldFileName) && !empty($newFileName) && !empty($ext)) {
						
			$baseUploadDir = UPLOADED_IMAGES;
			$dir = $baseUploadDir . 'mobile' . DS;			
			
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
		
		return $ret;

	}
	
	
	private function _deleteOriginalFile($path){    
		$filesToDelete = glob(UPLOADED_IMAGES . $path);
   
		if(!empty($filesToDelete)) {
			foreach($filesToDelete as $file) {
				if(is_file($file)) {
					unlink($file);
				}
			}	
		}
		
	}
	
	private function _deleteCompressedFiles($path, $ext){
		
		// svg
		$uncompressed = glob(MEDIA_IMAGES . $path);
		
		// altre immagini
        $fileName = substr(basename($path), 0, -1 * strlen('.'.$ext));
        $dirName = pathinfo($path, PATHINFO_DIRNAME);
        
		$compressed = glob(MEDIA_IMAGES . "{$path}.webp");
		$resized = glob(MEDIA_IMAGES . $dirName . "/{$fileName}@*.{$ext}.webp");	

        
		$filesToDelete = array_merge($uncompressed, $compressed, $resized);			
		
		if(!empty($filesToDelete)) {
			foreach($filesToDelete as $file) {
				if(is_file($file)) {
					unlink($file);
				}
			}	
		}
		
	}
	
}
