<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Utility\Text;

class UploaderComponent extends Component
{
    
	protected $allowedFiles, $allowedImages, $maxImageSize, $maxFileSize;

    // Execute any other additional setup for your component.
    public function initialize(array $config): void
    {		
        $this->allowedFiles = Configure::read('Setup.allowedFiles');		
		$this->maxFileSize = Configure::read('Setup.maxFileSize');		
		$this->allowedImages = Configure::read('Setup.allowedImages');
		$this->maxImageSize = Configure::read('Setup.maxImageSize');
    }
	
	public function uploadImage($uploadedFile, $devicePath = 'desktop', $filename = null) 
	{
		return $this->upload($uploadedFile, ['filename' => $filename, 'type' => 'image', 'devicePath' => $devicePath]);
	}
	
	public function uploadFile($uploadedFile, $filename = null) 
	{
		return $this->upload($uploadedFile, ['filename' => $filename, 'type' => 'file']);
	}
	
	
	public function upload($uploadedFile, $options = [])
	{		
		
		$_defaultOptions = [
			'type' => 'file',
			'devicePath' => 'desktop',
			'filename' => null
		];
		
		$options = array_merge($_defaultOptions, $options);		
		
		$type = $options['type'];
		$devicePath = $options['devicePath'] . DS;
						
		$allowedExtensions = $type == 'image' ? $this->allowedImages : $this->allowedFiles;		
		
		if($type == 'image'){
			$baseUploadDir = UPLOADED_IMAGES . $devicePath;
		} else {
			$baseUploadDir = UPLOADED_FILES;
		}
		
		$path = [
			'dbPath' => '',
			'wwwPath' => ''
		];
		$error = '';				
					
		if ($uploadedFile->getError() == 0) {
			// file uploaded
			
			// se sto forzano il filename, tengo quello
			if(!empty($options['filename'])) {
			 	$fileName = $options['filename'];
			} else {
				// recupero il filename dal file caricato
				$fileName = $uploadedFile->getClientFilename();
				// recupero il nome file senza estensione (qualunque essa sia)
				$fileName = pathinfo($fileName, PATHINFO_FILENAME);
			}			
			$fileName = Text::slug($fileName, ['replacement' => '-']);
			$fileType = $uploadedFile->getClientMediaType();
			$fileSize = $uploadedFile->getSize();
			$tmpName = $uploadedFile->getStream()->getMetadata('uri');			
						
			// ricavo l'estensione del file a partire dal mimetype
			$ext = $this->mime2ext($fileType);			
				
			// controllo che l'estensione del file caricato sia consentita
			if(in_array($ext, $allowedExtensions)){
						
															
				// METTERE SE SERVE CONTROLLO DIMENSIONI
				//list($width, $height, $type) = getimagesize($tmpName);								
				$savedFile = $this->avoidConflict($fileName, $ext, $baseUploadDir);				
				$filePath = $savedFile['filePath'];
				
				if (!file_exists($baseUploadDir)) {
					mkdir($baseUploadDir, 0777, true);

				} else if (!is_writable($baseUploadDir)) {
					chmod($baseUploadDir, 0777);
				}
				$uploadedFile->moveTo($filePath);
				
				$path['ext'] = $savedFile['ext'];
				$path['fileName'] = $savedFile['fileName'];
				$path['wwwPath'] = $filePath;			
				$path['dbPath'] = str_replace($baseUploadDir, '', $filePath);
				
			} else {
				$error = __('Il formato del file non è ammesso');
			}
		} else {
			$error = __('Errore nel file caricato');
		}
		
		return $path;
	}

