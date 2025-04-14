<?php
/* src/View/Helper/LinkHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;

class TableHelper extends Helper
{

    public array $helpers = [
        'Html',
        'Form',
        'Url',
        'Backend'
    ];

    public function start(){
        return '<table class="table" v-bind:class="{ \'table--loading\': loading }">';
    }

    public function end(){
        return '</table>';
    }

    public function paginator(){
        $prev = $this->Html->div(
            'prev',
            $this->Backend->materialIcon('chevron_left').' '.__d('admin', 'previous'),
            ['@click' => 'goToPage( page-1 )', 'v-bind:class' => "[ loading || page < 1 ? 'prev--disabled' : '' ]"]
        );

        $page = $this->Html->tag('div', '{{ value }}', [
            'v-for' => 'value in totalPages',
            '@click' => 'goToPage( value - 1 )',
            'v-bind:class' => "[ page === value - 1 ? 'page active' : 'page' ]"
        ]);

        $pages = $this->Html->div('pagination__pages', $page);

        $next = $this->Html->div(
            'next',
            __d('admin', 'next').' '.$this->Backend->materialIcon('chevron_right'),
            ['@click' => 'goToPage( page+1 )', 'v-bind:class' => "[ loading || page + 1 >= totalPages ? 'next--disabled' : '' ]"]
        );

        return $this->Html->div('pagination', $prev.$pages.$next);
    }

    public function defaultSort(){
        return $this->Html->tag('span', $this->Backend->materialIcon('format_list_numbered'), [
            '@click' => "sortBy(null)",
            'class' => 'table__default-sort',
            'v-bind:class' => '{ active : !disableSort() }'
        ]);
    }

    public function sort($label, $sortField){
        return $this->Html->tag('span', $label.' '.$this->Backend->svg('sort.svg'), [
            '@click' => "sortBy('" . $sortField . "')",
            'class' => 'table__sort',
            'v-bind:class' => "[ isCurrentSortField('" . $sortField . "') ? isCurrentSortField('" . $sortField . "') : '' ]"
        ]);
    }

    public function search(){
        return $this->Html->div('input input--icon input--search', $this->Form->text('search', ['placeholder' => __d('admin', 'search'), 'v-model' => 'search', 'class' => '']));
    }

    public function rowsPerPage(){
        $options = $this->Html->tag('option', '{{ option }}', [
            'v-for' => 'option in rowsPerPageOptions'
        ]);
        $optionsContainer = $this->Html->tag('select', $options, ['v-model' => 'rowsPerPage']);
        $label = $this->Html->tag('label', __d('admin', 'rows per page'));
        return $label.$optionsContainer;
    }

    public function toggler($field){
        return $this->Html->tag('span', $this->Html->tag('span', '', ['class' => 'onoff']), ['@click' => "toggleField(record.id,'".$field."')", 'v-bind:class' => "[ record.".$field." ? 'toggle active' : 'toggle' ]"]);
    }

    // mostra stato Sì / No in base al valore del campo booleano
    public function showStatus($field){
        return $this->Html->tag('span', "{{ record.".$field." ? '". __d('admin', 'yes') ."' : '" . __d('admin', 'no') . "' }}");
    }

    public function editLink($label = 'record.title', $action = 'edit'){
        $editAction = $this->Url->build(['action' => $action]);
        return $this->Html->tag('a', "{{ $label }}", ['v-bind:href' => "'" . $editAction . "/'+record.id"]);
    }

	// Parametri customLink
	// label = string, di default 'record.title' in alternative il percorso per arrivare alle info nel JSON
	// action_path = array, percorso di cake per arrivare all'action
	// id_path = string, di default 'record.id' in alternativa il percorso per arrivare alle info nel JSON
	public function customLink($label = 'record.title', $action_path = null, $id_path = 'record.id')
	{
		if(!empty($action_path) && !empty($id_path)){
			$action = $this->Url->build($action_path);
			return $this->Html->tag('a', "{{ $label }}", ['v-bind:href' => "'" . $action . "/'+" . $id_path]);
		}
    }

    public function editAction($action = 'edit'){
        $editAction = $this->Url->build(['action' => $action]);
        $icon = $this->Backend->materialIcon('edit');
        $tooltip = $this->Html->div('action__tooltip', __d('admin', 'edit'));
        return $this->Html->tag('a', $icon . "\n" . $tooltip, ['v-bind:href' => "'" .$editAction . "/'+record.id", 'class' => 'action']);
    }

    public function viewAction($action = 'view'){
        $action = $this->Url->build(['action' => $action]);
        $icon = $this->Backend->materialIcon('visibility', ['class' => 'material-symbols-rounded--fill']);
        $tooltip = $this->Html->div('action__tooltip', __d('admin', 'view'));
        return $this->Html->tag('a', $icon . "\n" . $tooltip, ['v-bind:href' => "'" .$action . "/'+record.id", 'class' => 'action']);
    }

    public function btn($label = null, $action = '#'){
        return $this->Html->tag('a', $label, ['v-bind:href' => "'$action/' + record.id", 'class' => 'btn btn--primary btn--small']);
    }

    // Parametri customAction
	// action_path = array, percorso di cake per arrivare all'action
	// icon_name = string, nome icona material icon
	// tooltip_name = string, stringa riportata nel file di lingua admin
	// id_path = string, di default 'record.id' in alternativa il percorso per arrivare alle info nel JSON
	public function customAction($action_path = null, $icon_name = null, $tooltip_name = null, $id_path = 'record.id')
	{
		if(!empty($action_path) && !empty($icon_name) && !empty($tooltip_name) && !empty($id_path)){
			$action = $this->Url->build($action_path);
			$icon = $this->Backend->materialIcon($icon_name);
			$tooltip = $this->Html->div('action__tooltip', __d('admin', $tooltip_name));
			return $this->Html->tag('a', $icon . "\n" . $tooltip, ['v-bind:href' => "'" . $action . "/'+" . $id_path , 'class' => 'action']);
		}
    }

    public function deleteAction(){
        $icon = $this->Backend->materialIcon('delete');
        $tooltip = $this->Html->div('action__tooltip', __d('admin', 'delete'));
        return $this->Html->tag('span', $icon . "\n" . $tooltip, ['@click' => 'deleteRecord(record.id)', 'class' => 'action']);
    }

    // inizio tbody per tabelle ordinabili
    public function dragStart(){
        return '<tbody is="draggable" handle=".draggable-handle" :list="allRecords" :disabled="disableSort()" tag="tbody" @change="updateOrder">';
    }

    // fine tbody per tabelle ordinabili
    public function dragEnd(){
        return '</tbody>';
    }

    //handle per tabelle ordinabili
    public function dragHandle(){
        return $this->Html->tag('div', $this->Backend->materialIcon('swap_vert'), ['class' => 'draggable-handle', 'v-bind:disabled' => 'disableSort()']);
    }

    //elemento vuoto viene mostrato se non ci sono risultati
    public function empty(){
        return $this->Html->tag(
            'tr',
            $this->Html->tag('td', __d('admin', 'empty table'), ['colspan' => 100]),
            ['class' => 'table__empty', 'v-if' => '!total && !loading']
        );
    }


    public function translationStatus($action = 'edit'){
        $editAction = $this->Url->build(['action' => $action]);
        $editLink = $this->Html->tag('a', '{{language}}', [
            'v-for' => 'language in languages',
            'class' => 'translation-status__language',
            'v-bind:class' => 'getTranslationStatus(record.id, language)',
            'v-bind:href' => "'" . $editAction . "/' + record.id + '?lang=' + language"
        ]);

        return $this->Html->div('translation-status', $editLink);
    }

}
?>
