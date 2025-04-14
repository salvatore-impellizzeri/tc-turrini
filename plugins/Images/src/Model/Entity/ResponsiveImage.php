<?php
declare(strict_types=1);

namespace Images\Model\Entity;

use Cake\ORM\Entity;

/**
 * Image Entity
 *
 * @property int $id
 * @property int|null $ref_id
 * @property string|null $plugin
 * @property string|null $model
 * @property string|null $title
 * @property string|null $scope
 * @property string|null $type
 * @property bool $multiple
 * @property string|null $path
 * @property int|null $order
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Images\Model\Entity\Ref $ref
 */
class ResponsiveImage extends Entity
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
	
	// serve per rendere visibili i virtual fields anche quando si trasforma l'oggetto in array o JSON
	protected array $_virtual = ['path', 'backend_path'];
	
	protected function _getBackendPath()
    {
        return DS . BACKEND_IMAGES . $this->device . DS . $this->relative_path;
    }
	
	protected function _getPath()
    {
        return $this->device . DS . $this->relative_path;
    }
	
}
