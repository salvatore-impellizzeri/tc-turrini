<?php
$this->extend('/Admin/Common/index');
?>


<?= $this->Table->start() ?>
    <thead>
		<th></th>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'question'), 'Questions.question') ?>
        </th>        
        <th></th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'Questions.published') ?>
        </th>
        <th>

        </th>
    </thead>
	
	<?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id">
			<td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row">
                <?= $this->Table->editLink('record.question') ?>
            </th>
            <td>
                <?= $this->Table->translationStatus() ?>
            </td>
            <td>
                <?php echo $this->Table->toggler('published') ?>
            </td>
            <td>
                <div class="table__actions">
                    <?= $this->Table->editAction() ?>
                    <?= $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>
		
	<?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
