<?php
use Cake\Utility\Inflector;

$this->extend('/Admin/Common/setupIndex');
$this->set('noScript', true);
$this->set('currentMenuId', 'Translate');
$this->set('controlsSettings', [
    'tableControls' => false,
    'actions' => false
])
?>


<table class="table">
    <thead>
        <tr>
            <th><?= __d('admin', 'title') ?></th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($poFiles as $poFile):
            $title = !empty($poFile['plugin']) ? __dx(Inflector::underscore($poFile['plugin']), 'admin', 'plugin name') : __d('admin', "_po {$poFile['file']}");
            $url = $this->Url->build(['action' => 'customize', $poFile['file'], $poFile['plugin']]);
            ?>
            <tr>
                <th scope="row">
                    <a href="<?= $url ?>">
                        <?php echo $title ?>
                    </a>
                </th>
                <td>
                    <a href="<?= $url ?>" class="btn btn--primary btn--small">
                        <?= __d('admin', 'manage translations') ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
