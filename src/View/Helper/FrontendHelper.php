<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Datasource\FactoryLocator;
use Cake\Http\ServerRequest;
use Cake\Collection\Collection;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class FrontendHelper extends Helper
{

    public array $helpers = [
        'Html'
    ];

	private function _parse_url($url) {

		$url_to_parse = $url;

		// se è un url relativo, fai il parse altrimenti restituisci l'url
		if(stripos($url, '/') !== 0) return $url;

		/*if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			$url_to_parse = '/' . ACTIVE_LANGUAGE . $url;
		}*/

		$query_parsed = parse_url($url_to_parse);

		$path = $query_parsed['path'] ?? '';
		$qs = $query_parsed['query'] ?? '';
		$hashtag = $query_parsed['fragment'] ?? '';

		// gestione link alla home
		if($path == '/') {
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
				return '/' . ACTIVE_LANGUAGE . '/';
			} else {
				return '/';
			}
		}

		$path = trim($path, '/');

		if(!empty($path)){

			$sefUrlTable = FactoryLocator::get('Table')->get('SefUrls.SefUrls');
			$sefUrl = $sefUrlTable->find()
				->where([
					'code' => 200,
					'original' => $path,
					'locale' => ACTIVE_LANGUAGE
					])
				->limit(1)
				->first();

			if(!empty($sefUrl->rewritten)) {

				$rewritten = $sefUrl->rewritten;

				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					$rewritten = ACTIVE_LANGUAGE . '/' . $rewritten;
				}
				$url = '/' . $rewritten;

				if(!empty($qs)) $url .= '?' . $qs;
				if(!empty($hashtag)) $url.= '#' . $hashtag;
			} else {
				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					$url = '/' . ACTIVE_LANGUAGE . $url;
				}
			}
		}
		return $url;
	}

	public function url($url = null, $full = false) {
		return $this->_parse_url($url);
	}

	public function seolink($title, $url = null, $options = array(), $confirmMessage = false) {
		if(!empty($confirmMessage)) {
			$options['confirm'] = $confirmMessage;
		}
		return $this->Html->link($title, $this->_parse_url($url), $options);
	}

    //restituisce l'url corrente in formato cake /controller/action/parametri
    public function getCakeUrl(){
        $request = $this->getView()->getRequest();

        $pageUrl = [];
    	$pageUrl[] = str_replace('_', '-', Inflector::underscore($request->getParam('controller')));

    	if (!empty($request->getParam('action'))) {
            $pageUrl[] = $request->getParam('action');
        }
    	if (!empty($request->getParam('pass'))) {
    		$pageUrl = array_merge($pageUrl, $request->getParam('pass'));
    	}
        return implode('/', $pageUrl);
    }

    //restutisce l'url sef in lingua
    public function getLanguageUrl($url, $language){
		if (!empty($url)) {
            $sefUrlTable = FactoryLocator::get('Table')->get('SefUrls.SefUrls');
			$sefurl = $sefUrlTable->find()
                ->where(['original' => $url, 'code' => 200, 'locale' => $language])
                ->first();


			$prefix = $language != DEFAULT_LANGUAGE ? '/'.$language.'/' : '/';
			if (!empty($sefurl->rewritten)) {
				return Router::url($prefix.$sefurl->rewritten, true);
			} else {
				return Router::url($prefix.$url, true);
			}
		}
	}



	private function _webpConvert($file, $eraseOriginal = false)
	{
		$compressionQuality = 90;

		// check if file exists
		if (!file_exists($file)) {
			return false;
		}
		$fileType = exif_imagetype($file);
		//https://www.php.net/manual/en/function.exif-imagetype.php
		//exif_imagetype($file);
		// 1    IMAGETYPE_GIF
		// 2    IMAGETYPE_JPEG
		// 3    IMAGETYPE_PNG
		// 6    IMAGETYPE_BMP
		// 15   IMAGETYPE_WBMP
		// 16   IMAGETYPE_XBM


		$compressedFile = str_replace('webroot' . DS . 'uploads' . DS . 'images', 'webroot' . DS . 'media' . DS . 'images', $file) . '.webp';

		$fileInfo = pathinfo($compressedFile);

		$basePath =  str_ireplace(ROOT . DS . 'webroot', '', $fileInfo['dirname']) . DS;
		$path = $basePath . $fileInfo['basename'];

		if (file_exists($compressedFile)) {

			return $path;
		}

		if (function_exists('imagewebp')) {
			switch ($fileType) {
				case '1': //IMAGETYPE_GIF
					$image = imagecreatefromgif($file);
					break;
				case '2': //IMAGETYPE_JPEG
					$image = imagecreatefromjpeg($file);
					break;
				case '3': //IMAGETYPE_PNG
						$image = imagecreatefrompng($file);
						imagepalettetotruecolor($image);
						imagealphablending($image, true);
						imagesavealpha($image, true);
						break;
				case '6': // IMAGETYPE_BMP
					$image = imagecreatefrombmp($file);
					break;
				case '15': //IMAGETYPE_Webp
				   return false;
					break;
				case '16': //IMAGETYPE_XBM
					$image = imagecreatefromxbm($file);
					break;
				default:
					return false;
			}

			$compressedDir = dirname($compressedFile);
			if (!file_exists($compressedDir)) {
				mkdir($compressedDir, 0777, true);

			} else if (!is_writable($compressedDir)) {
				chmod($compressedDir, 0777);
			}

			// Save the image
			$result = imagewebp($image, $compressedFile, $compressionQuality);
			if (false === $result) {
				return false;
			}
			// Free up memory
			imagedestroy($image);
			if($eraseOriginal) {
				unlink($file);
			}

			return $path;
		} elseif (class_exists('Imagick')) {
			$image = new Imagick();
			$image->readImage($file);
			if ($fileType === "3") {
				$image->setImageFormat('webp');
				$image->setImageCompressionQuality($compressionQuality);
				$image->setOption('webp:lossless', 'true');
			}
			$image->writeImage($outputFile);
			if($eraseOriginal) {
				unlink($file);
			}
			return $path;
		}

		return false;
	}

   	// crea thumbnail con cache delle immagini
	function image($basePath, $options = array()) {

		$tmp = trim($basePath, DS);

		$quality = 85;

		$options['size'] = empty($options['size']) ? null : $options['size'];
		$options['zoom'] = !isset($options['zoom']) ? true : $options['zoom'];
		$options['transform'] = !isset($options['transform']) ? 'resize' : $options['transform'];

		// passa il primo carattere del metodo transform per distinguere crop e transform sulla stessa immagine e dimensione
		$transformSuffix = '_' . $options['transform'][0];

		$originalPath = UPLOADED_IMAGES . $basePath;
		$compressedPath = MEDIA_IMAGES . $basePath . '.webp';

		$src = DS . IMAGES . $basePath;

		// recuperiamo le informazioni sul file
		$fileInfo = pathinfo($originalPath);
		$fileName = strtolower($fileInfo['filename']);
		$ext = strtolower($fileInfo['extension']);

		$dirName = str_replace($fileInfo['basename'], '', $basePath);
        $path = null;


		// se è un svg il file compresso è il file originale ma spostato nella cartella media
		if($ext == 'svg') {
			$compressedPath = MEDIA_IMAGES . $basePath;
		} else {
			$src = DS . IMAGES . $basePath . '.webp';

			if(empty($options['size']) || !is_array($options['size'])) {
				// richiamiamo la versione webp dell'immagine originale senza specificare un ridimensionamento
			} else {
				$size =	$options['size'];
				$width = empty($size['width']) ? 'auto' : $size['width'];
				$height = empty($size['height']) ? 'auto' : $size['height'];
				$tmp = UPLOADED_IMAGES . $dirName . $fileName . '@' . $width . 'x' . $height . $transformSuffix . '.' . $ext;
				$compressedPath = MEDIA_IMAGES . $dirName . $fileName . '@' . $width . 'x' . $height . $transformSuffix . '.' . $ext . '.webp';
				$src = DS . IMAGES . $dirName . $fileName . '@' . $width . 'x' . $height . $transformSuffix . '.' . $ext . '.webp';
			}
		}

		if(file_exists($compressedPath)) {
			return $this->encodePath($src);
		} else {
			if($ext == 'svg') {
				$compressedPath = MEDIA_IMAGES . $basePath;
				$compressedDir = dirname($compressedPath);
				if (!file_exists($compressedDir)) {
					mkdir($compressedDir, 0777, true);
				} else if (!is_writable($compressedDir)) {
					chmod($compressedDir, 0777);
				}
				copy($originalPath, $compressedPath);
				$path = $src;
			} else {
				// andiamo a creare la versione temporanea dell'immagine ridimensionata da passare a webp
				if(!empty($size)) {
					if(file_exists($originalPath)) {

						list($originalWidth, $originalHeight) = getimagesize($originalPath);

						switch($options['transform']) {
							case 'stretch':
								if(!empty($size['width'])) {
									$wRatio = $originalWidth / $size['width'];
								}

								if(!empty($size['height'])) {
									$hRatio = $originalHeight / $size['height'];
								}

								if(!empty($wRatio) && !empty($hRatio)) {
									$wRatio = 1 / $wRatio;
									$hRatio = 1 / $hRatio;
								}
								else if(!empty($wRatio)) {
									$wRatio = 1 / $wRatio;
									$hRatio = 1;
								}
								else if(!empty($hRatio)) {
									$hRatio = 1 / $hRatio;
									$wRatio = 1;
								}

								if($options['zoom'] === false) {
									$wRatio = $wRatio > 1 ? 1 : $wRatio;
									$hRatio = $hRatio > 1 ? 1 : $hRatio;
								}
								$resizeWidth = round($originalWidth * $wRatio);
								$resizeHeight = round($originalHeight * $hRatio);
								$originalStartX = $originalStartY = 0;
							break;
							case 'crop':
								if(empty($size['width'])) { $size['width'] = $size['height']; }
								if(empty($size['height'])) { $size['height'] = $size['width']; }

								$wRatio = $originalWidth / $size['width'];
								$hRatio = $originalHeight / $size['height'];

								$ratio = $wRatio > $hRatio ? 1 / $hRatio : 1 / $wRatio;

								$resizeWidth = round($originalWidth * $ratio);
								$resizeHeight = round($originalHeight * $ratio);
								$originalStartX  = round((($resizeWidth - $size['width'] )/2)*$hRatio);
								$originalStartY  = round((($resizeHeight - $size['height'])/2)*$wRatio);
							break;
							case 'resize':
							default :
								if(!empty($size['width'])) {
									$wRatio = $originalWidth / $size['width'];
								}

								if(!empty($size['height'])) {
									$hRatio = $originalHeight / $size['height'];
								}

								if(!empty($wRatio) && !empty($hRatio)) { $ratio = $wRatio > $hRatio ? 1 / $wRatio : 1 / $hRatio; }
								else if(!empty($wRatio)) { $ratio = 1 / $wRatio; }
								else if(!empty($hRatio)) {$ratio = 1 / $hRatio; }

								if($options['zoom'] === false && $ratio > 1) { $ratio = 1; }
								$resizeWidth = round($originalWidth * $ratio);
								$resizeHeight = round($originalHeight * $ratio);
								$originalStartX = $originalStartY = 0;
							break;
						}

						switch($ext) {
							case 'gif':
								$img = imagecreatefromgif($originalPath);
								break;
							case 'png':
								$img = imagecreatefrompng($originalPath);
								break;
							case 'bmp':
								$img = imagecreatefromwbmp($originalPath);
								break;
							case 'jpg':
							case 'jpeg':
							default:
								$img = imagecreatefromjpeg($originalPath);
								break;
						}

						if($options['transform'] == 'crop') {
							$thumb = imagecreatetruecolor($size['width'], $size['height']);
						} else {
							$thumb = imagecreatetruecolor($resizeWidth, $resizeHeight);
						}

						if($ext == 'gif' or $ext == 'png'){
							imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
							imagealphablending($thumb, false);
							imagesavealpha($thumb, true);
						}

						if($ext == 'png'){ $quality = 9 - round(($quality/100) * 9); }

						imagecopyresampled($thumb, $img, 0, 0, $originalStartX, $originalStartY, $resizeWidth, $resizeHeight, $originalWidth, $originalHeight);

						switch($ext) {
							case 'gif':
								imagegif($thumb, $tmp);
								break;
							case 'png':
								imagepng($thumb, $tmp, $quality);
								break;
							case 'bmp':
								imagewbmp($thumb, $tmp);
								break;
							case 'jpg':
							case 'jpeg':
							default:
								imagejpeg($thumb, $tmp, $quality);
								break;
						}

						imagedestroy($thumb);
						$path = $this->_webpConvert($tmp, true);

					}
				} else {
					$path = $this->_webpConvert($originalPath);
				}
			}
		}

		return $path;
	}

    private function encodePath($path){
        if (empty($path)) return '';
        $baseName = rawurlencode(basename($path));
        $dirName = dirname($path);
        return $dirName == '.' ? $baseName : $dirName . DS . $baseName;
    }

    // shortHand per crop
    public function crop($basePath, $width = null, $height = null){
        $options = [
            'transform' => 'crop',
            'size' => [
                'width' => $width,
                'height' => $height
            ]
        ];

        return $this->image($basePath, $options);
    }

    // shortHand per resize
    public function resize($basePath, $width = null, $height = null){
        $options = [
            'transform' => 'resize',
            'size' => [
                'width' => $width,
                'height' => $height
            ]
        ];

        return $this->image($basePath, $options);
    }

    // crea un menu con sottomenu
    public function menu($items, $options = [], $level = 0){
        if (empty($items)) return;

        $defaults = [
            'class' => 'menu',
            'showBackArrow' => false
        ];

        $options = array_merge($defaults, $options);

        $links = [];

        if (!empty($items)){

            if (!empty($options['showBackArrow']) && $level > 0) {
                $links[] = $this->Html->tag('li', $this->Html->tag('span', __d('global', 'menu back'), ['class' => 'menu__back']));
            }
            foreach ($items as $item) {
                $linkOptions = [
                    'class' => !empty($item->children) ? 'menu__link menu__link--parent' : 'menu__link'
                ];
                if (!empty($item->blank)) $linkOptions['target'] = '_blank';
                $link = $this->seolink($item->title, $item->url, $linkOptions); //sostuire con url riscritta

                if (!empty($item->children)) $link .= $this->menu($item->children, array_merge($options, ['class' => 'menu__submenu']), $level+1);

                $links[] = $this->Html->tag('li', $link, ['class' => 'menu__item']);
            }
        }

        return $this->Html->tag('ul', implode("\n", $links), ['class' => $options['class']]);

    }

    // genera il layout per un pagina composta da blocchi di contenuto
    public function renderContentBlocks($contentBlocks){
        $out = '';
        if (!empty($contentBlocks)) {
            $view = $this->getView();
            $images = $view->get('images');
            $attachments = $view->get('attachments');
            
            foreach ($contentBlocks as $contentBlock) {

                if (empty($contentBlock->content_block_type) || empty($contentBlock->content_block_type->layout)) continue;
                $out .= $view->element('ContentBlocks.frontend/'.$contentBlock->content_block_type->layout, [
                    'item' => $contentBlock,
                    'images' => $images[$contentBlock->id] ?? [],
                    'attachments' => $attachments[$contentBlock->id] ?? []
                ]);
            }
        }

        return $out;
    }

    //include un immagine svg presente nella cartella img
    public function svg($path){
        $fullPath = WWW_ROOT.'img'.DS.$path;

        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }elseif(file_exists(UPLOADED_IMAGES . $path)){
					return file_get_contents(UPLOADED_IMAGES . $path);
				}
				
        return false;
    }

	public function formatPrice($price){
		return number_format($price, 2, ',', '') . ' €';
	}

    //crea un menu ricorsivo per un modello con tree behavior
    // parametri options possibili:
    // class: classe html (default menu)
    // current: id dell'elemento corrente (aggiunge la class active)
    // displayField: nome del campo da usare come label (default title)
    // controller: nome del controller da usare per il link
    // action: nome dell'action per il link
    public function recursiveMenu($items = [],$options = [], $level = 0){
        $defaults = [
            'class' => 'menu',
            'current' => null,
            'displayField' => 'title',
            'controller' => null,
            'action' => 'view',
            'showAllLink' => null,
            'showAllLabel' => null
        ];

        $defaults = array_merge($defaults, $options);
        extract($defaults);

        if (!empty($items)) {
            $li = [];

            if ($level == 0 && !empty($showAllLink) && !empty($showAllLabel)) {
                $extraClass = empty($current) ? 'active' : '';
                $li[] = $this->Html->tag('li', $this->seolink($showAllLabel, $showAllLink), ['class' => $extraClass]);
            }

            foreach ($items as $item) {
                $extraClass = !empty($current) && $current == $item->id ? 'active' : '';
                $tmp = $this->seolink($item->{$displayField}, '/' . $controller . '/' . $action . '/' . $item->id);

                if (!empty($item->children)){
                    $tmp .= "\n" . $this->recursiveMenu($item->children, $options, $level+1);
                }

                $li[] = $this->Html->tag('li', $tmp, ['class' => $extraClass]);
            }

            $class = $level > 0 ? $class . '__submenu' : $class;
            return $this->Html->tag('ul', implode("\n", $li), ['class' => $class]);
        }


    }

    public function formatDate($field, $format = 'dd MMM yyyy') {
        return $field->i18nFormat($format);
    }


}
?>
