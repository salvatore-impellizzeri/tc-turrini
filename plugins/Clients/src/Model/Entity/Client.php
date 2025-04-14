<?php
declare(strict_types=1);

namespace Clients\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Utility\Text;


class Client extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];


    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

}
