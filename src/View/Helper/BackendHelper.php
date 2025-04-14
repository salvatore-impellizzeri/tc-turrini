<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;
use Cake\Core\Configure;

class BackendHelper extends Helper
{

    public array $helpers = [
        'Html',
        'Form',
        'Url',
		'Frontend'
    ];


    public function materialIcon($icon, $options = []){
        $extraClass = $options['class'] ?? null;
        return $this->Html->tag('span', $icon, ['class' => 'material-symbols-rounded '.$extraClass]);
    }


    public function iconButton($label, $icon, $options = []){
        $defaults = [
            'class' => '',
            'tag' => 'button',
            'url' => null
        ];
        $options = array_merge($defaults, $options);


        $extraClass = $options['class'] ?? null;
        $params = [
            'class' => 'btn btn--icon '.$extraClass
        ];
        if (!empty($options['target'])) $params['target'] = $options['target'];

        if (!empty($options['url'])) $params['href'] = $this->Url->build($options['url']);
		if($options['tag'] == 'button') {
			$options['tag'] = null;
			$options['escapeTitle'] = false;
			$options['class'] = $params['class'];
			$options['name'] = '_submit';
			return $this->Form->button($this->materialIcon($icon).' '.$this->Html->tag('span', $label), $options);
		} else {
			return $this->Html->tag($options['tag'], $this->materialIcon($icon).' '.$this->Html->tag('span', $label), $params);
		}
    }

    public function belongsToMany($field, $item, $settings = []){


        //valori di default
        $defaults = [
            'label' => $field,
            'options' => [],
            'url' => ['action' => 'checkbox']
        ];

        //merge con parametri passati
        $settings = array_merge($defaults, $settings);
        extract($settings);

        //vedo se ci sono dei valori pre-selezionati
        $preSelected = [];
        $selectables = [];

        if (!empty($item->{$field})) {
            foreach ($item->{$field} as $i => $tag) {
                $preSelected[] = $tag->id;
                $selectables[] = "<span class='multiCheckbox__tag' data-multicheckbox-tag>".$tag->title." <span class='multiCheckbox__tag__remove material-symbols-rounded' data-multicheckbox-remove data-id='".$tag->id."'>close</span><input type='hidden' name='".$field."[".$i."][id]' value='".$tag->id."'><input type='hidden' name='".$field."[".$i."][_joinData][position]' value='".$i."' data-position></span>";
            }
        }

        //pulsante aggiungi
        //$selectables[] = $this->Html->tag('span', $this->materialIcon('add'), ['data-multicheckbox-add', 'class' => 'multiCheckbox__add']);

        //url cake way
        $url = $this->Url->build($url);
    
        $out = $this->Html->div('multiCheckbox__selected', implode('', $selectables), ['data-multicheckbox-selected']);
        $out .= $this->Form->hidden('_preselected', ['name' => '', 'value' => implode(',', $preSelected), 'data-multicheckbox-preselected']);
        $out .= $this->Html->div('multiCheckbox__actions', $this->Html->tag('span', __d('admin','add action'), ['data-multicheckbox-add', 'class' => 'btn btn--primary-outline btn--small']));
        $popupContent = $this->Html->div('multiCheckbox__title label', __d('admin', 'Select').$this->Html->tag('span', 'close', ['class' => 'material-symbols-rounded', 'id' => 'close-popup', 'data-multicheckbox-close']),);
        $popupContent .= $this->Form->text('multicheckbox_search', ['data-multicheckbox-search', 'class' => 'search',  'placeholder' => __d('admin','search')]);
        $popupContent .= $this->Html->div('multiCheckbox__wrapper',  $this->Html->div('multiCheckbox__options', '', ['data-multicheckbox-inputs']), ['data-multicheckbox-wrapper']);
        $popupContent .= $this->Html->div('multiCheckbox__btn', $this->Html->div('btn btn--primary', __d('admin', 'done'), ['data-multicheckbox-close']));
        $out .= $this->Html->div('multiCheckbox__popup labelled', $popupContent, ['data-multicheckbox-popup']);

        $emptyInput = $this->Form->hidden($field.'._ids', ['value' => '']);
        $label = $this->Html->tag('label', $label);

		if(ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE) {
			return '';
		} else {
			return $this->Html->div('input multiCheckbox', $emptyInput.$label.$out, ['data-multicheckbox', 'data-multicheckbox-url' => $url, 'data-field' => $field]);
		}

    }


