<?php
declare(strict_types=1);

namespace Cookies\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;


class Cookie extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];
	
}
