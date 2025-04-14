<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class ShopProductVariantsController extends AppController
{


	public function get()
    {
        $this->queryContain = ['Attributes', 'Preview'];


        // se passo il parametro productId mi recupera solo quelli collegati al prodotto
        if (!empty($this->request->getQuery('productId'))){
            $productId = $this->request->getQuery('productId');
            $this->queryConditions = ['ShopProductVariants.shop_product_id' => $productId];
        }

		parent::get();
	}

    function edit($id = null)
	{
        $this->queryContain = [
            'ShopProducts' => [
                'VariantAttributeGroups' => [
                    'Attributes'
                ]
            ],
            'Images' => 'ResponsiveImages',
            'Attributes'
        ];

        $this->actionRedirect = [
            'eval' => "['controller' => 'ShopProducts', 'action' => 'edit', \$item->shop_product_id];"
        ];
        
        parent::edit($id);
    }

    public function test(){
        $this->ShopProductVariants->updateTitle(27);
    }
}
