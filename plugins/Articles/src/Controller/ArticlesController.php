<?php
declare(strict_types=1);

namespace Articles\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Articles Controller
 *
 * @property \Articles\Model\Table\ArticlesTable $Articles
 * @method \Articles\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
	
	public function view($id = null)
	{
		
		$this->queryContain = [
			'Images' => 'ResponsiveImages',
			'Attachments'
		];
		parent::view($id);			

	}
	
	public function testsibcomponent() {
		//$result = $this->Sib->sendmail([['email' => 'webmaster@webmotion.it', 'name' => 'Nome Destinatario']], Configure::read('Sib.emails.benvenuto.it'));
		//$result = $this->Sib->sendcontact('webmaster@webmotion.it', [10], ['NOME' => 'Andrea update', 'COGNOME' => 'Tosi']);
		//$result = $this->Sib->removefromlist(['webmaster@webmotion.it'], 10);
		//$result = $this->Sib->sibcontactdetails('webmaster@webmotion.it');
		//$result = $this->Sib->updatecontact('webmaster@webmotion.it', ['NOME' => 'Webmaster']);
		$result = $this->Sib->sendmail([['email' => 'webmaster@webmotion.it', 'name' => 'Nome Destinatario']], Configure::read('Sib.emails.benvenuto.it'), [], ['attachment' => [['url' => 'https://gunter.webmotion.it/Fattura24/test.pdf', 'name' => 'test.pdf']]]);
		
		debug($result);
		die('fatto');
	}
	
	public function testfattura24() {
		//$result = $this->Fattura24->createOrderInvoice(126);
		//$result = $this->Fattura24->createSubscriptionOrderInvoice(26);
		$result = $this->Fattura24->getOrderDocument(1);
		//$result = $this->Fattura24->getSubscriptionOrderDocument(24);
		
		debug($result);
		die('fatto');
	}
	
}
