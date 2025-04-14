<?php
declare(strict_types=1);

namespace ContentBlocks\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Event\EventInterface;
use Cake\Core\Plugin;


class ContentBlockTypesController extends AppController
{

    public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit'])) {
            $this->set('plugins', $this->buildPluginLinks());
        }
    }

    public function get()
    {
        $this->queryContain = [
            'Icon'
        ];

        parent::get();
    }


    public function edit($id = null)
    {
        $this->queryContain = [
            'Images' => 'ResponsiveImages'
        ];

        parent::edit($id);
    }


    protected function buildPluginLinks(){

        // i link sono statici perché non aveva senso renderli dinamici
        $links = [
            'Blog' => __dx('blog', 'admin', 'plugin name'),
            'CustomPages' => __dx('custom_pages', 'admin', 'plugin name'),
        ];


        return $links;
    }
}
