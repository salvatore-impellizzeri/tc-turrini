<?php
declare(strict_types=1);

namespace Snippets\Controller;

use App\Controller\AppController;


class SnippetsController extends AppController
{

	public function view($id = null)
	{

		/*$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];*/
		parent::view($id);

	}

}
