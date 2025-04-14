<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

if(!empty($id)){
	
	$snippet = FactoryLocator::get('Table')->get('Snippets.Snippets')->get($id);

	if(!empty($snippet)){
		if(!empty($showTitle)) echo $this->Html->tag($showTitle, str_replace('\\', '<br>', $snippet->title));
		echo $snippet->text;
	}
}