<?php
extract($options);
if (empty($backUrl) && empty($tableControls) && empty($actions) && empty($pathway)) return;
?>

<div class="controls">


    <?php if (!empty($backUrl)): ?>
        <a class="controls__back" href="<?= $this->Url->build($backUrl) ?>">
            <?= $this->Backend->materialIcon('arrow_back') ?> <?= __d('admin', 'go back') ?>
        </a>
    <?php endif; ?>




    <?php if (!empty($tableControls)): ?>
        <div class="controls__form">
            <div class="controls__form__search">
                <?= $this->Table->search() ?>
            </div>

            <?php if (!empty($filters)): ?>
                <?php foreach ($filters as $filter ): ?>
                    <?php if (!empty($filter['options'])): ?>
                        <div class="controls__form__filter">
                            <label><?= $filter['label'] ?></label>
                            <select v-on:change="applyFilter($event, '<?= $filter['field']?>')">
                                <?php if (!empty($filter['empty'])): ?>
                                    <option value=""><?= $filter['empty'] ?></option>
                                <?php endif; ?>

                                <?php foreach ($filter['options'] as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>

            <div class="controls__form__rows">
                <?= $this->Table->rowsPerPage() ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($actions)): ?>
        <div class="controls__actions">
            <?php foreach ($actions as $action):
                $params = ['class' => 'btn--primary', 'tag' => 'a', 'url' => $action['url']];
                if (!empty($action['target'])) $params['target'] = $action['target'];
                ?>
                <?= $this->Backend->iconButton($action['title'], $action['icon'], $params) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
