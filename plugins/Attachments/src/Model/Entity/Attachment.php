<?php
declare(strict_types=1);

namespace Attachments\Model\Entity;

use Cake\ORM\Entity;


class Attachment extends Entity
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
	protected array $_virtual = ['path'];
	
	protected function _getPath()
    {
        return DS . FILES . $this->filename . ".". $this->ext;
    }
	
}
