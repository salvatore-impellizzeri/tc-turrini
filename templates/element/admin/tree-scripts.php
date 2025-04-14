<?php $this->Html->scriptStart(['block' => 'scriptBottom']) ?>

var nestedSortables = [].slice.call(document.querySelectorAll('[data-tree-list]'));

// Loop through each nested sortable element
for (var i = 0; i < nestedSortables.length; i++) {
	new Sortable(nestedSortables[i], {
		group: {
			name: 'nested',
			put : function(to){
				var maxDepth = to.el.getAttribute('data-max-depth'),
					domEl = $('[data-tree-list][data-parent="' + to.el.getAttribute('data-parent') + '"]'),
					depth = domEl.parents('[data-tree-list]').length;

				return depth < maxDepth;
			}
		},
		animation: 150,
		fallbackOnBody: true,
		swapThreshold: 0.65,
        handle: '[data-tree-list-drag]',
        ghostClass: 'tree-list__ghost',
        onEnd: function(ev){
            let oldIndex = ev.oldIndex,
                newIndex = ev.newIndex,
                oldParent = ev.from.getAttribute('data-parent'),
                newParent = ev.to.getAttribute('data-parent'),
                id = ev.item.getAttribute('data-id'),
                offset = newIndex - oldIndex;
				movedObject = ev.item.querySelector('.tree-list__row');

            if (newParent != oldParent) {
                let siblingsCount  = ev.to.querySelectorAll(':scope > li').length;
                offset = parseInt(((siblingsCount - 1) - newIndex) * -1);
            }

			movedObject.classList.remove('tree-list__row--moved');
			movedObject.classList.add('tree-list__row--moved');

            $.ajax({
                dataType: 'json',
                cache: false,
                url: '<?= $this->Url->build(['action' => 'treeMove', '_ext' => 'json']) ?>',
                data: {
                    id : id,
                    newParent : newParent,
                    oldParent : oldParent,
                    offset : offset,
                },
                success: function(result){
                    // successo
                }
            });

        }
	});
}
<?php $this->Html->scriptEnd() ?>
