import '../scss/app.scss';

// loads the Bootstrap jQuery plugins
import 'bootstrap/js/dist/dropdown.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/transition.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/alert.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/collapse.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js';
import 'bootstrap-sass/assets/javascripts/bootstrap/modal.js';
import 'jquery'

let ready = $(document).ready(function () {
    $('.addauthor').click(function () {
        console.log('111');
        let div = document.createElement('div');
        div.id = 'soathor1';
        div.appendChild(document.getElementById('soauthors'));
        div.innerText = '1111';
    });
    let $navbar = $(".navbar");

    $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
            $navbar.addClass("top-nav-collapse");
        } else {
            $navbar.removeClass("top-nav-collapse");
        }
    });
});

$( document ).ready(function() {
    new WOW().init();
});