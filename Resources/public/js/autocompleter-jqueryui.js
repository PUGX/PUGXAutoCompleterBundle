(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get:  '',
            url_search: '',
            min_length: 2,
            on_select_callback: null
        };

        return this.each(function () {
            if (options) {
                $.extend(settings, options);
            }
            var $this = $(this), $fakeInput = $this.clone();
            $fakeInput.attr('id', 'fake_' + $fakeInput.attr('id'));
            $fakeInput.attr('name', 'fake_' + $fakeInput.attr('name'));
            $this.hide().after($fakeInput);

            $fakeInput.autocomplete({
                source: settings.url_list,
                search: function(event,ui) {
                    $('#'+ $this.attr('id')).val($fakeInput.val()) ;
                },
                select: function (event, ui) {
                    $this.val(ui.item.id);
                    if($.isNumeric($this.val())){
                        window.location.href = settings.url_get + $this.val();
                    }
                    else
                    {
                        window.location.href = settings.url_list + $this.val();
                    }
                    if (settings.on_select_callback) {
                        settings.on_select_callback($this);
                    }
                },
                minLength: settings.min_length
            });
            if ($this.val() !== '' && $.isNumeric($this.val())) {
                $.ajax({
                    url:     settings.url_get + $this.val(),
                    success: function (name) {
                        $fakeInput.val(name);
                    }
                });
            }
        });
    };
})(jQuery);
