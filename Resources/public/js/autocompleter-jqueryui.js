(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get:  '',
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
                select: function (event, ui) {
                    $this.val(ui.item.id);
                    if (settings.on_select_callback) {
                        settings.on_select_callback($this);
                    }
                },
                minLength: settings.min_length
            });
            if ($this.val() !== '') {
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
