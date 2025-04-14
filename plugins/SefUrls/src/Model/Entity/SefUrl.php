<?php
declare(strict_types=1);

namespace SefUrls\Model\Entity;

use Cake\ORM\Entity;

/**
 * SefUrl Entity
 *
 * @property int $id
 * @property string $original
 * @property string $rewritten
 * @property bool $custom
 * @property int $code
 * @property string $plugin
 * @property string $controller
 * @property string $action
 * @property string $param
 * @property string $locale
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class SefUrl extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        '*' => true
    ];
}
