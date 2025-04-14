<?php
declare(strict_types=1);

namespace Contacts\Model\Entity;

use Cake\ORM\Entity;


class Contact extends Entity
{

    protected array $_accessible = [
        '*' => true        
    ];
}
