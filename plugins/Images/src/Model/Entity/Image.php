<?php
declare(strict_types=1);

namespace Images\Model\Entity;

use Cake\ORM\Entity;

class Image extends Entity
{
   
    protected array $_accessible = [  
		'*' => true
    ];
	
	// serve per rendere visibili i virtual fields anche quando si trasforma l'oggetto in array o JSON
	protected array $_virtual = ['path', 'backend_path', 'backend_resized_path'];
	
	protected function _getBackendPath()
    {
        return DS . BACKEND_IMAGES . 'desktop' . DS . $this->relative_path;
    }

	protected function _getPath()
    {
        return 'desktop' . DS . $this->relative_path;
    }

    protected function _getBackendResizedPath()
    {
        if (empty($this->relative_path)) return null;
        $path =  DS . BACKEND_IMAGES . 'desktop' . DS . $this->relative_path;
        $options = [
            'transform' => 'resize',
            'size' => [
                'width' => 320,
                'height' => null
            ]
        ];
        return $this->resizeImage($path, $options);
    }

    private function resizeImage($basePath, $options) {
        
        if (empty($basePath) || empty($options)) return null;

        $tmp = trim($basePath, DS);
        $quality = 85;

        $options['size'] = empty($options['size']) ? null : $options['size'];
        $options['zoom'] = !isset($options['zoom']) ? true : $options['zoom'];
        $options['transform'] = !isset($options['transform']) ? 'resize' : $options['transform'];

    

        // passa il primo carattere del metodo transform per distinguere crop e transform sulla stessa immagine e dimensione
        $transformSuffix = '_' . $options['transform'][0];

        $originalPath = WWW_ROOT . $basePath;
        if (!file_exists($originalPath)) return null;
        $dirName = dirname($basePath) ;
        
        
        $compressedDir = WWW_ROOT . $dirName . DS . 'resized' . DS;
        
        
        //verifico se esiste la cartella per le immagini compresse
        if (!file_exists($compressedDir)) {
            mkdir($compressedDir, 0777, true);
        } else if (!is_writable($compressedDir)) {
            chmod($compressedDir, 0777);
        }


        // recuperiamo le informazioni sul file
        $fileInfo = pathinfo($originalPath);
        $fileName = strtolower($fileInfo['filename']);
        $ext = strtolower($fileInfo['extension']);

        
        $path = null;

        // se è un svg o non ho passato le misure restituisco il file originale
        if($ext == 'svg' || empty($options['size']) || !is_array($options['size'])) {
            return $basePath;
        } 

        $size =	$options['size'];
        $width = empty($size['width']) ? 'auto' : $size['width'];
        $height = empty($size['height']) ? 'auto' : $size['height'];
        $compressedPath = $compressedDir . $fileName . '@' . $width . 'x' . $height . $transformSuffix . '.' . $ext;
        $src = $dirName . DS . 'resized' . DS . $fileName . '@' . $width . 'x' . $height . $transformSuffix . '.' . $ext;

    

        if(file_exists($compressedPath)) {
            return $this->encodePath($src);
        } else {
        
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
                $thumb = imagecreatetruecolor((int)$resizeWidth, (int)$resizeHeight);
            }

            if($ext == 'gif' or $ext == 'png'){
                imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            if($ext == 'png'){ $quality = 9 - round(($quality/100) * 9); }

            imagecopyresampled($thumb, $img, 0, 0, (int)$originalStartX, (int)$originalStartY, (int)$resizeWidth, (int)$resizeHeight, (int)$originalWidth, (int)$originalHeight);

    
            switch($ext) {
                case 'gif':
                    imagegif($thumb, $compressedPath);
                    break;
                case 'png':
                    imagepng($thumb, $compressedPath, (int)$quality);
                    break;
                case 'bmp':
                    imagewbmp($thumb, $compressedPath);
                    break;
                case 'jpg':
                case 'jpeg':
                default:
                    imagejpeg($thumb, $compressedPath, $quality);
                    break;
            }

            imagedestroy($thumb);
            return $this->encodePath($src);

        }
        die('test');

    }

    private function encodePath($path){
        if (empty($path)) return '';
        $baseName = rawurlencode(basename($path));
        $dirName = dirname($path);
        return $dirName == '.' ? $baseName : $dirName . DS . $baseName;
    }
    
	
}
