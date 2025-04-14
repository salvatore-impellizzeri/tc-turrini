<?php
return [
    'inputContainer' => '<div class="input {{extraClass}} {{type}}{{required}}" {{data}}>{{content}}{{extraInput}}</div>',    
    'inputContainerError' => '<div class="input {{extraClass}} {{type}}{{required}} error" {{data}}>{{content}}{{extraInput}}{{error}}</div>',
	'label' => '<label{{attrs}}><div class="label-heading"><span class="material-symbols-rounded">{{icon}}</span>{{text}}</div></label>',	
	'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}<span class="toggle"><span class="onoff"></span></span>',
];
?>