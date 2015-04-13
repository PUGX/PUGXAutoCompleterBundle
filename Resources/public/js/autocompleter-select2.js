(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get:  ''
        };
        return this.each(function () {
            if (options) {
                $.extend(settings, options);
            }
            var $this = $(this), $fakeInput = $('<input type="text" name="fake' + $this.attr('name') + '">');
            $this.hide().after($fakeInput);
            $fakeInput.select2({
              ajax:{
                url: settings.url_list,
                dataType: 'json',
                delay: 250,
                data: function(params){
                  return {
                    q: params.term,
                    page: params.page
                  }
                },
                processResult:function(data,params){
                  params.page = params.page || 1;
                  return {
                    results: data.items,
                    pagination: {
                      more: (params.page * 30) < data.total_count
                    }
                  }
                },
                cache: true
              },
              escapeMarkup: function(markup){return markup;},
              minimumInputLength: 2

            })
            if ($this.val() != '') {
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
