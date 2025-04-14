<?php
use Cake\Core\Configure;

$this->Html->script('/admin-assets/js/lodash.min.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/vue.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/axios.min.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/vuedraggable.umd.min.js', ['block' => 'scriptBottom']);
?>

<?php
//recupero i parametri di default dalla sessione
$defaults = [
    'page' => 0,
    'rowsPerPage' => 10,
    'order' => null,
    'search' => ''
];
$plugin = $this->request->getParam('plugin');
$controller = $this->request->getParam('controller');
$sessionKey = !empty($plugin) ? $plugin . '.' . $controller : $controller;
$settings = $this->request->getSession()->read('Filters.'.$sessionKey);
?>

<script>
<?php
if (!empty($settings)) $defaults = array_merge($defaults, $settings);

$this->Html->scriptStart(['block' => 'scriptBottom', 'type' => 'module']);
?>

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;

let tableDefaultFilters = <?= !empty($conditions) ? json_encode($conditions) : '{}' ?>;

let table = new Vue({
    el: '#app',
    data :  {
        allRecords: [],
        page: <?= $defaults['page'] ?>,
        total: null,
        rowsPerPage: <?= $defaults['rowsPerPage'] ?>,
        rowsPerPageOptions: [1,10,25,50,100],
        orderBy: <?= !empty($defaults['order']) ? $defaults['order'] : 'null' ?>,
        search: '<?= $defaults['search'] ?>',
        filters: {},
        loading: true,
		preloading: true,
        languages: <?= json_encode(Configure::read('Setup.languages')) ?>
    },
    components: {
        draggable: window['vuedraggable']
    },
    mounted : function () {
        this.refresh();
    },
    computed: {
        totalPages() {
            return Math.ceil( this.total / this.rowsPerPage );
        },
        records() {
            if (!this.allRecords.length) return [];
            let startIndex = this.page * this.rowsPerPage;
        
            return this.allRecords.slice(startIndex, startIndex + this.rowsPerPage);
        }
    },
    methods: {
        refresh : function () {
            let getFilters = Object.assign({}, tableDefaultFilters, this.filters);

            this.allRecords = [];
            this.loading = true;
			
			axios.get('<?= $this->Url->build(['action' => 'get.json']) ?>', {
				params: {
					order: this.orderBy === null ? {} : JSON.stringify(this.orderBy),
					search: this.search,
					filters: getFilters
				}
			})
			.then(function (response) {
				table.loading = false;
				table.preloading = false;
				if (response.status == 200){
                    table.total = response.data.length;
					response.data.forEach( el => {
						table.allRecords.push(el);
					})
				}
			})
			.catch(function (error) {
				console.log(error);
			});
        },
        toggleField : function (id,field) {
            let recordIndex = this.allRecords.findIndex(el => el.id === id);
            if (recordIndex == -1) return false; 

            table.allRecords[recordIndex][field] = !table.allRecords[recordIndex][field];

            axios.get('<?= $this->Url->build(['action' => 'toggleField.json']) ?>', {
                params: {
                    id: id,
                    field: field
                }
            })
            .then(function (response) {
                if (response.status == 200 && response.data.newValue != table.allRecords[recordIndex][field]){
                    table.allRecords[recordIndex][field] = response.data.newValue;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        deleteRecord: function (id) {
            Swal.fire({
                title: "<?=  __d('admin', 'delete confirm')?>",
                showCancelButton: true,
                confirmButtonText: "<?=  __d('admin', 'yes')?>",
                cancelButtonText: "<?=  __d('admin', 'undo')?>"
            }).then((result) => {
                if (!result.isConfirmed) return;

                axios.get('<?= $this->Url->build(['action' => 'deleteRecord.json']) ?>', {
                    params: {
                        id: id
                    }
                })
                .then(function (response) {
                    if (response.status == 200){
                        if (response.data.delete) {
                            let recordIndex = table.allRecords.findIndex(el => el.id === id);
                            if (id != -1) table.allRecords.splice(recordIndex,1);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.data.message,
                            });
                        }
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            });
   

        },
        goToPage: function (newPage) {
            if (newPage >= 0 && newPage < this.totalPages){
                this.page = newPage;
            }
        },
        sortBy: function(field) {
            if (field && field.length){
                let orderField = field,
                    orderDir =  this.orderBy !== null && this.orderBy.field == field && this.orderBy.dir == 'ASC' ? 'DESC' : 'ASC';

                this.orderBy = {
                    field: orderField,
                    dir: orderDir
                };
            } else {
                this.orderBy = null;
            }


        },
        updateOrder: function() {
            let min = Math.min.apply(Math, this.allRecords.map( el =>  { return el.position; })),
                data = {};

            this.allRecords.forEach((el,i) => {
                data[el.id] = min;
                el.position = min;
                min++;
            });

            axios.put('<?= $this->Url->build(['action' => 'updatePosition', '_ext' => 'json']) ?>', {
                params: {
                    position: data
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        applyFilter: function(ev, field) {
            let value = ev.target.value;

            if (value) {
                this.filters[field] = value;
            } else {
                delete this.filters[field];
            }
            this.refresh();
        },
        formatDateTime: function(date) {
            let jsdate = new Date(date);
            return jsdate.toLocaleDateString('it-IT', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        formatDate: function(date) {
            let jsdate = new Date(date);
            return jsdate.toLocaleDateString('it-IT', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        },
        getTranslationStatus: function(id, lang) {
            let index = table.allRecords.findIndex(el => el.id === id);

            if (index != -1) {
                let record = this.allRecords[index];
                if (!('_translations' in record) || !(lang in record._translations)) return 'translation-status__language--missing';
                return record._translations[lang]._status == 1 ? 'translation-status__language--complete' : 'translation-status__language--partial';
            }
        },
        saveStatus: function() {
            axios.get('<?= $this->Url->build(['action' => 'saveIndexFilters', '_ext' => 'json']) ?>', {
                params: {
                    page: table.page,
                    rowsPerPage: table.rowsPerPage,
                    order: table.orderBy === null ? {} : JSON.stringify(table.orderBy),
                    search: table.search,
                }
            })
            .then(function (response) {
                if (response.status == 200){
                    //console.log(response);
                }

            })
            .catch(function (error) {
                console.log(error);
            });
        },
        isCurrentSortField: function(field) {
            if (this.orderBy === null || this.isEmptyObj(this.orderBy)) return false;
            return this.orderBy.field == field ? this.orderBy.dir.toLowerCase() : false;
        },
        isEmptyObj: function(obj) {
            for(var prop in obj) {
                if(Object.prototype.hasOwnProperty.call(obj, prop)) {
                    return false;
                }
            }

            return JSON.stringify(obj) === JSON.stringify({});
        },
        disableSort() {
            return !(this.orderBy === null || this.isEmptyObj(this.orderBy)) || this.search.length > 0 || !this.isEmptyObj(this.filters);
        }
    },
    watch: {
        page: function(o,n){
            if (o != n){
                
                this.saveStatus();
            }
        },
        orderBy: function(o,n){
            if (o != n){
                this.refresh();
                this.saveStatus();
            }
        },
        rowsPerPage: function(o,n){
            if (o != n){
                this.page = 0;
                this.saveStatus();
            }
        },
        search: _.debounce(function(o,n){
            if (o != n){
                this.page = 0;
                this.refresh();
                this.saveStatus();
            }
        }, 700),
    },
    
});
<?php
$this->Html->scriptEnd();
?>
</script>