    // public function belongsToMany($field, $settings = []){
    //     $defaults = [
    //         'label' => $field,
    //         'options' => [],
    //         'url' => $this->Url->build(['action' => 'checkbox'])
    //     ];
    //
    //     $settings = array_merge($defaults, $settings);
    //     extract($settings);
    //
    //
    //     $out = $this->Html->div('multiCheckbox__selected', '', ['data-multicheckbox-selected']);
    //     $out .= $this->Html->div('multiCheckbox__actions', $this->Html->tag('span', __d('admin','add action'), ['data-multicheckbox-add']));
    //     $popupContent = $this->Html->div('multiCheckbox__title label', __d('admin', 'add tag').$this->Html->tag('span', 'close', ['class' => 'material-symbols-rounded', 'id' => 'close-popup']),);
    //     $popupContent .= $this->Form->text('multicheckbox_search', ['data-multicheckbox-search', 'class' => 'search',  'placeholder' => __d('admin','search')]);
    //     $popupContent .= $this->Form->control('tags._ids', ['options' => $options, 'multiple' => 'checkbox', 'label' => false]);
    //     $popupContent .= $this->Html->div('multiCheckbox__btn', $this->Html->div('btn btn--primary',__d('admin', 'done')));
    //     $out .= $this->Html->div('multiCheckbox__popup labelled', $popupContent, ['data-multicheckbox-popup']);
    //
    //     $label = $this->Html->tag('label', $label);
    //     return $this->Html->div('input multiCheckbox', $label.$out, ['data-multicheckbox', 'data-multicheckbox-url' => $url]);
    //
    // }

    public function treeList($items, $options = []){
        if (empty($items)) return;

        // merge di opzioni con default
        $defaults = [
            'draggable' => true,
            'class' => 'tree-list',
            'parent' =>  'root', // utilizzato per chiamate ricorsive
            'maxDepth' => 10,
            'links' => []
        ];

        $options = array_merge($defaults, $options);

        $isTranslatable = $this->getView()->get('isTranslatable');


        if (!empty($items)) {

            foreach ($items as $item) {
                $rowContent = [];
                if (!empty($options['draggable'])) $rowContent[] = $this->Html->tag('span', $this->materialIcon('drag_indicator'), ['class' => 'tree-list__drag', 'data-tree-list-drag']);
                $rowContent[] = $this->Html->div('tree-list__row__title', $this->Html->link($item->title, ['action' => 'edit', $item->id]));

                if (!empty($options['links'])) {
                    $rowLinks = [];
                    foreach ($options['links'] as $link) {
                        if (!empty($link['conditions'])){
                            $expr = "return {$item->{$link['conditions'][0]}} {$link['conditions'][1]} {$link['conditions'][2]};";
                            $test = eval($expr);
                            if (!$test) continue;
                        }

                        // sostituisce tutti i paramentri $variabile con la corrispettiva proprieta dell'oggetto item ($item->variabile)

                        $encodedLink = json_encode($link['url']);
                        $replacedLink = preg_replace_callback(
                            '/"\$(.*)"/',
                            function($matches) use ($item){
                                return $item->{$matches[1]};
                            },
                            $encodedLink
                        );
                        $link['url'] = json_decode($replacedLink, true);
                        $rowLinks[] = $this->Html->link($link['title'], $link['url'], ['class' => 'btn btn--primary btn--small']);
                    }

                    $rowContent[] = $this->Html->div('tree-list__row__buttons', implode("\n", $rowLinks));
                }

                if ($isTranslatable) {
                    $rowContent[] = $this->translationStatus($item);
                }

                $rowContent[] = $this->Html->div('tree-list__row__toggler', $this->toggle($item->id, 'published', $item->published));

                //azioni row
                $actions = [];
                $actions[] = $this->actionEdit($item->id);
                $actions[] = $this->actionDelete($item->id);

                $actionList = $this->Html->div('action-list', implode("\n", $actions));

                $rowContent[] = $actionList;


                $row = $this->Html->div('tree-list__row', implode("\n", $rowContent));

                if (!empty($item->children)) {
                    //chiamata ricorsiva
                    $row .= $this->treeList($item->children, [
                        'class' => 'tree-list__submenu',
                        'parent' => $item->id,
                        'maxDepth' => $options['maxDepth'],
                        'links' => $options['links'],
                    ]);
                } else {
                    //riga vuota per il drop
                    $row .= $this->Html->tag('ul', '', [
                        'class' => 'tree-list__submenu',
                        'data-tree-list',
                        'data-parent' => $item->id,
                        'data-max-depth' => $options['maxDepth']
                    ]);
                }

                $rows[] = $this->Html->tag('li', $row, ['data-id' => $item->id]);
            }
        }

        return $this->Html->tag('ul', implode("\n", $rows), [
            'class' => $options['class'],
            'data-tree-list',
            'data-parent' => $options['parent'],
            'data-max-depth' => $options['maxDepth']
        ]);

    }

