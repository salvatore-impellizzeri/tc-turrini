<?php
declare(strict_types=1);

namespace ContentBlocks\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;


class ContentBlock extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];


}
