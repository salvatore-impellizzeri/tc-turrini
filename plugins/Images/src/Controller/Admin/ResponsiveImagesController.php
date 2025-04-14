<?php
declare(strict_types=1);

namespace Images\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\I18n;

class ResponsiveImagesController extends AppController
{
	
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Uploader');
	}

	
	public function uppy()
    {
				
		$responsiveImage = $this->ResponsiveImages->newEmptyEntity();		
						
        if ($this->request->is('post')) {			
			
			$data = $this->request->getData();			
			$data['finalWidth'] = trim($data['finalWidth']) ?? null;
			$data['finalHeight'] = trim($data['finalHeight']) ?? null;			
			
			// recupero l'immagine principale associata a questa versione responsive
			$mainImage = $this->ResponsiveImages->Images->find()
                ->where(['same' => $data['same']]);
			
			$mainImageId = $mainImage->first()->get('id');
			
			if(!empty($mainImageId)) {
				$data['image_id'] = $mainImageId;
							
				$device = 'mobile';
										
				if(!empty($data['device'])) {
					$device = $data['device'];
				}
							
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
																		
					$responsiveImage = $this->ResponsiveImages->patchEntity($responsiveImage, $data);				
					if ($this->ResponsiveImages->save($responsiveImage)) {					
						$savedImage = $this->ResponsiveImages->get($responsiveImage->id);
						$this->set('savedImage', $savedImage);					
						$this->viewBuilder()->setOption('serialize', 'savedImage');					
					}				
					
				}
			}			
        }        
		
    }
	
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $image = $this->ResponsiveImages->get($id);
        if ($this->ResponsiveImages->delete($image)) {
            $this->Flash->success(__('The image has been deleted.'));
        } else {
            $this->Flash->error(__('The image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
