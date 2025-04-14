<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $item
 */
?>
<div class="row">
	<?php foreach($images['gallery'] as $image): ?>
			<img src="<?php echo $this->Frontend->image($image->base_path); ?>" />
	<?php endforeach; ?>
    <div class="column-responsive column-80">
        <div class="articles view content">
            <h3><?= h($item->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($item->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Seotitle') ?></th>
                    <td><?= h($item->seotitle) ?></td>
                </tr>
                <tr>
                    <th><?= __('Seokeywords') ?></th>
                    <td><?= h($item->seokeywords) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($item->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($item->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($item->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Excerpt') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($item->excerpt)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($item->text)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Seodescription') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($item->seodescription)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
