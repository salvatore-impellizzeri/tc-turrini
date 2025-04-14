<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;
use Cake\Core\Configure;

class ShopProductVariant extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];

    protected array $_virtual = [
        'display_price', //prezzo da mostrare
        'display_discounted_price', //prezzo scontato da mostrare
    ];
	
	protected function _getSefUrl()
    {
		return Text::slug(__dx('shop', 'admin', 'sef prefix'), ['replacement' => '-']) . '/' . Text::slug($this->title, ['replacement' => '-']);				
    }

    protected function _getDisplayPrice()
    {
        return Configure::read('Shop.vatIncuded') ? $this->price : $this->price_net;
    }

    protected function _getDisplayDiscountedPrice()
    {
        return Configure::read('Shop.vatIncuded') ? $this->discounted_price : $this->discounted_price_net;
    }

}
