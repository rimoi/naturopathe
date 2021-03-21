/*!
    * Start Bootstrap - Grayscale v6.0.3 (https://startbootstrap.com/theme/grayscale)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-grayscale/blob/master/LICENSE)
    */
$(function () {
    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ==
            this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            var target = $(this.hash);
            target = target.length
                ? target
                : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top - 70,
                    },
                    1000,
                    "easeInOutExpo"
                );
                return false;
            }
        }
    });

    // Closes responsive menu when a scroll trigger link is clicked
    $(".js-scroll-trigger").click(function () {
        $(".navbar-collapse").collapse("hide");
    });

    // // Activate scrollspy to add active class to navbar items on scroll
    // $("body").scrollspy({
    //     target: "#mainNav",
    //     offset: 100,
    // });

    $('#modal-connect-form').modal({
        backdrop: 'static',
        show: true,
        keyboard: false
    });

    let input_select2 = $('.js-select2');
    input_select2.select2({
        allowClear: true,
        placeholder: input_select2.attr('placeholder'),
    });

    $('.js-toggle-dropdown-job').on('click', function (event) {
        event.preventDefault();
        $(this).toggleClass('active');

        if ($('.js-toggle-dropdown-job-options').hasClass('d-none')) {
            $('.js-arrow-toggle-job').html('&#8613');
        } else {
            $('.js-arrow-toggle-job').html('&#8615');
        }
        $('.js-toggle-dropdown-job-options').toggleClass('d-none');
    });

    $('.js-toggle-dropdown-commune').on('click', function (event) {
        event.preventDefault();

        $(this).toggleClass('active');

        if ($('.js-toggle-dropdown-commune-options').hasClass('d-none')) {
            $('.js-arrow-toggle-commune').html('&#8613');
        } else {
            $('.js-arrow-toggle-commune').html('&#8615');
        }
        $('.js-toggle-dropdown-commune-options').toggleClass('d-none');
    });

    $('[data-component="jobs"], [data-component="zones"]').on('click', function (e) {
        $('#js-articles').html(`
            <div class="d-flex justify-content-center">
                <div class="spinner-grow text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);

        let jobs = [];
        let communes = [];
        $('[data-component="jobs"]:checked').each((index, element) => jobs.push(element.dataset.id*1));
        $('[data-component="zones"]:checked').each((index, element) => communes.push(element.dataset.id*1));

        $.ajax({
            type: 'POST',
            data: {'jobs': jobs, 'communes': communes},
            url: Routing.generate('home'),
            success: function (html) {
                if ($(html).find('#js-articles').length) {
                    $('#js-articles').replaceWith(
                        $(html).find('#js-articles')
                    );
                }
            }
        })
    });

    $('.js-metier').on('click', function (e) {
        e.preventDefault();
        $('.js-content').html(`<div class="text-center" role="status">
            <span class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></span>
            <span class="sr-only">Loading...</span>
        </div>`);
        let searchTerm = $('#search_keyword').val();
        let location = $('#search_location').val();
        let locale = $('#search_keyword').data('locale');
        let type = $(this).data('type');
        let url = Routing.generate('home.' + locale, {
            type,
            searchTerm,
            location
        });
        $.ajax({
            url,
            type: 'GET',
            data: { searchTerm, location },
            success(html) {
                $('.js-content').replaceWith(
                    $(html).find('.js-content')
                );
            }
        });
    });

    $('body').on('click', '.js-number', function (e) {
        $(this).closest('.card-body').find('.js-show-phone').removeClass('d-none');
        $(this).addClass('d-none');
    });
});
