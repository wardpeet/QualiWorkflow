var url = "";
if(window.location.hostname == "localhost") {
    url = "/o-one-o/backend";
}

Modernizr.load();

;
$.fn.core = function() {
    var core = this;
    return {
        navigation: function() {
            return core.each(function() {
                $('> ul > li > a', this).each(function() {
                    if($(this).parent().has('ul').length) {
                        $(this).bind('click', function() {
                            $el = $(this).parent().find('ul');
                            if(!$el.is(':visible')) {
                                $('li ul').slideUp();
                                $el.slideDown();
                            }

                            return false;
                        });
                    }
                });
            });
        },
        'checkAll': function() {
            return core.each(function() {
                $(this).bind('click', function() {
                    var checked = ($(this).attr('checked') ? true : false);
                    $('.' + this.id).each(function() {
                        $(this).attr('checked', checked);
                    });
                });
            });
        }
    }
}

;
$(function() {
    $('.tip').tipsy({
        gravity: 'n'
    });
    $('.tip-w').tipsy({
        gravity: 'w'
    });

    $('#navigation').core().navigation();
    $('nav .active ul').show();

    //$('#check1').core().checkAll();
});