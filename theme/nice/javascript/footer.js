"use strict";

/**
 * Scroll to top button functionality with gradient fill based on scroll position.
 *
 * @param {number} [scrollY=300] - The minimum scroll position to show the button.
 */
function niceScrollToTop(scrollY = 300) {
    // Select the scroll-to-top container element
    const niceToTop = document.querySelector(".nice-scroll-to-top-container");

    // If the container element is not found, exit the function
    if (niceToTop === null) {
        return;
    }

    // Add a scroll event listener
    window.addEventListener("scroll", () => {
        // Get the current scroll position
        const niceScrollTop = window.pageYOffset
            || document.documentElement.scrollTop;

        // Calculate the window height
        const niceWindowHeight = document.documentElement.scrollHeight
            - document.documentElement.clientHeight;

        // Calculate the scroll percentage
        const niceScrollPercent = (niceScrollTop / niceWindowHeight) * 100;

        // Update the conic-gradient background based on the scroll percentage
        niceToTop.style.background =
            `conic-gradient(
                var(--nice-color-main) ${niceScrollPercent}%,
                var(--nice-scroll-to-top-background) ${niceScrollPercent}%
            )`;

        // Show the button if the scroll position is greater than or equal to the threshold
        if (niceScrollTop >= scrollY) {
            niceToTop.classList.add("active");

            // Add a click event listener to smoothly scroll back to the top
            niceToTop.onclick = () => {
                window.scrollTo({
                    top: 0,
                    left: 0,
                    behavior: "smooth"
                });
            };
        } else {
            if (niceToTop.classList.contains("active")) {
                niceToTop.classList.remove("active");
            }
        }
    });
}

// Initialize the scroll-to-top button
niceScrollToTop(200);
