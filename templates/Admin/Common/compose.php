<?php
use Cake\Http\ServerRequest;
use Cake\Core\Configure;

$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'title' => $item->title
]);

$this->set('controlsSettings', [
    'actions' => [
        [
            'title' => __d('admin', 'edit'),
            'icon' => 'edit',
            'url' => [
                'action' => 'edit',
                $item->id
            ]
        ],
        [
            'title' => __d('admin', 'preview action'),
            'icon' => 'pageview',
            'url' => [
                'prefix' => false,
                'action' => 'view',
                $item->id,
                '?' => ['forcePreview' => true]
            ],
            'target' => '_blank'
        ]
    ]
])
?>

<div class="page-builder" id="pageBuilder">
    <div class="page-builder__blocks">
        <div class="page-builder__blocks__label">
            <?= __d('admin', 'available blocks') ?>
        </div>
        <draggable
            class="page-builder__blocks__collection"
            :list="blockTypes"
            :group="{ name: 'blocks', pull: 'clone', put: false }"
            :sort="false"
            :clone="cloneBlock"
            handle="[drag-handle]"
        >
            <div class="page-builder__block" v-for="blockType in blockTypes" :key="blockType.id">
                <div class="page-builder__block__drag" drag-handle>
                    <?= $this->Backend->materialIcon('drag_indicator') ?>
                </div>
                <div class="page-builder__block__icon" v-if="blockType.icon !== null">
                    <img v-bind:src="blockType.icon.backend_path" alt="">
                </div>
                <div class="page-builder__block__title">
                    {{ blockType.title }}
                </div>

            </div>
        </draggable>

    </div>

    <div class="page-builder__page">

        <div class="page-builder__page__empty" v-if="!blocks.length">
            <div class="page-builder__page__empty__icon">
                <?= $this->Backend->materialIcon('highlight_alt') ?>
            </div>
            <div class="page-builder__page__empty__label">
                <?= __d('admin', 'drag blocks here') ?>
            </div>

        </div>

        <draggable
            class="page-builder__dropzone"
            :list="blocks"
            group="blocks"
            @add="addContentBlock"
            @update="updateOrder"
            handle="[drag-handle]"
        >

            <div class="page-builder__block" v-for="block in blocks">
                <div class="page-builder__block__drag" drag-handle>
                    <?= $this->Backend->materialIcon('drag_indicator') ?>
                </div>

                <div class="page-builder__block__title">
                    {{ block.title }}
                </div>

                <?php /*
                <div class="page-builder__block__translations">
                    <div class="translation-status">
                        <?= $this->Html->tag('a', '{{language}}', [
                            'v-for' => 'language in languages',
                            'class' => 'translation-status__language',
                            'v-bind:class' => 'getTranslationStatus(block.id, language)',
                            'v-bind:href' => "'" . $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'edit']) . "/' + block.id + '?lang=' + language"
                        ]); ?>
                    </div>
                </div>
                */ ?>

                <div class="page-builder__block__actions">
                    <a v-bind:href="'<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'edit']) ?>' + '/' + block.id">
                        <?php echo $this->Backend->materialIcon('edit') ?>
                    </a>
                    <span @click="deleteContenBlock(block.id)">
                        <?php echo $this->Backend->materialIcon('delete') ?>
                    </span>
                </div>

            </div>

        </draggable>

    </div>
</div>

<?php
$this->Html->script('/admin-assets/js/vue.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/axios.min.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/vuedraggable.umd.min.js', ['block' => 'scriptBottom']);
?>

<?php
$this->Html->scriptStart(['block' => 'scriptBottom', 'type' => 'module']);
?>

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;

let pageBuilder = new Vue({
    el: '#pageBuilder',
    data: {
        blockTypes: [],
        blocks: [],
        drag: false,
        requestParams: {
            plugin: '<?= $this->request->getParam('plugin') ?>',
            model: '<?= $model ?>',
            ref: '<?= $item->id ?>',
        },
        languages: <?= json_encode(Configure::read('Setup.languages')) ?>
    },
    components: {
        draggable: window['vuedraggable']
    },
    created: function() {
        this.getContentBlockTypes();
        this.getContentBlocks();
    },
    methods: {
        cloneBlock: function (item) {
            return {
                id: null,
                title: item.title,
                content_block_type_id: item.id
            }
        },

        addContentBlock: function (ev) {
            let currentBlock = this.blocks[ev.newIndex];

            axios.put('<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'addBlock', '_ext' => 'json']) ?>', {
                title: currentBlock.title,
                plugin: pageBuilder.requestParams.plugin,
                model: pageBuilder.requestParams.model,
                ref: pageBuilder.requestParams.ref,
                content_block_type_id: currentBlock.content_block_type_id
            })
            .then(function(response){
                pageBuilder.blocks[ev.newIndex].id = response.data.id;

                // aggiorno l'ordinamento
                pageBuilder.updateOrder();

            });
        },

        deleteContenBlock: function (id) {
            if (confirm('<?=  __d('admin', 'delete confirm')?>')){
                axios.get('<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'deleteRecord.json']) ?>', {
                    params: {
                        id: id
                    }
                })
                .then(function (response) {
                    if (response.status == 200){
                        let recordIndex = pageBuilder.blocks.findIndex(el => el.id === id);

                        if (id != -1) {
                            pageBuilder.blocks.splice(recordIndex,1);
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }

        },

        updateOrder: function() {
            let data = {},
                position = 0;

            this.blocks.forEach((el,i) => {
                data[el.id] = position;
                position++;
            });

            axios.put('<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'updatePosition', '_ext' => 'json']) ?>', {
                params: {
                    position: data
                }
            });
        },

        getContentBlockTypes: function () {
            axios.get('<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlockTypes', 'action' => 'get', '_ext' => 'json']) ?>', {
                params: {
                    rowsPerPage: 1000,
                    filters: {
                        'ContentBlockTypes.plugin': '<?= $this->request->getParam('plugin') ?>',
                        'ContentBlockTypes.published': true,
                    }
                }
            })
            .then(function(response){
                if (response.status == 200){
                    pageBuilder.blockTypes = [];
                    response.data.forEach( el => {
                        pageBuilder.blockTypes.push(el);
                    })
                }
            })
        },

        getContentBlocks: function () {
            axios.get('<?= $this->Url->build(['plugin' => 'ContentBlocks', 'controller' => 'ContentBlocks', 'action' => 'get', '_ext' => 'json']) ?>', {
                params: {
                    plugin: this.requestParams.plugin,
                    model: this.requestParams.model,
                    ref: this.requestParams.ref,
                }

            })
            .then(function(response){
                if (response.status == 200){
                    pageBuilder.blocks = [];
                    response.data.forEach( el => {
                        pageBuilder.blocks.push(el);
                    });
                }
            })
        },

        getTranslationStatus: function(id, lang){
            let index = this.blocks.findIndex(el => el.id === id);

            if (index != -1) {
                let record = this.blocks[index];
                if (!('_translations' in record) || !(lang in record._translations)) return 'translation-status__language--missing';
                return record._translations[lang]._status == 1 ? 'translation-status__language--complete' : 'translation-status__language--partial';
            }
        }


    }



})

<?php
$this->Html->scriptEnd();
?>