	public function avoidConflict($fileName = null, $ext = null, $dir = null) {
		
		$filePath = false;
		$conflict = true;
		
		if(!empty($fileName) && !empty($ext) && !empty($dir)) {
			$finalName = $fileName;
			$filePath = $dir . $fileName . '.' . $ext;
			
			// se il file non esiste, procediamo a salvarlo
			if(!is_file($filePath)) {
				$conflict = false;
			} else {
				$index = 1;
				
				// se il file esiste proviamo a salvarlo con un suffisso _index
				while($conflict && $index <= 50) {
					$finalName = $fileName . '_' . $index;
					$filePath = $dir . $finalName . '.' . $ext;
					if(!is_file($filePath)) {
						$conflict = false;
					}
					$index++;
				}					
			}

			// se ancora dopo 50 tentativi non si è risolto il conflitto, associamo un suffisso univoco
			if($conflict) {
				$bytes = random_bytes(6);
				$suffix = bin2hex($bytes);
				$finalName = $fileName . '_' . $suffix;
				$filePath = $dir . $finalName . '.' . $ext;
			}	
		}
		
		$response = [
			'filePath' => $filePath,
			'fileName' => $finalName,
			'ext' => $ext
		];
		
		return $response;
		
	}
		
	
	public function cropper($originalFile = null, $cropData = [], $finalWidth = null, $finalHeight = null)
	{
		
		$cropData = json_decode($cropData);
		$quality = 85;
				
		
		if(!empty($finalWidth) || !empty($finalHeight)) {
		
			// fix per evitare, per errore o malizia, valori negativi
			$finalWidth = !empty($finalWidth) ? abs($finalWidth) : null;
			$finalHeight = !empty($finalHeight) ? abs($finalHeight) : null;
			
			if(file_exists($originalFile)) {
									
				list($originalWidth, $originalHeight, $fileType) = getimagesize($originalFile);
								
				$preMapping = [				
					1 => 'gif',
					2 => 'jpeg',
					3 => 'png',
					6 => 'bmp'
				];
				
				$ext = $preMapping[$fileType];
				
				// se la dimensione iniziale e finale coincidono, non croppare il file
				if($finalWidth == $originalWidth && $finalHeight == $originalHeight) return;	
				
				// fai il crop solo per gif, png, bmp e jpeg
				if(in_array($ext, ['png', 'gif', 'bmp', 'jpg', 'jpeg'])) {

					
					$resizeWidth = round($finalWidth);
					$resizeHeight = round($finalHeight);
						
					if(empty($finalWidth) || empty($finalHeight)) {

						if(empty($finalWidth)) { $finalWidth = $finalHeight; }
						if(empty($finalHeight)) { $finalHeight = $finalWidth; }

						$wRatio = $originalWidth / $finalWidth;
						$hRatio = $originalHeight / $finalHeight;

						$ratio = $wRatio > $hRatio ? 1 / $hRatio : 1 / $wRatio;

						$resizeWidth = round($originalWidth * $ratio);
						$resizeHeight = round($originalHeight * $ratio);
						//$originalStartX  = round((($resizeWidth - $finalWidth )/2)*$hRatio);
						//$originalStartY  = round((($resizeHeight - $finalHeight)/2)*$wRatio);
					}

					switch($ext) {
						case 'gif':
							$img = imagecreatefromgif($originalFile);
							break;
						case 'png':
							$img = imagecreatefrompng($originalFile);
							break;
						case 'bmp':
							$img = imagecreatefromwbmp($originalFile);
							break;
						case 'jpg':
						case 'jpeg':
						default:
							$img = imagecreatefromjpeg($originalFile);
							break;
					}

					$cropped = imagecreatetruecolor($finalWidth, $finalHeight);

					if($ext == 'gif' or $ext == 'png'){
						imagecolortransparent($cropped, imagecolorallocatealpha($cropped, 0, 0, 0, 127));
						imagealphablending($cropped, false);
						imagesavealpha($cropped, true);
					}

					if($ext == 'png'){ $quality = 9 - round(($quality/100) * 9); }					

					imagecopyresampled($cropped, $img, 0, 0, $cropData->x, $cropData->y, $resizeWidth, $resizeHeight, $cropData->width, $cropData->height);					

					switch($ext) {
						case 'gif':
							imagegif($cropped, $originalFile);
							break;
						case 'png':
							imagepng($cropped, $originalFile, $quality);
							break;
						case 'bmp':
							imagewbmp($cropped, $originalFile);
							break;
						case 'jpg':
						case 'jpeg':
						default:
							imagejpeg($cropped, $originalFile, $quality);
							break;
					}

					imagedestroy($img);
					imagedestroy($cropped);					
				}
			}
		}
				
	}
	
	public function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) === true ? $mime_map[$mime] : false;
    }
	
}