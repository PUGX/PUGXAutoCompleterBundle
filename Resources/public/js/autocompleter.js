(function($) {
    $.fn.autocompleter = function(options) {
        var settings = {
            url_list: '',
            url_get:  ''
        };
        return this.each(function() {
            if (options) {
                $.extend(settings, options);
            }
            var $this = $(this);
            var $fakeInput = $('<input type="text" name="fake' + $this.attr('name') + '">');
            $this.hide().after($fakeInput);
            $fakeInput.autocomplete({
                source: settings.url_list,
                select: function(event, ui) {
                    $this.val(ui.item.id);
                }
            });
            if ($this.val() != '') {
                $.ajax({
                    url:     settings.url_get + $this.val(),
                    success: function(name) {
                        $fakeInput.val(name)
                    }
                });
            }
        });
    };
})(jQuery);