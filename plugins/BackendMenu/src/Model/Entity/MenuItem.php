<?php
declare(strict_types=1);

namespace BackendMenu\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;


class MenuItem extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];


}
