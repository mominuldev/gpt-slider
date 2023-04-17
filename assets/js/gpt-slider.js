jQuery(document).ready(function ($) {

    var slider = $('.gpt-slider').attr('id');

    console.log('#' + slider)

    var swiper = new Swiper('#' + slider, {
        loop: true,
        speed: 1000,
		effect: 'fade',
		fadeEffect: {
			crossFade: true
		},
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
			clickable: true,
        },

    });

});