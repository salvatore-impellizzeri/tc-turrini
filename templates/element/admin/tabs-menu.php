<?php 
use Cake\Utility\Inflector;
?>
<?= $this->Backend->tabsHeader(
        [
            [
                'title' => __d('admin', 'tabs general'),
                'icon' => 'edit',
                'hash' => 'edit'
            ],
            [
                'title' => __d('admin', 'tabs seo'),
                'icon' => 'insights',
                'hash' => 'seo'
            ],
            [
                'title' => __d('admin', 'tabs social'),
                'icon' => 'share',
                'hash' => 'social'
            ],
        ],
        //'/'. Inflector::dasherize($modelName) .'/view/'. $item['id'] .'?forcePreview=true'
    );
?>