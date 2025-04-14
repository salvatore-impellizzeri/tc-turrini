<?php
declare(strict_types=1);

namespace News\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;

/**
 * News Controller
 *
 * @property \News\Model\Table\NewsTable $News
 * @method \News\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsController extends AppController
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
