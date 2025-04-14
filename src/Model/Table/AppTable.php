<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Datasource\FactoryLocator;

class AppTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Images', ['className' => 'Images.Images'])
			->setForeignKey('ref')
			->setDependent(true)
			->setCascadeCallbacks(true)
			->setConditions(['Images.model' => $this->getAlias()]);

        $this->hasMany('Attachments', ['className' => 'Attachments.Attachments'])
			->setForeignKey('ref')
			->setDependent(true)
			->setCascadeCallbacks(true)
			->setConditions(['Attachments.model' => $this->getAlias()]);

    }
	
	public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{	
				
		$imagesTable = FactoryLocator::get('Table')->get('Images.Images');
		$responsiveImagesTable = FactoryLocator::get('Table')->get('Images.ResponsiveImages');	
		$attachmentsTable = FactoryLocator::get('Table')->get('Attachments.Attachments');
		
		$imagesTable->deleteAll(['relative_path IS NULL']);
		$responsiveImagesTable->deleteAll(['relative_path IS NULL']);
		$attachmentsTable->deleteAll(['relative_path IS NULL']);
		
	}
	
	public function findFiltered(Query $query, array $options)
    {
        $key = $options['key'];
        return $query->where([$this->getAlias() . '.title LIKE' => "%" . trim($key) . "%"]);
    }
}
?>
