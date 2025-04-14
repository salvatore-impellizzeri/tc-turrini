<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Sepia\PoParser\SourceHandler\FileSystem;
use Sepia\PoParser\Parser;
use Sepia\PoParser\Catalog;
use Sepia\PoParser\PoCompiler;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Plugin;
use Cake\Http\Exception\ForbiddenException;


class TranslateController extends AppController
{

	public $tableless = true;


	public function index()
	{
		$poFiles = [];

		$defaultLanguage = Configure::read('Setup.default_language');
		$globalPoPath = RESOURCES . 'locales' . DS . $defaultLanguage . DS;

		if (!file_exists($globalPoPath)) {
			mkdir($globalPoPath, 0755, true);
		}

		$globalPoDir = scandir($globalPoPath);

		if (!empty($globalPoDir)) {
			$globalPo = array_filter($globalPoDir, function ($file) {
				return pathinfo($file, PATHINFO_EXTENSION) == 'po';
			});
		}

		if (!empty($globalPo)) {
			foreach ($globalPo as $poFile) {
				if ($poFile == 'admin.po') continue; //non considero il file admin.po
				if ($poFile == 'cake.po') continue; //non considero il file cake.po
				$poFiles[] = [
					'file' => basename($poFile, '.po'),
					'plugin' => null
				];
			}
		}

		// recupero l'elenco di tutti i plugin attivi
		$plugins = Plugin::loaded();
		if (!empty($plugins)) {
			foreach ($plugins as $pluginName) {

				$pluginPath = Plugin::path($pluginName);
				$pluginPoPath = $pluginPath . 'resources' . DS . 'locales' . DS . $defaultLanguage . DS;

				// controllo che esista la cartella dei po altrimenti passo al prossimo plugin
				if (!file_exists($pluginPoPath)) {
					continue;
				} 

				$pluginPoDir = scandir($pluginPoPath);

				if (!empty($pluginPoDir)) {
					$pluginPo = array_filter($pluginPoDir, function ($file) {
						return pathinfo($file, PATHINFO_EXTENSION) == 'po';
					});
				}

				$allowedPlugins = ['Blog', 'Clients', 'Orders', 'Subscriptions', 'ContentBlocks', 'Contacts', 'Coupons', 'HelpRequests', 'Newsletters', 'Products', 'Reviews'];

				if (in_array($pluginName, $allowedPlugins)) {

					if (!empty($pluginPo)) {
						foreach ($pluginPo as $poFile) {

							$poFiles[] = [
								'file' => basename($poFile, '.po'),
								'plugin' => $pluginName
							];
						}
					}
				}
			}
		}

		$this->set(compact('poFiles'));
	}

