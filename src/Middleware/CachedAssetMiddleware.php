<?php
namespace App\Middleware;

use Cake\I18n\Time;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Cake\Core\Configure;

class CachedAssetMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
							
		$debug = Configure::read('debug');
		$sandboxCache = CACHE . 'asset_compress';
		$liveCache = WWW_ROOT . 'cache_assets';		
		
		// se il debug è a true, svuotiamo la cache live se presente
		if(!empty($debug)) {		
			if(file_exists($liveCache)){			
				// svuotiamo la cache live	
				$files = glob($liveCache . DS . '*');
				foreach($files as $file){
					if(is_file($file)) {
						unlink($file);
					}
				}
			} else {
				//creiamo la directory necessaria per la cache live
				mkdir($liveCache, 0755);
			}
		} else {
			// popoliamo la cache live se non c'è
			$files = glob($sandboxCache . DS . '*');
			foreach($files as $file){
				if(is_file($file)) {
					$filename = basename($file);
					copy($file, $liveCache . DS . $filename);
				}
			}
		}

        // Calling $handler->handle() delegates control to the *next* middleware
        // In your application's queue.
        $response = $handler->handle($request);

        return $response;
    }
}