(function($) {
    $.fn.tabs = function(options) {
        var defaults = {

        };
        var settings = $.extend({}, defaults, options);
        if (this.length > 1) {
            this.each(function() { $(this).tabs(options) });
            return this;
        }

        var current = 0;
        var push = true;
        var togglers = this.find('[data-tab-toggler]');
        var tabs = this.find('[data-tab]');



        var showTab = function(_self) {

            var hash = _self.prop('hash');

            togglers.removeClass('active');
            tabs.removeClass('active');

            if (!push) {
                history.pushState({}, '', hash);
            }

            tabs.filter('[data-tab="'+hash.substr(1)+'"]').addClass('active');
            _self.addClass('active');

            push = false;
        }

        var checkHash = function () {
            var h = (window.location.href.indexOf("#") > -1) ? window.location.href.split("#").pop() : false;
            if(h) {
                togglers.filter('[href="#'+h+'"]').click();
            } else {
                togglers.first().click();
            }
        }


        // public methods
        this.initialize = function () {
            togglers.on('click', function(ev){
                showTab($(this));
                return false;
            });

            $(window).on("popstate", function() {
    			push = true;
    			checkHash();
    		});

            checkHash();

            return this;
        };

        return this.initialize();
    }
})(jQuery);
