<?php
declare(strict_types=1);

namespace Sliders\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;


class Slider extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];

}
