<?php
declare(strict_types=1);

namespace CustomPages\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;


class CustomPage extends Entity
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
		return Text::slug($this->title, ['replacement' => '-']);
    }


}
