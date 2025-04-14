<?php
declare(strict_types=1);

namespace Contacts\Model\Entity;

use Cake\ORM\Entity;


class ContactField extends Entity
{

    protected array $_accessible = [
        '*' => true        
    ];
}
