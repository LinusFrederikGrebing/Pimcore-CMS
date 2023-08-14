/*!
 * Start Bootstrap - Agency v7.0.12 (https://startbootstrap.com/theme/agency)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE)
 */
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {
    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector("#mainNav");
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove("navbar-shrink");
        } else {
            navbarCollapsible.classList.add("navbar-shrink");
        }
    };

    // Shrink the navbar
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener("scroll", navbarShrink);

    //  Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector("#mainNav");
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: "#mainNav",
            rootMargin: "0px 0px -40%",
        });
    }

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector(".navbar-toggler");
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll("#navbarResponsive .nav-link")
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener("click", () => {
            if (window.getComputedStyle(navbarToggler).display !== "none") {
                navbarToggler.click();
            }
        });
    });
});

const timelinecontainer = document.getElementById("timeline-container");
const timelineEvents = document.querySelectorAll(".timeline__event");
const arrows = document.querySelectorAll(".arrow");
var images = document.querySelectorAll(".image");
var additionalcontent = document.getElementById("additional-content");
additionalcontent.style.display = "none";
function hideTimeline(clickedArrow) {
    var timelineIndex = clickedArrow
        .closest(".timeline__event")
        .getAttribute("data-timeline-index");
        additionalcontent.style.display = "block";
        if (timelineIndex % 2 === 0) {
            additionalcontent.classList.add("additional-content-order2");
            timelinecontainer.classList.add("timeline-order1");
        } else {
            additionalcontent.classList.add("additional-content-order1");
            timelinecontainer.classList.add("timeline-order2");
        }
    timelineEvents.forEach(function (event) {
        if (event.getAttribute("data-timeline-index") !== timelineIndex) {
            event.style.display = "none";
        } else {
            event.classList.add("timeline__event_detailed");

            images.forEach(function (image) {
                image.style.display = "none";
            });
            event.classList.remove("timeline__event");
            arrows.forEach(function (arrow) {
                arrow.style.display = "none";
            });
            if (timelineIndex % 2 === 0) {
                event.classList.add("timeline_detailed_left");
            } else {
                event.classList.add("timeline_detailed_right");
            }
        }
    });
}

function showTimeline() {
    timelineEvents.forEach(event => {
        additionalcontent.style.display = "none";
        event.style.display = "flex";
        event.classList.remove("timeline__event_detailed");
        images.forEach(image => image.style.display = "block");
        event.classList.add("timeline__event");
        arrows.forEach(arrow => arrow.style.display = "block");
        event.classList.remove("timeline_detailed_left", "timeline_detailed_right");
        additionalcontent.classList.remove("additional-content-order1");
        additionalcontent.classList.remove("additional-content-order2");
        timelinecontainer.classList.remove("timeline-order1");
        timelinecontainer.classList.remove("timeline-order2");
    });
}