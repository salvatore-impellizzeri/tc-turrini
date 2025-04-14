<?php
declare(strict_types=1);

namespace Services\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $text
 * @property string|null $seotitle
 * @property string|null $seokeywords
 * @property string|null $seodescription
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Service extends Entity
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

	// funzione per la generazione della url riscritta
	protected function _getSefUrl()
    {
		return Text::slug(__dx('services', 'admin', 'sef prefix'), ['replacement' => '-']) . '/' . Text::slug($this->title, ['replacement' => '-']);
    }


}
