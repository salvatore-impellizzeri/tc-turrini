<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'Sliders', 'action' => 'index']
        ],
        [
            'title' => $slider->title ?? $item->slider->title,
            'url' => ['action' => 'list', $slider->id ?? $item->slider_id]
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?= $this->Form->create($item) ?>
    <fieldset class="input-group">
    <legend class="input-group__info">
        Generali
    </legend>
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-11']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'extraClass' => 'span-1']);
        echo $this->element('admin/uploader/image', [
            'scope' => 'preview',
            'title' => __dx('sliders', 'admin', 'preview'),
            'width' => 2560,
            'height' => 1440,
        ]);

        echo $this->Form->editor('text', ['label' => __d('admin', 'text')]);
        echo $this->Form->editor('cta', ['label' => __dx($po, 'admin', 'cta')]);
        echo $this->Form->editor('url', ['label' => __dx($po, 'admin', 'url')]);
		echo $this->Form->hidden('slider_id', ['value' => $this->request->getQuery('slider_id') ?? $item->slider_id]);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
