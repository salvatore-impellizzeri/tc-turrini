<?php
$this->extend('/Admin/Common/edit');
$this->set('controlsSettings', [
    'pathway' => [
        [
            'title' => $cookieType->title ?? $item->cookie_type->title,
            'url' => ['action' => 'list', $cookieType->id ?? $item->cookie_type_id]
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?= $this->Form->create($item) ?>
    <div class="grid-builder">
        <?php
			echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-8']);
			echo $this->Form->control('alias', ['label' => 'Alias', 'extraClass' => 'span-3']);
			echo $this->Form->control('published', ['label' => __dx($po, 'admin', 'published'), 'extraClass' => 'span-1']);

        ?>
		<div class="editor">
			<?php
				echo $this->Form->control('text', ['label' => __d('admin', 'text')]);
			?>
		</div>
        <?php
			echo $this->Form->hidden('cookie_type_id', ['value' => $this->request->getQuery('cookie_type_id')]);
        ?>
    </div>


    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
