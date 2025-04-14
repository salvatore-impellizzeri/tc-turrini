<!-- La ricerca è attualmente tarata sul plugin prodotti. Riga 58 -->
<div class="search" data-search>
    <span class="search__toggler" data-search-toggler>
        <?= $this->Frontend->svg('icons/search.svg'); ?>
    </span>
    <div class="search__wrapper">
        <span class="search__close" data-search-toggler></span>
        <div class="search__form">
            <span class="search__icon">
                <?= $this->Frontend->svg('icons/search.svg'); ?>
            </span>
            <input type="text" class="search__input" placeholder="<?php echo __d('global', 'search')?>" data-search-input>
            <span data-search-spinner class="search__spinner"><?= $this->Frontend->svg('icons/spinner.svg') ?></span>
        </div>
        <div class="search__results" data-search-results>

        </div>
    </div>
    
</div>


<?php echo $this->Html->scriptStart(['block' => 'scriptBottom']) ?>
$(function() {

	$('[data-search]').each(function(i,el){

		let timedSearch = null,
            $search = $(el),
			$searchInput = $('[data-search-input]', el),
            $seachToggeler = $('[data-search-toggler]', el),
			$resultsContainer = $('[data-search-results]', el),
			$spinner = $('[data-search-spinner]', el),
            isOpen = false;


        doSearch = function() {
            var keyword = $searchInput.val();
            

            if (keyword.length >= 3) {
                $spinner.addClass('loading');

                $.ajax({
                    url : '<?= $this->Frontend->url('/search/find') ?>',
                    type : 'POST',
                    data : {key: keyword},
                    localCache : false,
                    cacheTTL : 1,           // Optional. In hours.
                    cacheKey : 'SearchHistory.' + keyword,      // optional.
                    headers: { 
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    isCacheValid : function(){  // optional.
                        return true;
                    },
                    success : function(response) {
                        $resultsContainer.html(response).addClass('visible');
                        $spinner.removeClass('loading');
                        isOpen = true;
                    }
                });
            } else {
                console.log('else');
                $resultsContainer.empty().removeClass('visible');
                $spinner.removeClass('loading');
                isOpen = false;
            }


        };

        $searchInput.on('input paste',
            function(){
                if (timedSearch !== null) {
                    clearTimeout(timedSearch);
                }
                timedSearch = setTimeout(function () { doSearch(); }, 250);
            }
        );

        $(document).on('click', function(event) { 
            var $target = $(event.target);
            if(!$target.closest('[data-search]').length && isOpen) {
                $resultsContainer.empty().removeClass('visible');
                $spinner.removeClass('loading');
                $searchInput.val('');
                isOpen = false;
            }        
        });

        $seachToggeler.on('click', function(ev){
            $resultsContainer.empty().removeClass('visible');
            $searchInput.val('');
            $search.toggleClass('open');
        });

	});


});
<?php echo $this->Html->scriptEnd() ?>