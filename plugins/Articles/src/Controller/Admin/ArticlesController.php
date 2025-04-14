<?php
declare(strict_types=1);

namespace Articles\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;

/**
 * Articles Controller
 *
 * @property \Articles\Model\Table\ArticlesTable $Articles
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

	
	public function edit($id = null)
	{
		
		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];
		parent::edit($id);			

	}

}
