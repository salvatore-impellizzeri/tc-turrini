<?php
namespace App\Model\Behavior;

use ArrayObject;
use Cake\ORM\Behavior;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Datasource\FactoryLocator;
#[\AllowDynamicProperties]
class SortableBehavior extends Behavior
{

    protected array $_defaultConfig = [
        'scope' => null
    ];

    public function initialize(array $config): void
    {
        $this->config = array_merge($this->_defaultConfig, $config);
    }

    public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, $primary)
	{
		$order = $query->clause('order');

	    if ($order === null || !count($order)) {
	        $query->order( [ $query->getRepository()->getAlias() . '.position' => 'ASC'] );
	    }

	}

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {

        if ($entity->isNew()) {
            $source = $entity->getSource();
    		$model = FactoryLocator::get('Table')->get($source);

            $query = $model->find()->select(['max' => 'MAX(position)']);


            if (!empty($this->config['scope'])) {
                $scopes = is_array($this->config['scope']) ? $this->config['scope'] : [$this->config['scope']];
                $conditions = [];
                foreach ($scopes as $scope) {
                    if (!empty($entity->{$scope})) {
                        $conditions[$model->getAlias().'.'.$scope] = $entity->{$scope};
                    }
                }
                $query->where($conditions);
            }

            $max = $query->first();
            $entity->position = !empty($max->max) ? $max->max + 1 : 1;
        }

    }
}
?>
