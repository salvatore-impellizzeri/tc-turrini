<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class MagicController extends AppController
{
    public $tableless = true;
	
	
    public function getFlash()
    {
		
    }
	
	public function setSubmenuStatus($submenuID, $status)
    {
		$this->Session->write("SubmenuStatus.$submenuID", $status);
		die;
    }
	
}
