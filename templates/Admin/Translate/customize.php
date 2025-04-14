<?php

use Cake\Utility\Inflector;

$this->extend('/Admin/Common/setupEdit');
$this->set('currentMenuId', 'Translate');

$this->set('controlsSettings', [
	'pathway' => [
		[
			'title' => __d('admin', 'settings translations'),
			'url' => ['plugin' => false, 'controller' => 'Translate', 'action' => 'index']
		],
		[
			'title' => !empty($pluginName) ? __dx(Inflector::underscore($pluginName), 'admin', 'plugin name') : __d('admin', "_po {$file}")
		],
	]
])
?>

<?= $this->Form->create() ?>
<div class="box">
    <table class="table translate">
        <thead>
            <tr>
                <th align="left" data-locale="<?php echo $defaultLanguage['locale']; ?>">
                    <div class="flag">
											<span class="mini-flag"><img src="/admin-assets/img/flags/<?php echo $defaultLanguage['locale']?>.svg"></span><span class="percent">100%</span>
										</div>
                </th>
                <?php foreach($otherLanguages as $otherLanguage): ?>
                <th align="left" data-locale="<?php echo $otherLanguage['locale']; ?>">
								<div class="flag">
										<span class="mini-flag"><img src="/admin-assets/img/flags/<?php echo $otherLanguage['locale']?>.svg"></span><span class="percent"><?php echo $otherLanguage['percentage']. '%'; ?>
										</span>
									</div>
								</th> <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($defaultLanguage['entries'] as $key => $entry): ?>
            <?php if(empty($entry->getMsgCtxt())): ?>
            <tr>
                <td><?php echo $this->Form->control($defaultLanguage['locale'] . '.entry_' . $key, ['data-hash' => $key, 'data-locale' => $defaultLanguage['locale'], 'label' => false, 'value' => $entry->getMsgStr()]); ?>
                </td>
                <?php foreach($otherLanguages as $otherLanguage): ?>
                <?php $otherEntry = $otherLanguage['entries'][$key]; ?>
                <td><?php echo $this->Form->control($otherLanguage['locale'] . '.entry_' . $key, ['data-hash' => $key, 'data-locale' => $otherLanguage['locale'], 'label' => false, 'value' => $otherEntry->getMsgStr()]); ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<?= $this->Form->end() ?>


<?php echo $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
$(function(){

$.ajaxSetup({
headers:
{ 'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>" }
});

// al change salva il campo
$('input').on('change', function(){

var locale = $(this).data('locale');
var hash = $(this).data('hash');
var msgStr = $(this).val();

var reqData = {
'locale' : locale,
'hash' : hash,
'msgStr' : msgStr,
'pluginName' : '<?= $pluginName ?>',
'file' : '<?= $file ?>'
};
$.post('/admin/translate/saveField', reqData).done(function( data ) {
var totalStrings = $('input[data-locale="' + locale + '"]').length;
var translatedStrings = 0;
$('input[data-locale="' + locale + '"]').each(function(){
if($(this).val() != '') translatedStrings++;
})

var translationPercentage = '' + Math.round(translatedStrings / totalStrings * 100);
$('th[data-locale="' + locale + '"]').find('span.percent').text(translationPercentage + '%');
});
})

// se premiamo invio, salviamo e passiamo al campo successivo nella stessa lingua
$('input').on('keypress', function(event){
var $input = $(this); // input corrente
var $tr = $(this).closest('tr'); // riga corrente
var tdIndex = $(this).closest('td').index(); // indice colonna attuale
var $nextInput = $tr.next().find('td').eq(tdIndex).find('input');

var keycode = (event.keyCode ? event.keyCode : event.which);
if(keycode == 13){
event.stopPropagation();
if($nextInput.length) {
$nextInput.focus();
} else {
$input.blur();
}
}
});

})
<?php echo $this->Html->scriptEnd(); ?>
