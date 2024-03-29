let ready = $(document).ready(function() {
    let $navbar = $(".navbar-auth");

    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    if ($("#container-forgotten-password").length || $("#container-reset-password").length) {
        $navbar.addClass("top-nav-collapse");
    } else {
        if ($navbar.offset().top > 10) {
            $navbar.addClass("top-nav-collapse");
        }
        $(window).scroll(function () {
            if ($(this).scrollTop() > 1) {
                $navbar.addClass("top-nav-collapse");
            } else {
                $navbar.removeClass("top-nav-collapse");
            }
        });
    }
    /*
    $(window).scroll(function() {
        let scrolledY = $(window).scrollTop();
        $('.authorize-background').css('background-position', 'center '+ ((scrolledY)) + 'px');
    });
    */

    $('.js-datepicker').datepicker({
        todayHighlight: true,
        format: 'dd-mm-yyyy',
        clearBtn: true,
        language : 'ru',
        position: 'top left'
    }).on('change', function(){
        $('.datepicker').hide();
    });
    //$.datepicker.defaults($.fn.datepicker.dates['ru']);

    $('#btn-info-module').click(function (){
        for (let i = 0; i<$('.info-module').length; i++) {
            $('.info-module')[i].classList.toggle('d-none');
        }
        $('#btn-info-module')[0].classList.toggle('active');
        $('#btn-info-module')[0].blur();
    });

    $('#btn-notifications').click(function() {
        $('#notifications')[0].classList.toggle('d-none');
        $('#btn-notifications')[0].classList.toggle('active');
        $('#btn-notifications')[0].blur();
        /*
        if ($('#notifications')[0].classList.contains('d-none')) {
            $('#notifications')[0].classList.remove('d-none');
        } else {
            $('#notifications')[0].classList.add('d-none');
        }
        */
    });

    $('#btn-feedback').click(function() {
        $('#feedback')[0].classList.toggle('d-none');
        $('#btn-feedback')[0].classList.toggle('active');
        $('#btn-feedback')[0].blur();
    });

    $('#btn-help').click(function() {
        $('#help')[0].classList.toggle('d-none');
        $('#btn-help')[0].classList.toggle('active');
        $('#btn-help')[0].blur();
    });

    $('body').on('change', '#user_department', function() {
        let $form = $(this).closest('form');
        let data = {};
        data['department'] = $('#user_department').val();

        console.log(data['department']);

        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : data,
            success : function(html) {
                $('[data-toggle="tooltip"]').tooltip('hide');
                $('#user_positions').replaceWith($(html).find('#user_positions'));
            }
        });
    });

    $('body').on('change', '#user_faculty', function() {
        let $form = $(this).closest('form');
        let data = {};
        data['faculty'] = $('#user_faculty').val();
        data['speciality'] = null;

        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : data,
            success : function(html) {
                $('[data-toggle="tooltip"]').tooltip('hide');
                $('#user_speciality').replaceWith(
                    $(html).find('#user_speciality')
                );
                $('#user_group').replaceWith(
                    $(html).find('#user_group')
                );
            }
        });
    });

    $('body').on('change', '#user_speciality', function() {
        let $form = $(this).closest('form');
        let data = {};
        data['speciality'] = $('#user_speciality').val();

        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : data,
            success : function(html) {
                $('[data-toggle="tooltip"]').tooltip('hide');
                $('#user_group').replaceWith(
                    $(html).find('#user_group')
                );
            }
        });
    });

    $('input[name="user[userkind]"]').on('click', function() {
        let val = $('input[name="user[userkind]"]:checked').val();
        switch(val) {
            case '2':
                $('#colleague-department').addClass('invisible');
                $('#colleague-position').addClass('invisible');
                $('#colleague-date-start').addClass('invisible');
                $('#student-faculty').removeClass('invisible');
                $('#student-speciality').removeClass('invisible');
                $('#student-group').removeClass('invisible');
                $('#student-date-start').removeClass('invisible');
                break;
            case '3':
                $('#colleague-department').removeClass('invisible');
                $('#colleague-position').removeClass('invisible');
                $('#colleague-date-start').removeClass('invisible');
                $('#student-faculty').addClass('invisible');
                $('#student-speciality').addClass('invisible');
                $('#student-group').addClass('invisible');
                $('#student-date-start').addClass('invisible');
                break;
            case '4':
                $('#colleague-department').addClass('invisible');
                $('#colleague-position').addClass('invisible');
                $('#colleague-date-start').addClass('invisible');
                $('#student-faculty').addClass('invisible');
                $('#student-speciality').addClass('invisible');
                $('#student-group').addClass('invisible');
                $('#student-date-start').addClass('invisible');
        }
    });

    let wow = new WOW.WOW(
        {
            boxClass:     'wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset:       0,          // distance to the element when triggering the animation (default is 0)
            mobile:       true,       // trigger animations on mobile devices (default is true)
            live:         true,       // act on asynchronously loaded content (default is true)
            callback:     function(box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null // optional scroll container selector, otherwise use window
        }
    );
    wow.init();
});