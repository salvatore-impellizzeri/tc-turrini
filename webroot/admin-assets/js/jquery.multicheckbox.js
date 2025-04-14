(function($) {
    $.fn.multiCheckbox = function(options) {
        var defaults = {

        };
        var settings = $.extend({}, defaults, options);

        if(!this.length) return;

        if (this.length > 1) {
            this.each(function() { $(this).multiCheckbox(options) });
            return this;
        }

        var page = 0;
        var sortable = null;
        var tagsContainer = this.find('[data-multicheckbox-selected]').first();
        var popup = this.find('[data-multicheckbox-popup]').first();
        var search = this.find('[data-multicheckbox-search]').first();
        var options = this.find('.checkbox');
        var wrapper = this.find('[data-multicheckbox-wrapper]').first();
        var inputsContainer = this.find('[data-multicheckbox-inputs]').first();
        var inputs = this.find('input[type="checkbox"]');
        var popupToggler = this.find('[data-multicheckbox-add]').first();
        var url = this.data('multicheckbox-url');
        var fieldName = this.data('field');
        var active = this.find('[data-multicheckbox-preselected]').first().val().split(',');



        var addTag = function (label, id){
            var position = 0;

            tagsContainer.find('[data-position]').each(function(i,el){
                if (parseInt($(el).val()) > position) position = parseInt($(el).val());
            });

            position++;

            // in caso di modifica modificare anche BackendHelper
            tagsContainer.append("<span class='multiCheckbox__tag' data-multicheckbox-tag>"+label+" <span class='multiCheckbox__tag__remove material-symbols-rounded' data-multicheckbox-remove data-id='"+id+"'>close</span><input type='hidden' name='"+fieldName+"["+position+"][id]' value='"+id+"'><input type='hidden' name='"+fieldName+"["+position+"][_joinData][position]' value='"+position+"' data-position></span>");
        }

        var removeTag = function (id){
            let tag = $('[data-multicheckbox-remove]').filter('[data-id="' + id + '"]');
            tag.parent().remove();
        }

        var getCheckbox = function(reset = false) {
            if (reset) {
                wrapper.addClass('loading');
                inputsContainer.empty();
                page = 0;
            }

            $.ajax({
        		url : url,
        		method: 'POST',
        		data : {
                    checked: active,
                    search: search.val(),
                    page: page
                },
        		headers: {
        			'X-CSRF-Token': window.csrfToken
        		},
                beforeSend: function(){
        			wrapper.off('scroll');
        		},
                success: function(response) {
                    if (response.length) {
                        if (reset) {
                            wrapper.removeClass('loading');
                        }
                        inputsContainer.append(response);

                        wrapper.on('scroll', function(){
                            if (((inputsContainer.height() - wrapper.scrollTop()) - wrapper.height()) < 100) {
                                page++;
                                getCheckbox();
                            }

                        });

                    } else {
                        wrapper.removeClass('loading');
                    }
                }
            });

        }

        // public methods
        this.initialize = function () {
            let searchTimer;

            getCheckbox(true);

            // abilita ricerca con delay
            search.on('input', function(){
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function(){
                    getCheckbox(true);
                }, 500);
            });

            // rimozione di un tag
            tagsContainer.on('click', '[data-multicheckbox-remove]', function(ev){
                let input = inputs.filter('[value="' + $(this).data('id') + '"]').first();
                input.prop('checked', false);
                $(this).parent().remove();
            });

            // aggiunta di un tag
            inputsContainer.on('click', '[data-value]', function(ev){
                if ( !$(this).attr('data-checked') ||  $(this).attr('data-checked') == 0) {
                    $(this).attr('data-checked', 1);
                    addTag($(this).find('[data-value-label]').first().text(), $(this).data('value'));
                } else {
                    $(this).attr('data-checked', 0);
                    removeTag($(this).data('value'));
                }
            });


            //apertura popup
            popupToggler.click(function(){
                popup.addClass('visible');
                wrapper.scrollTop(0);
            });


            //chiusura popup
            $('[data-multicheckbox-close]').click(function(){
                popup.removeClass('visible');
                wrapper.scrollTop(0);
            });

            //ordinamento
            Sortable.create(tagsContainer[0], {
                onEnd: function (ev) {
            		let newPosition = 0;
                    tagsContainer.find('[data-position]').each(function(){
                        $(this).val(newPosition);
                        newPosition++;
                    });
            	}
            });


            return this;
        };

        return this.initialize();
    }
})(jQuery);