    //shortcut per action edit, se servono url custom usare la funzione generica action()
    public function actionEdit($id){
        return $this->action('edit', __d('admin', 'edit'), ['action' => 'edit', $id]);
    }

    //azione generica con icona
    public function action($icon, $label, $url) {
        return $this->Html->link($this->materialIcon($icon)."\n".$this->Html->div('action__tooltip', $label), $url, ['class' => 'action', 'escape' => false]);
    }

    //azione delete con messaggio conferma
    public function actionDelete($id) {
        return $this->Form->postLink(
            $this->materialIcon('delete')."\n".$this->Html->div('action__tooltip', __d('admin', 'delete')),
            ['action' => 'delete', $id],
            ['escape' => false, 'class' => 'action', 'confirm' => __d('admin', 'delete confirm')]
        );
    }


    //toggler per gestione ajax campo tinyint
    public function toggle($id, $field, $value){
        $class = $value ? ' active' : '';
        return $this->Html->tag('span', $this->Html->tag('span', '', ['class' => 'onoff']), ['class' => 'toggle'.$class, 'data-toggle-field' => $field, 'data-toggle-id'=> $id]);
    }

    //stato traduzioni
    // item: elemento da tradurre
    // action: può essere stringa dell'action all'interno del plugin o array completo se si esce dal plugin
    public function translationStatus($item, $action = 'edit'){
        $languages = Configure::read('Setup.languages');
        if (empty($languages)) return;

        $links = [];

        foreach ($languages as $lang) {
            $status = 'missing';

            if (!empty($item->_translations)){

                if (!empty($item->_translations[$lang])){
                    $status = $item->_translations[$lang]->_status == 1 ? 'complete' : 'partial';
                }
            }

            if(is_array($action)){
                $action_link = array_merge($action, ['?' => ['lang' => $lang]]);
            } else {
                $action_link = ['action' => $action, $item->id, '?' => ['lang' => $lang]];
            }

            $links[] = $this->Html->link($lang, $action_link, ['class' => 'translation-status__language translation-status__language--'.$status]);
        }

        return $this->Html->div('translation-status', implode($links));

    }

    //header per le tab Admin
    // ogni toggler deve avere i parametri:
    // title: titolo del toggler
    // icon: icona del toggler
    // hash: hash della tab, la tab corrispondente deve avere l'attributo data-tab="hash"
    public function tabsHeader($togglers = [], $url_preview = null){
        $links = [];

        foreach ($togglers as $toggler) {
            $links[] = $this->Html->tag(
                'a',
                $this->Html->tag('span', $this->materialIcon($toggler['icon']), ['class' => 'tabs__toggler__icon']) . $this->Html->tag('span', $toggler['title'], ['class' => 'tabs__toggler__title']),
                ['href' => '#'.$toggler['hash'], 'class' => 'tabs__toggler', 'data-tab-toggler']
            );
        }
		
		if(!empty($url_preview)){
			$links[] = $this->Html->tag(
                'a',
                $this->Html->tag('span', $this->materialIcon('image_search'), ['class' => 'tabs__toggler__icon']) . $this->Html->tag('span', __d('admin', 'tabs preview'), ['class' => 'tabs__toggler__title']),
                ['href' =>  $this->Frontend->url($url_preview), 'class' => 'tabs__toggler', 'target' => '_blank']
            );
		}

        return $this->Html->div('tabs__header', implode($links));

    }

    //include un immagine svg presente nella cartella img
    public function svg($path){
        $fullPath = WWW_ROOT.'admin-assets' . DS . 'img' . DS.$path;
        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        return false;
    }

}
?>
