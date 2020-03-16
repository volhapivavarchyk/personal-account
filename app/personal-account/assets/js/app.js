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

let ready = $(document).ready(function () {
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
    $('#btn-notifications').click(function () {
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

    $('#btn-feedback').click(function () {
        $('#feedback')[0].classList.toggle('d-none');
        $('#btn-feedback')[0].classList.toggle('active');
        $('#btn-feedback')[0].blur();
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