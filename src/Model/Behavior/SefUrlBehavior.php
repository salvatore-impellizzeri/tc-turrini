<?php

declare(strict_types=1);

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\Datasource\FactoryLocator;
use Cake\I18n\I18n;
#[\AllowDynamicProperties]
class SefUrlBehavior extends Behavior
{

	public function initialize(array $config): void
	{
        $this->SefUrls = FactoryLocator::get('Table')->get('SefUrls.SefUrls');
	}


	protected function _saveUrl(EntityInterface $entity, $rewrittenUrl = null, $locale = null)
    {

		// la struttura dell'elemento che stiamo salvando (ad esempio User o Articles.Articles)
		$source = $entity->getSource();
		$model = FactoryLocator::get('Table')->get($source);

		// nel caso ci sia il plugin, lo recuperiamo
		if(strpos($source, ".") !== false) {
            list($pluginName, $modelName) = explode('.', $source);
        } else {
            $modelName = $source;
            $pluginName = null;
        }

		$controllerName = $modelName;

		$originalUrl = Inflector::dasherize($controllerName) . '/view/' . $entity->id;
		$param = $entity->id;
		$rewrittenUrl = strtolower($rewrittenUrl);


		// controlliamo se l'url originale è associata ad un url custom immodificabile
		$conditions = [
			'original' => $originalUrl,
			'locale' => $locale,
			'custom' => true
		];

		$lockedUrl = $this->SefUrls->exists($conditions);


		// se l'url originale non è associato ad un url custom procediamo, altrimenti va mantenuta la url custom
		if(!$lockedUrl){

			// controlliamo se l'url riscritta esiste già a database
			$conditions = [
				'rewritten' => $rewrittenUrl,
				'locale' => $locale
			];

			$alreadyExists = $this->SefUrls->exists($conditions);

			if(!$alreadyExists) {

				$sefUrl = $this->SefUrls->newEntity([
					'plugin' => $pluginName,
					'controller' => $controllerName,
					'action' => 'view',
					'original' => $originalUrl,
					'rewritten' => $rewrittenUrl,
					'param' => $param,
					'code' => 200,
					'locale' => $locale,
					'custom' => false
				]);

				if(empty($sefUrl->getErrors())) {
					$this->SefUrls->save($sefUrl);
				}

				// impostiamo le url 200 precedenti come redirect 301
				$this->SefUrls->updateAll(
					[
						'code' => 301
					],
					[
						'code' => 200,
						'original' => $originalUrl,
						'rewritten !=' => $rewrittenUrl,
						'locale' => $locale
					]
				);

			} else {

				// controlliamo se l'url già esistente è associata ad un altro elemento o al nostro
				$conditions = [
					'original !=' => $originalUrl,
					'rewritten' => $rewrittenUrl,
					'locale' => $locale
				];

				$associatedWithDifferentUrl = $this->SefUrls->exists($conditions);

				if($associatedWithDifferentUrl) {
					
					// controlliamo se l'url che contiene l'id è già presente o se va creata
					$conditions = [
						'rewritten' => "{$rewrittenUrl}-{$entity->id}",
						'locale' => $locale
					];
					
					$urlWithId = $this->SefUrls->exists($conditions);
					
					if(!$urlWithId) {
						
						$sefUrl = $this->SefUrls->newEntity([
							'plugin' => $pluginName,
							'controller' => $controllerName,
							'action' => 'view',
							'original' => $originalUrl,
							'rewritten' => "{$rewrittenUrl}-{$entity->id}",
							'param' => $param,
							'code' => 200,
							'locale' => $locale,
							'custom' => false
						]);

						if(empty($sefUrl->getErrors())) {
							$this->SefUrls->save($sefUrl);
						}

						// impostiamo le url 200 precedenti come redirect 301
						$this->SefUrls->updateAll(
							[
								'code' => 301
							],
							[
								'code' => 200,
								'original' => $originalUrl,
								'locale' => $locale,
								'rewritten !=' => "{$rewrittenUrl}-{$entity->id}"
							]
						);

						// eliminiamo eventuali url 301 duplicate residue
						$this->SefUrls->deleteAll(
							[
								'code' => 301,
								'original' => $originalUrl,
								'locale' => $locale,
								'rewritten' => "{$rewrittenUrl}-{$entity->id}"
							]
						);
					
					} else {

						// controlliamo se l'url che contiene l'id già esistente e associata al nostro elemento ha codice 301
						$conditions = [
							'original' => $originalUrl,
							'rewritten' => "{$rewrittenUrl}-{$entity->id}",
							'code' => 301,
							'locale' => $locale
						];

						if($this->SefUrls->exists($conditions)){

							// settiamo la url che contiene l'id come 200
							$this->SefUrls->updateAll(
								[
									'code' => 200
								],
								[
									'code' => 301,
									'original' => $originalUrl,
									'rewritten' => "{$rewrittenUrl}-{$entity->id}",
									'locale' => $locale
								]
							);

							// impostiamo le url 200 precedenti come redirect 301
							$this->SefUrls->updateAll(
								[
									'code' => 301
								],
								[
									'code' => 200,
									'original' => $originalUrl,
									'rewritten !=' => "{$rewrittenUrl}-{$entity->id}",
									'locale' => $locale
								]
							);
						}

					}

				} else {

					// controlliamo se l'url già esistente e associata al nostro elemento ha codice 301
					$conditions = [
						'original' => $originalUrl,
						'rewritten' => $rewrittenUrl,
						'code' => 301,
						'locale' => $locale
					];

					if($this->SefUrls->exists($conditions)){

						// settiamo la url come 200
						$this->SefUrls->updateAll(
							[
								'code' => 200
							],
							[
								'code' => 301,
								'original' => $originalUrl,
								'rewritten' => $rewrittenUrl,
								'locale' => $locale
							]
						);

						// impostiamo le url 200 precedenti come redirect 301
						$this->SefUrls->updateAll(
							[
								'code' => 301
							],
							[
								'code' => 200,
								'original' => $originalUrl,
								'rewritten !=' => $rewrittenUrl,
								'locale' => $locale
							]
						);
					}

				}

			}

		}

    }


    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {

		$locale = $entity->_locale ?? I18n::getLocale();
		$sefUrl = $entity->get('sef_url');

		if(!empty($sefUrl)) {
			$this->_saveUrl($entity, $sefUrl, $locale);
		}

    }

	public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options)
	{

		// la struttura dell'elemento che stiamo salvando (ad esempio User o Articles.Articles)
		$source = $entity->getSource();
		$model = FactoryLocator::get('Table')->get($source);

		// nel caso ci sia il plugin, lo recuperiamo
		if(strpos($source, ".") !== false) {
            list($pluginName, $modelName) = explode('.', $source);
        } else {
            $modelName = $source;
            $pluginName = null;
        }

		$controllerName = $modelName;

		if($controllerName == 'ProductVariants') {
			$originalUrl = 'products/view/' . $entity->product_id . '/' . $entity->id;
		} else {
			$originalUrl = Inflector::dasherize($controllerName) . '/view/' . $entity->id;
		}

		$this->SefUrls->deleteAll(
			[
				'original' => $originalUrl
			]
		);

		return true;
	}



}
