<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;

class MagicController extends AppController
{
    public $tableless = true;
	
    /* may the force be with you -- funzioncina per il controllo antispam */
	public function yoda()
	{
		if($this->request->is('ajax')) {
			$this->set('token', $this->Session->read('token'));
		}		
	}
}
