<?php
declare(strict_types=1);

namespace Products\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class ProductCategory extends Entity
{

     protected array $_accessible = [
         '*' => true
     ];

	// funzione per la generazione della url riscritta
	protected function _getSefUrl()
    {
		$path = '';
		$crumbs = FactoryLocator::get('Table')->get('Products.ProductCategories')->find('path', for: $this->id)->all();

		foreach ($crumbs as $crumb) {
			$path .= '/' . Text::slug($crumb->title, ['replacement' => '-']);
		}
		$path = trim($path, '/');

		return $path;
    }

}
