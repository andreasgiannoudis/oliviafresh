document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.best-sellers-carousel', {
        slidesPerView: 4.5,
        spaceBetween: 50,
        centeredSlides: false,
        loop: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1024: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 2,
            },
            480: {
                slidesPerView: 1,
            },
        },
    });
});