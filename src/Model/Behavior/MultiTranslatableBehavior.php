<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior\Translate;
use Cake\Utility\Inflector;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

/**
 * This behavior provides a way to translate dynamic data by keeping translations
 * in a separate table linked to the original record from another one. Translated
 * fields can be configured to override those in the main table when fetched or
 * put aside into another property for the same entity.
 *
 * If you wish to override fields, you need to call the `locale` method in this
 * behavior for setting the language you want to fetch from the translations table.
 *
 * If you want to bring all or certain languages for each of the fetched records,
 * you can use the custom `translations` finders that is exposed to the table.
 */
class MultiTranslatableBehavior extends \Cake\ORM\Behavior\TranslateBehavior
{

    public $isAdmin = false;
	public $translatableFields = ['_status'];
	public $languages = null;

    public function initialize(array $config): void
    {
        parent::initialize($config);

		$this->translatableFields = array_merge($this->translatableFields, $config['fields']);

		$this->languages = Configure::read('Setup.languages');
		$prefix = (Router::getRequest() != null && !empty(Router::getRequest()->getParam('prefix'))) ? strtolower(Router::getRequest()->getParam('prefix')) : '';
		if($prefix == 'admin') $this->isAdmin = true;


		// se il debug è attivo, andiamo a verificare l'esistenza della tabelle di traduzione
		// ed eventualmente la creiamo mantenendo solo i campi traducibili
		$debug = Configure::read('debug');
		if(!empty($debug)) {

			// recupera la tabella di traduzione e quella originale
			$translationTable = $this->getStrategy()->getTranslationTable()->getTable();
			$originalTable = Inflector::tableize($config['referenceName']);

			// si connette al database e verifica l'esistenza della tabella
			$connection = ConnectionManager::get('default');
			$translationTableExists = $connection->execute("SHOW TABLES LIKE ?", [$translationTable])->fetchAll('assoc');

			if(empty($translationTableExists)) {


				// se la tabella non esiste, la crea copiando la tabella originale
				// e successivamente rimuove dalla tabella di traduzione tutti i campi non traducibili
				// mantenendo i campi id e modified
				$connection->execute("CREATE TABLE $translationTable LIKE $originalTable");
				// aggiunge la colonna per il locale
				$connection->execute("ALTER TABLE $translationTable ADD `locale` VARCHAR(2) NULL AFTER `id`;");
				// aggiunge la colonna per lo status traduzione
				$connection->execute("ALTER TABLE $translationTable ADD `_status` INT(11) NOT NULL DEFAULT '0';");
				
				// rimuove AUTO INCREMENT su id altrimenti non si potrebbe attivare più di una lingua
				$connection->execute("ALTER TABLE $translationTable CHANGE `id` `id` INT(11) NOT NULL");
				
				// trasforma la chiave primaria sulla tabella delle traduzioni nella combo (id, locale) altrimenti non si potrebbe attivare più di una lingua
				$connection->execute("ALTER TABLE $translationTable DROP PRIMARY KEY");				
				$connection->execute("ALTER TABLE $translationTable ADD PRIMARY KEY (`id`, `locale`); ");


				$translatableFields = $config['fields'];

				$columns = $this->getStrategy()->getTranslationTable()->getSchema()->columns();
				$columnsToKeep = ['id', 'locale'];
				$columnsToKeep = array_merge($columnsToKeep, $translatableFields);

				$columnsToRemove = array_diff($columns, $columnsToKeep);
				if(!empty($columnsToRemove)) {
					$dropPlaceholder = implode(', DROP ', $columnsToRemove);
					$connection->execute("ALTER TABLE $translationTable DROP $dropPlaceholder");
				}

			}
		}

    }

	public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {

		// se stiamo salvando una traduzione, aggiorniamo il campo _status, ma non chiamiamo i callback
		if($this->isAdmin && ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE) {
			$entity->set('_status', 1);
		}

		// aggiorniamo lo status delle traduzioni se campi traducibili dell'originale sono stati modificati
		if($this->isAdmin && ACTIVE_ADMIN_LANGUAGE == DEFAULT_LANGUAGE) {

			$dirtyFields = $entity->getDirty();

			// se almeno uno dei campi modificati è un campo traducibile, aggiorno lo status delle eventuali traduzioni presenti
			if(array_intersect($dirtyFields, $this->translatableFields)) {
				if(!($entity->isNew()) && !empty($this->languages))
				{
					$translationTable = $this->getStrategy()->getTranslationTable()->getTable();
					$connection = ConnectionManager::get('default');
					$connection->execute("UPDATE $translationTable SET `_status` = 2 where id = {$entity->id} and `_status` = 1");
				}
			}

		}

		parent::beforeSave($event, $entity, $options);

	}

}
