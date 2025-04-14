<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\FactoryLocator;


class AttributesShopVariantsTable extends AppTable
{

    public function initialize(array $config): void
    {

		// configurazione tabella
        $this->setTable('attributes_shop_variants');


        $this->belongsTo('Attributes', [
            'className' => 'Shop.Attributes'
        ]);

        $this->belongsTo('ShopProductVariants', [
            'className' => 'Shop.ShopProductVariants'
        ]);

        $this->addBehavior('CounterCache', [
            'Attributes' => ['product_variant_count'],
            'ShopProductVariants' => ['attribute_count'],
        ]);

    }



}
