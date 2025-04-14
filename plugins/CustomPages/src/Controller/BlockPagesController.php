<?php
declare(strict_types=1);

namespace CustomPages\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class BlockPagesController extends AppController
{


    public function view($id = null, $test = true){
        $this->queryContain = [
            'Images' => 'ResponsiveImages',
            'Attachments',
            'ContentBlocks' => 'ContentBlockTypes' 
        ];
        parent::view($id);
    }




}
