<?php
declare(strict_types=1);

namespace Questions\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;


class Question extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];

}
