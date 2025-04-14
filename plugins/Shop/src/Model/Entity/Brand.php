<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class Brand extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];
	
	protected function _getSefUrl()
    {
		return Text::slug($this->title ?? '', ['replacement' => '-']);						
    }

}
