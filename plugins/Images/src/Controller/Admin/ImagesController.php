<?php
declare(strict_types=1);

namespace Images\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\I18n;

class ImagesController extends AppController
{

	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Uploader');
	}

	public function uppy()
    {

		$image = $this->Images->newEmptyEntity();

        if ($this->request->is('post')) {

			$data = $this->request->getData();

			if(!empty($data['finalWidth'])){
				$data['finalWidth'] = trim($data['finalWidth']);
			} else {
				$data['finalWidth'] = null;
			}

			if(!empty($data['finalHeight'])){
				$data['finalHeight'] = trim($data['finalHeight']);
			} else {
				$data['finalHeight'] = null;
			}

			$device = 'desktop';

			if(!empty($data['file'])) {
								
				$uploadedImage = $data['file'];			

				$path = $this->Uploader->uploadImage($uploadedImage, $device, $data['filename']);
				if(!empty($path['wwwPath']) && !empty($data['cropData']) && (!empty($data['finalWidth']) || !empty($data['finalHeight']))) {
					$this->Uploader->cropper($path['wwwPath'], $data['cropData'], $data['finalWidth'], $data['finalHeight']);
				}

				$data['relative_path'] = $path['dbPath'];
				$data['ext'] = $path['ext'];
				$data['filename'] = $path['fileName'];
				$data['device'] = $device;
				$data['multiple'] = $data['multiple'] ?? 0;


				// recupera le dimensioni dal file
				list($width, $height, $type) = getimagesize($path['wwwPath']);

				$data['width'] = $data['finalWidth'] ?? $width;
				$data['height'] = $data['finalHeight'] ?? $height;

				$image = $this->Images->patchEntity($image, $data);
				if ($this->Images->save($image)) {
					$savedImage = $this->Images->get($image->id);
					$this->set('savedImage', $savedImage);
					$this->viewBuilder()->setOption('serialize', 'savedImage');
				}

			}
        }

    }


    public function edit($id = null)
    {

		$response = [];
		
		if(ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
		    $this->Images->setLocale(ACTIVE_ADMIN_LANGUAGE);			
        }
		
		$image = $this->Images->get($id);

        if(!empty($image)){

            if (!empty($this->request->getData())) {
                $this->Images->patchEntity($image, $this->request->getData());
                $this->Images->save($image);

				$response['saved'] = true;
            } else {
				if($this->request->getQuery('lang')){
					$response['html'] = "<form action='/admin/images/edit/{$id}.json?lang=" . $this->request->getQuery('lang') . "' method='POST'>
										<input type='hidden' name='id' value='{$id}' />										
										<label for='title'>Descrizione immagine (alt)</label>
										<input type='text' name='title' value='" . h($image['title']) . "' />";
				} else {
					$response['html'] = "<form action='/admin/images/edit/{$id}.json' method='POST'>
										<input type='hidden' name='id' value='{$id}' />
										<label for='filename'>Nome file</label>
										<input type='text' name='filename' value='" . h($image['filename']) . "' />
										<label for='title'>Descrizione immagine (alt)</label>
										<input type='text' name='title' value='" . h($image['title']) . "' />";	
				}
				if ($this->request->getQuery('video')){
					$response['html'] .= "<label for='video'>Video collegato (URL Vimeo)</label>
										  <input type='text' name='video' value='" . h($image['video']) . "' />";
				}

				if ($this->request->getQuery('videoMobile')){
					$response['html'] .= "<label for='video'>Video collegato mobile (URL Vimeo)</label>
										  <input type='text' name='video_mobile' value='" . h($image['video_mobile']) . "' />";
				}
										
				$response['html'] .= "</form>";

			}

		}

		$response['file'] = $image;

		$this->set('response', $response);
		$this->viewBuilder()->setOption('serialize', 'response');

    }

    public function delete($id = null)
    {
        $image = $this->Images->get($id);
        $this->Images->delete($image);
		$this->set('deleted', true);
		$this->viewBuilder()->setOption('serialize', 'deleted');

    }
}
