<?php
declare(strict_types=1);

namespace Products\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;

class Product extends Entity
{

    protected array $_accessible = [
        '*' => true
    ];
	
	protected function _getSefUrl()
    {
		$path = '';

        if(!empty($this->product_category_id)) {
            $crumbs = FactoryLocator::get('Table')->get('Products.ProductCategories')->find('path', for: $this->product_category_id)->all();

            foreach ($crumbs as $crumb) {
                $path .= '/' . Text::slug($crumb->title, ['replacement' => '-']);
            }
        }

        $path .= '/' . Text::slug($this->title, ['replacement' => '-']);
        $path = trim($path, '/');
		return $path;
						
    }

}
