<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class ShopCategory extends Entity
{

     protected array $_accessible = [
         '*' => true
     ];

	// funzione per la generazione della url riscritta
	protected function _getSefUrl()
    {
		$path = '';
		$crumbs = FactoryLocator::get('Table')->get('Shop.ShopCategories')->find('path', for: $this->id)->all();

		foreach ($crumbs as $crumb) {
			$path .= '/' . Text::slug($crumb->title, ['replacement' => '-']);
		}
		$path = trim($path, '/');

		return $path;
    }

}
