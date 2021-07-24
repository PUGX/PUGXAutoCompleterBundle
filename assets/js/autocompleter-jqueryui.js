(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        let settings = {
            url_list: '',
            url_get:  '',
            min_length: 2,
            on_select_callback: null
        };
        return this.each(function () {
            if (options) {
                $.extend(settings, options);
            }

            let $this = $(this), $fakeInput = $this.clone();
            $fakeInput.attr('id', 'fake_' + $fakeInput.attr('id'));
            $fakeInput.attr('name', 'fake_' + $fakeInput.attr('name'));
            $this.hide().after($fakeInput);
            $fakeInput.autocomplete({
                source: settings.url_list,
                select: function (event, ui) {
                    event.preventDefault();

                    $this.val(ui.item.id);
                    $(this).val(ui.item.label);
                    if (settings.on_select_callback) {
                        settings.on_select_callback($this, event, ui);
                    }
                },
                minLength: settings.min_length
            });
        });
    };
})(jQuery);