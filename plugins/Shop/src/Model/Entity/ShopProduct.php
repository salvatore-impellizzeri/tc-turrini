<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class ShopProduct extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];

    protected array $_virtual = [
        'admin_title'
    ];
	
	protected function _getSefUrl()
    {
		return Text::slug($this->title ?? '', ['replacement' => '-']);						
    }

    protected function _getAdminTitle()
    {
        return !empty($this->draft) ? __dx('shop', 'admin', 'draft') : $this->title;
    }

}
