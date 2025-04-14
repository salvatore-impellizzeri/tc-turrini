<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class AttributeType extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];

    protected array $_virtual = ['is_color'];


    protected function _getIsColor()
    {
        return !empty($this->id) && $this->id == 2;
    }


}
