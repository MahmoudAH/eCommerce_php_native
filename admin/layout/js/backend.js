$(function() {
    'use strict';
    $('.toggle-info').click(function() {
        $(this).toggleClass('active').parent().siblings().fadeToggle(100);
        if ($(this).hasClass('active')) {
            $(this).html('<i class="fas fa-minus"></i>');
        } else {
            $(this).html('<i class="fas fa-plus"></i>');
        };
    });

    $("input").each(function() {
        if ($(this).attr('required')) {
            $(this).after('<span class ="star" style="color:red">*</span>');
        }

    });
    var passfield = (".password");
    $('.show-pass').hover(function() {
        passfield.attr('type', 'text');
    }, function() {
        passfield.attr('type', 'password');

    });

    $('.confirm').click(function() {
        return confirm('are you sure');
    });


});