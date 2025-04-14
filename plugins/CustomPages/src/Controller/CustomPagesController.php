<?php
declare(strict_types=1);

namespace CustomPages\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class CustomPagesController extends AppController
{


    public function view($id = null, $test = true){
        $this->queryContain = [
            'Images' => 'ResponsiveImages',
            'Attachments'
        ];
        parent::view($id);
    }


    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if ($this->request->getParam('action') == 'view') {
            $item = $this->viewBuilder()->getVar('item');
            $this->viewBuilder()->setTemplate($item->layout);
        }

    }

}
