<?php
declare(strict_types=1);

namespace ContentBlocks\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Event\EventInterface;


class ContentBlocksController extends AppController
{

    // l'index dei blocchi non deve essere accessibile
    public function index(){
        throw new NotFoundException();
    }

    // aggiunge un blocco
    public function addBlock(){

        $this->request->allowMethod(['put']);
        $data = $this->request->getData();
        extract($data);

        // controllo che ci siano i parametri obbligatori
        if (empty($title) || empty($model) || empty($ref) || empty($content_block_type_id)) {
            throw new ForbiddenException(__d('admin', 'Missing mandatory parameters'));
        }

        // salvo il nuovo blocco
        $item = $this->dbTable->newEmptyEntity();
        $item = $this->dbTable->patchEntity($item, $data);

        if ($this->dbTable->save($item)) {
            $this->set(compact('item'));
            $this->viewBuilder()->setOption('serialize', 'item');
            return;
        }

        throw new InternalErrorException(__d('admin', 'Something went wrong'));
    }


    // sovrascrive il metodo get dell'AppController perché ha parametri diversi
    public function get()
    {

        $query = $this->request->getQuery();

        extract($query);

        //controllo che ci siano i parametri obbligatori
        if (empty($model) || empty($ref)) {
            throw new ForbiddenException(__d('admin', 'Missing mandatory parameters'));
        }

        $plugin = isset($plugin) ? $plugin : null;
    
        if($this->dbTable->hasBehavior('MultiTranslatable')) {
            $records = $this->dbTable->find('translations');
        } else {
            $records = $this->dbTable->find();
        }

        $records->contain([
            'ContentBlockTypes' => [
                'Icon'
            ]
        ]);
        $records->where([
            'ContentBlocks.plugin' => $plugin,
            'ContentBlocks.model' => $model,
            'ContentBlocks.ref' => $ref,
            'ContentBlocks.published' => 1
        ]);

        $records->order([
            'ContentBlocks.position' => 'ASC']
        );

        $this->set(compact('records'));
        $this->viewBuilder()->setOption('serialize', 'records');

    }

    public function edit($id = null)
    {
        $this->queryContain = [
            'ContentBlockTypes',
            'Images' => 'ResponsiveImages'
        ];

        if ($this->request->is(['patch', 'post', 'put'])) {
            // redirect personalizzato
            $item = $this->ContentBlocks->get($id);

            if (!empty($item)) {
                $this->actionRedirect = ['plugin' => $item->plugin, 'controller' => $item->model, 'action' => 'compose', $item->ref]; //da vedere se servirà salvare anche il controller
            }
        }

        parent::edit($id);
    }

    //setto la view per l'edit
    // public function beforeRender(EventInterface $event){
    //     parent::beforeRender($event);

    //     if ($this->request->getParam('action') == 'edit') {
    //         $item = $this->viewBuilder()->getVar('item');
    //         $viewFile = $item->content_block_type->layout;
    //         $this->viewBuilder()->setTemplate($viewFile);
    //     }
    // }

}
