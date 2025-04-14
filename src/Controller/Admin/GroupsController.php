<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;

class GroupsController extends AppController
{
    public function beforeFilter(EventInterface $event)
	{
        // solo superadmin ha accesso ai gruppi
        if (empty($this->loggedUser['group_id']) || $this->loggedUser['group_id'] != 1){
			throw new NotFoundException(__('Not found'));
		}
	}
}
