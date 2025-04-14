<?php
declare(strict_types=1);

namespace Menu\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;


class Menu extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];


}
