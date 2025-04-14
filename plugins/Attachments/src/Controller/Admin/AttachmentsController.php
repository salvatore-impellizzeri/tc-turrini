<?php
declare(strict_types=1);

namespace Attachments\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\I18n\I18n;

class AttachmentsController extends AppController
{
	
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Uploader');
	
	}
	
	public function uppy()
    {
				
		$attachment = $this->Attachments->newEmptyEntity();		
						
        if ($this->request->is('post')) {			
			
			$data = $this->request->getData();			
									
			if(!empty($data['file'])) {
									
				$uploadedFile = $data['file'];			
								
				$path = $this->Uploader->uploadFile($uploadedFile, $data['filename']);
				
				$data['relative_path'] = $path['dbPath'];
				$data['ext'] = $path['ext'];
				$data['filename'] = $path['fileName'];				
				$data['multiple'] = $data['multiple'] ?? 0;					
																	
				$attachment = $this->Attachments->patchEntity($attachment, $data);				
				if ($this->Attachments->save($attachment)) {					
					$savedAttachment = $this->Attachments->get($attachment->id);
					$this->set('savedAttachment', $savedAttachment);					
					$this->viewBuilder()->setOption('serialize', 'savedAttachment');					
				}				
				
			}			
        }        
		
    }
	
	public function edit($id = null)
    {        
        
		$response = [];
		$file = $this->Attachments->get($id);
				        
        if(!empty($file)){

            if (!empty($this->request->getData())) {
                $this->Attachments->patchEntity($file, $this->request->getData());
                $this->Attachments->save($file);
				
				$response['saved'] = true;
            } else {
				$response['html'] = "<form action='/admin/attachments/edit/{$id}.json' method='POST'>
										<input type='hidden' name='id' value='{$id}' />
										<label for='filename'>Nome file</label>
										<input type='text' name='filename' value='" . h($file['filename']) . "' />										
									 </form>";
			}

		}		
        
		$response['file'] = $file;
		
		$this->set('response', $response);
		$this->viewBuilder()->setOption('serialize', 'response');

    }

    public function delete($id = null)
    {        
        $attachment = $this->Attachments->get($id);
        $this->Attachments->delete($attachment);
		$this->set('deleted', true);
		$this->viewBuilder()->setOption('serialize', 'deleted');

    }
}
