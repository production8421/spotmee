import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// AOS is loaded + initialized in layouts/web/master.blade.php (once: true + refresh on load/fonts).
// Do not call AOS.init() here — a second init overwrote settings with once:false and made sections
// (e.g. FAQ accordions) fade back to invisible when scrolling.
// if (typeof AOS !== 'undefined') {
//     AOS.init({
//         duration: 800,
//         easing: 'ease-in-out',
//         once: false,
//         offset: 100,
//         delay: 0,
//     });
// }
if (typeof window.jQuery !== 'undefined') {
    const $ = window.jQuery;

    $(document).ready(function () {
        $('.menu-toggle').on('click', function () {
            $('.primary-navs').toggleClass('active');
        });

        if ($('.featured-slider').length) {
            $('.featured-slider').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                centerMode: true,
                centerPadding: '150px',
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                prevArrow: $('.featured-prev-arrow'),
                nextArrow: $('.featured-next-arrow'),
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            });
        }

        if ($('.service-cat-slider').length) {
            $('.service-cat-slider').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                prevArrow: $('.service-cat-prev-arrow'),
                nextArrow: $('.service-cat-next-arrow'),
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            });
        }

        if ($('.service-detail-main-slider').length) {
            $('.service-detail-main-slider').slick({
                dots: false,
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
                appendDots: '.service-detail-slider-dots',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            dots: true,
                        },
                    },
                ],
            });
        }

        if ($('.gym-slider').length && $('.gym-prev').length && $('.gym-next').length) {
            $('.gym-slider').slick({
                dots: false,
                infinite: false,
                speed: 500,
                slidesToShow: 4,
                slidesToScroll: 1,
                prevArrow: $('.gym-prev'),
                nextArrow: $('.gym-next'),
                responsive: [
                    {
                        breakpoint: 1280,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });
        }
    });
}
