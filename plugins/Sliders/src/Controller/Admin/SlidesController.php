<?php
declare(strict_types=1);

namespace Sliders\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\MethodNotAllowedException;


class SlidesController extends AppController
{


    public function add()
    {
        $sliderId = $this->request->getQuery('slider_id');
        if (empty($sliderId)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $slider = $this->Slides->Sliders->findById($sliderId)->firstOrFail();

        if (empty($slider)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('slider_id')];
        }

        $this->set(compact('slider'));
		parent::add();
    }

    public function edit($id = null)
    {

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('slider_id')];
        }

        $this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Sliders'
		];

        parent::edit($id);
    }

	public function list($id = null)
    {
        $slider = $this->Slides->Sliders->findById($id)->firstOrFail();

        $this->set(compact('slider'));
    }


}
