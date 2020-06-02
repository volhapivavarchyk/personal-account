import '../scss/app.scss';

// loads the Bootstrap jQuery plugins
//import 'bootstrap/js/dist/dropdown.js';
import 'bootstrap/dist/js/bootstrap.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/transition.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/alert.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/collapse.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/modal.js';
import 'jquery';
import WOW from 'wowjs/dist/wow.js';

let ready = $(document).ready(function() {
    let $navbar = $(".navbar-auth");

    $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
            $navbar.addClass("top-nav-collapse");
        } else {
            $navbar.removeClass("top-nav-collapse");
        }
    });
    /*
    $(window).scroll(function() {
        let scrolledY = $(window).scrollTop();
        $('.authorize-background').css('background-position', 'center '+ ((scrolledY)) + 'px');
    });
    */
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

    $('#user_department').change(function() {
        let $form = $(this).closest('form');
        let data = {};
        //data[$('#user_department').attr('name')] = $('#user_department').val();
        data['department'] = $('#user_department').val();

        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : data,
            success : function(html) {
                $('#user_positions').replaceWith(
                    $(html).find('#user_positions')
                );
            }
        });
    });

    $('#user_faculty').change(function() {
        let $form = $(this).closest('form');
        let data = {};
        data['faculty'] = $('#user_faculty').val();
        data['speciality'] = null;

        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : data,
            success : function(html) {
                $('#user_speciality').replaceWith(
                    $(html).find('#user_speciality')
                );
                console.log($('#user_speciality'));
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
                $('#student-faculty').removeClass('invisible');
                $('#student-speciality').removeClass('invisible');
                $('#student-group').removeClass('invisible');
                break;
            case '3':
                $('#colleague-department').removeClass('invisible');
                $('#colleague-position').removeClass('invisible');
                $('#student-faculty').addClass('invisible');
                $('#student-speciality').addClass('invisible');
                $('#student-group').addClass('invisible');
                break;
            case '4':
                $('#colleague-department').addClass('invisible');
                $('#colleague-position').addClass('invisible');
                $('#student-faculty').addClass('invisible');
                $('#student-speciality').addClass('invisible');
                $('#student-group').addClass('invisible');
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
    console.log(wow);
});