<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\ORM\Query;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;

class AttributesShopProductsTable extends AppTable
{

    public function initialize(array $config): void
    {

		// configurazione tabella
        $this->setTable('attributes_shop_products');

        $this->belongsTo('Attributes', [
            'className' => 'Shop.Attributes'
        ]);

        $this->belongsTo('ShopProducts', [
            'className' => 'Shop.ShopProducts'
        ]);

        $this->addBehavior('CounterCache', [
            'Attributes' => ['product_count']
        ]);
    }


}