	public function customize($file, $pluginName = null)
	{
		// cancella la cache delle lingue
		Cache::clear('_cake_core_');

		$defaultLanguage = Configure::read('Setup.default_language');

		// costruisco l'url del fule po a partire dal nome e dal plugin
		$basePath = $this->getLocalesPath($pluginName);

		if (!$basePath) {
			throw new ForbiddenException(__d('admin', 'Locale folder not found'));
			die;
		}

		$poPath = $basePath . $defaultLanguage . DS . $file . '.po';

		if (!file_exists($poPath)) {
			throw new ForbiddenException(__d('admin', 'Po file not found'));
			die;
		}


		// recupera il file .po della lingua di default
		$defaultPo = new \Sepia\PoParser\SourceHandler\FileSystem($poPath);
		$defaultParser = new \Sepia\PoParser\Parser($defaultPo);
		$defaultSet  = $defaultParser->parse();

		// recupera tutte le righe
		$defaultEntries = $defaultSet->getEntries();

		$otherLanguages = Configure::read('Setup.languages');



		foreach ($otherLanguages as $key => $otherLanguage) {

			// crea directory e file se non esistono
			if (!file_exists($basePath . $otherLanguage)) {
				mkdir($basePath . $otherLanguage, 0755, true);
			}
			if (!file_exists($basePath . $otherLanguage . DS . $file . '.po')) {
				touch($basePath . $otherLanguage . DS . $file . '.po');
			}

			// recupera il file .po della lingua di traduzione
			$translationPo = new \Sepia\PoParser\SourceHandler\FileSystem($basePath . $otherLanguage . DS . $file . '.po');

			$translationParser = new \Sepia\PoParser\Parser($translationPo);
			$translationSet  = $translationParser->parse();

			// recupera tutte le entries
			$translationEntries = $translationSet->getEntries();

			// aggiorna il file inserendo gli eventuali msgid mancanti
			$saveFile = false;
			$missingEntries = array_diff(array_keys($defaultEntries), array_keys($translationEntries));
			foreach ($missingEntries as $missing) {


				$saveFile = true;
				$entry = new \Sepia\PoParser\Catalog\Entry($defaultEntries[$missing]->getMsgId(), '');
				$msgCtxt = $defaultEntries[$missing]->getMsgCtxt();
				if (!empty($msgCtxt)) {
					$entry->setMsgCtxt($msgCtxt);
				}
				$translationSet->addEntry($entry);
			}

			if ($saveFile) {
				$compiler = new \Sepia\PoParser\PoCompiler();
				$translationPo->save($compiler->compile($translationSet));
			}

			// recupera il file po aggiornato
			$translationEntries = $translationSet->getEntries();

			// calcola la percentuale di traduzione			
			$translationCount = 0;
			$defaultCount = 0;
			foreach ($translationEntries as $translationEntry) {

				if (empty($translationEntry->getMsgCtxt())) {
					$defaultCount++;
				}
				if (!empty($translationEntry->getMsgStr())) {
					$translationCount++;
				}
			}

			$translationPercentage = round($translationCount / $defaultCount * 100);
			$otherLanguages[$key] = [
				'locale' => $otherLanguage,
				'entries' => $translationEntries,
				'percentage' => $translationPercentage
			];
		}

		$defaultLanguage = [
			'locale' => $defaultLanguage,
			'entries' => $defaultEntries,
			'percentage' => 100
		];

		$this->set('defaultLanguage', $defaultLanguage);
		$this->set('otherLanguages', $otherLanguages);
		$this->set('pluginName', $pluginName);
		$this->set('file', $file);
	}

	public function saveField()
	{

		$data = $this->request->getData();

		// controllo che ci siano i paremtri obbligatori
		if (empty($data['locale']) || empty($data['hash']) || empty($data['file'])) {
			throw new ForbiddenException(__d('admin', 'Po file not found'));
			die;
		}

		if (!empty($data)) {
			$locale = $data['locale'];
			$hash = $data['hash'];
			$msgStr = $data['msgStr'] ?? '';
			$file = $data['file'];
			$pluginName = $data['pluginName'] ?? null;

			$basePath = $this->getLocalesPath($pluginName);
			$poPath = $basePath . $locale . DS . $file . '.po';


			// controllo che il file esista
			if (!file_exists($poPath)) {
				throw new ForbiddenException(__d('admin', 'Po file not found'));
				die;
			}

			// recupera il file .po della lingua di traduzione
			$translationPo = new \Sepia\PoParser\SourceHandler\FileSystem($poPath);

			$translationParser = new \Sepia\PoParser\Parser($translationPo);
			$translationSet  = $translationParser->parse();

			// recupera tutte le entries
			$translationEntries = $translationSet->getEntries();
			$entryToUpdate = $translationEntries[$hash];
			$entryToUpdate->setMsgStr($msgStr);

			$compiler = new \Sepia\PoParser\PoCompiler(10000);
			$translationPo->save($compiler->compile($translationSet));
		}

		Cache::clear('_cake_core_');
		die;
	}

	// restituisce il path della cartella locale di un plugin o globale
	private function getLocalesPath($pluginName = null)
	{
		// costruisco l'url del fule po a partire dal nome e dal plugin
		$basePath = RESOURCES . 'locales' . DS;

		if (!empty($pluginName)) {
			$activePlugins = Plugin::loaded();

			if (!in_array($pluginName, $activePlugins)) {
				return false;
			}

			$pluginPath = Plugin::path($pluginName);
			$basePath = $pluginPath . 'resources' . DS . 'locales' . DS;
		}

		return $basePath;
	}
}
