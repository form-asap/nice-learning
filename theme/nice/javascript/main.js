/* global $ */

"use strict";

const isRTL = $("html").attr("dir") === "rtl";

const navTextIcons = isRTL
    ? ['<i class="fas fa-arrow-right"></i>', '<i class="fas fa-arrow-left"></i>']
    : ['<i class="fas fa-arrow-left"></i>', '<i class="fas fa-arrow-right"></i>'];

if (window.jQuery && $.fn.owlCarousel) {
    $(document).ready(function() {
        // List all your slider selectors that share same settings
        const sliderSelectors = [
            ".nice-courses-slider-one",
            ".nice-courses-slider-two",
            ".nice-courses-slider-three",
            ".nice-courses-slider-four",
            ".nice-courses-slider-five",
            ".nice-categories-slider-one",
            ".nice-categories-slider-two",
            ".nice-blogs-slider-one",
            ".nice-blogs-slider-two",
            ".nice-instructors-slider-one"
        ];

        // Shared carousel options
        const sharedOptions = {
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            navText: navTextIcons,
            rtl: isRTL,
            responsive: {
                "0": {items: 1},
                "600": {items: 2},
                "1000": {items: 3},
                "1200": {items: 4}
            }
        };

        // Initialize all sliders in the list
        sliderSelectors.forEach(function(selector) {
            if ($(selector).length) {
                $(selector).owlCarousel(sharedOptions);
            }
        });

        // Sliders with custom config

        // Nice-about-us-four
        if ($(".nice-about-us-four").length) {
            $(".nice-about-us-four").owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                items: 1,
                dots: false,
                navText: navTextIcons,
                rtl: isRTL,
                smartSpeed: 5000,
                autoplay: true,
                autoplayTimeout: 10000,
                autoplayHoverPause: true
            });
        }

        // Nice-testimonials-slider-one
        if ($(".nice-testimonials-slider-one").length) {
            $(".nice-testimonials-slider-one").owlCarousel({
                loop: true,
                margin: 30,
                center: true,
                autoplay: true,
                nav: false,
                dots: true,
                navText: navTextIcons,
                autoplayHoverPause: true,
                smartSpeed: 3000,
                autoplayTimeout: 10000,
                rtl: isRTL,
                responsive: {
                    "0": {items: 1},
                    "600": {items: 2},
                    "1000": {items: 3},
                    "1200": {items: 3}
                }
            });
        }

        // Nice-testimonials-slider-two
        if ($(".nice-testimonials-slider-two").length) {
            $(".nice-testimonials-slider-two").owlCarousel({
                loop: true,
                margin: 30,
                center: true,
                autoplay: true,
                nav: false,
                dots: true,
                navText: navTextIcons,
                autoplayHoverPause: true,
                smartSpeed: 3000,
                autoplayTimeout: 10000,
                rtl: isRTL,
                responsive: {
                    "0": {items: 1},
                    "600": {items: 2},
                    "1000": {items: 3},
                    "1200": {items: 3}
                }
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    // ========== Accordion Logic ==========

    const buttons = document.querySelectorAll(
        ".nice-accordion-content-container .nice-accordion-content button"
    );

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            // Remove "nice-color-main" class from all buttons
            buttons.forEach((btn) => btn.classList.remove("nice-color-main"));

            const icon = button.querySelector("i");
            const collapse = button
                .closest(".nice-accordion-content")
                .nextElementSibling;

            const dataParent = collapse.getAttribute("data-parent");

            if (dataParent) {
                // Close other open sections under the same parent
                const parentElement = document.querySelector(dataParent);
                if (parentElement) {
                    const openCollapses = parentElement.querySelectorAll(".collapse.show");
                    openCollapses.forEach((openCollapse) => {
                        if (openCollapse !== collapse) {
                            openCollapse.classList.remove("show");

                            const prevButton = openCollapse.previousElementSibling?.querySelector("button");
                            const prevIcon = prevButton?.querySelector("i");
                            if (prevIcon) {
                                prevIcon.classList.remove("fa-minus");
                                prevIcon.classList.add("fa-plus");
                            }
                        }
                    });
                }
            }

            // Toggle the clicked section
            collapse.classList.toggle("show");

            // Update icon based on state
            if (collapse.classList.contains("show")) {
                icon.classList.remove("fa-plus");
                icon.classList.add("fa-minus");
                button.classList.add("nice-color-main");
            } else {
                icon.classList.remove("fa-minus");
                icon.classList.add("fa-plus");
            }
        });
    });

    // ========== Title/Status Logic ==========

    const title = document.title;

    if (title === "Status") {
        const element = document.querySelector("#page-site-index .page-title-area");
        if (element) {
            element.style.display = "block";
        }
    }
});
