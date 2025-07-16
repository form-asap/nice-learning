"use strict";

// Prepending icons to each link
const links = document.querySelectorAll('.nice-dashboard-sidebar-items a');

const icons = [
    `
    <svg stroke="currentColor" fill="none" stroke-width="0"
         viewBox="0 0 24 24" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M16 9C16 11.2091 14.2091 13 12 13
                 C9.79086 13 8 11.2091 8 9
                 C8 6.79086 9.79086 5 12 5
                 C14.2091 5 16 6.79086 16 9Z"
              fill="currentColor"></path>
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M12 1C5.92487 1 1 5.92487 1 12
                 C1 18.0751 5.92487 23 12 23
                 C18.0751 23 23 18.0751 23 12
                 C23 5.92487 18.0751 1 12 1ZM3 12
                 C3 14.0902 3.71255 16.014 4.90798 17.5417
                 C6.55245 15.3889 9.14627 14 12.0645 14
                 C14.9448 14 17.5092 15.3531 19.1565 17.4583
                 C20.313 15.9443 21 14.0524 21 12
                 C21 7.02944 16.9706 3 12 3
                 C7.02944 3 3 7.02944 3 12Z"
              fill="currentColor"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         viewBox="0 0 24 24" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path fill="none" d="M0 0h24v24H0V0z"></path>
        <path d="M12 7.13l.97 2.29.47 1.11
                 1.2.1 2.47.21-1.88 1.63-.91.79
                 .27 1.18.56 2.41-2.12-1.28
                 -1.03-.64-1.03.62-2.12 1.28
                 .56-2.41.27-1.18-.91-.79
                 -1.88-1.63 2.47-.21 1.2-.1
                 .47-1.11.97-2.27M12 2
                 L9.19 8.63 2 9.24l5.46 4.73
                 L5.82 21 12 17.27
                 18.18 21l-1.64-7.03
                 L22 9.24l-7.19-.61L12 2z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         viewBox="0 0 1024 1024" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M880 184H712v-64c0-4.4-3.6-8-8-8h-56
                 c-4.4 0-8 3.6-8 8v64H384v-64c0-4.4-3.6-8-8-8
                 h-56c-4.4 0-8 3.6-8 8v64H144
                 c-17.7 0-32 14.3-32 32v664c0 17.7 14.3 32
                 32 32h736c17.7 0 32-14.3 32-32V216
                 c0-17.7-14.3-32-32-32zm-40 656H184V460h656
                 v380zM184 392V256h128v48c0 4.4 3.6 8 8 8
                 h56c4.4 0 8-3.6 8-8v-48h256v48
                 c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-48
                 h128v136H184z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         viewBox="0 0 32 32" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M6 3 L6 29 L26 29 L26 9.59375
                 L25.71875 9.28125 L19.71875 3.28125
                 L19.40625 3 Z M8 5 L18 5 L18 11
                 L24 11 L24 27 L8 27 Z M20 6.4375
                 L22.5625 9 L20 9 Z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         viewBox="0 0 1024 1024" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M888 792H200V168c0-4.4-3.6-8-8-8h-56
                 c-4.4 0-8 3.6-8 8v688c0 4.4 3.6 8 8 8h752
                 c17.7 0 32-14.3 32-32V216
                 c0-17.7-14.3-32-32-32zm-616-64h536
                 c4.4 0 8-3.6 8-8V284c0-7.2-8.7-10.7-13.7-5.7
                 L592 488.6l-125.4-124a8.03 8.03 0 0 0-11.3 0
                 l-189 189.6a7.87 7.87 0 0 0-2.3 5.6V720
                 c0 4.4 3.6 8 8 8z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         viewBox="0 0 24 24" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path fill="none" stroke="#000" stroke-width="2"
              d="M18.0003,20.9998
                 C16.3453,20.9998 15.0003,19.6538
                 15.0003,17.9998
                 C15.0003,16.3458 16.3453,14.9998
                 18.0003,14.9998
                 C19.6543,14.9998 21.0003,16.3458
                 21.0003,17.9998
                 C21.0003,19.6538 19.6543,20.9998
                 18.0003,20.9998 Z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="currentColor" stroke-width="0"
         t="1569683921137"
         viewBox="0 0 1024 1024" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M759 335c0-137-111-248-248-248S263 198
                 263 335c0 82.8 40.6 156.2 103 201.2
                 -0.4 0.2-0.7 0.3-0.9 0.4-44.7 18.9
                 -84.8 46-119.3 80.6-34.5 34.5-61.5 74.7
                 -80.4 119.5C146.9 780.5 137 827
                 136 874.8c-0.1 4.5 3.5 8.2 8 8.2h59.9
                 c4.3 0 7.9-3.5 8-7.8 2-77.2 32.9-149.5
                 87.6-204.3C356 614.2 431 583
                 511 583c137 0 248-111 248-248z"></path>
    </svg>
    `,
    `
    <svg stroke="currentColor" fill="none" stroke-width="0"
         viewBox="0 0 24 24" height="25px" width="25px"
         xmlns="http://www.w3.org/2000/svg">
        <path d="M8.51428 20H4.51428
                 C3.40971 20 2.51428 19.1046
                 2.51428 18V6
                 C2.51428 4.89543 3.40971 4
                 4.51428 4H8.51428V6
                 H4.51428V18H8.51428V20Z"
              fill="currentColor"></path>
    </svg>
    `
];

icons.forEach((icon, index) => {
    if (links[index]) {
        const container = document.createElement('div');
        container.innerHTML = icon.trim();
        links[index].insertBefore(container, links[index].firstChild);
    }
});

// Navbar notifications
document.addEventListener('DOMContentLoaded', () => {
    const popovers = document.querySelectorAll(
        '.popover-region.collapsed.popover-region-notifications'
    );

    popovers.forEach((popover) => {
        popover.addEventListener('click', (event) => {
            event.currentTarget.classList.toggle('collapsed');
        });
    });
});
