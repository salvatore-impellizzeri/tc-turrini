<?php
declare(strict_types=1);

namespace Policies\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;


class Policy extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];
	
	// funzione per la generazione della url riscritta
	protected function _getSefUrl()
    {        
		return Text::slug(__dx('policies', 'admin', 'sef prefix'), ['replacement' => '-']) . '/' . Text::slug($this->title, ['replacement' => '-']);
    }
	
	
}
