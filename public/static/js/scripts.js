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

const timelinecontainer = $("#timeline-container");
const timelineEvents = $(".timeline__event");
const arrows = $(".arrow");
const images = $(".image");
const additionalcontent = $(".additional-content");

function hideTimeline(timelineIndex) {
    additionalcontent.eq(timelineIndex - 1).show().addClass("additional-content-order" + (timelineIndex % 2 === 0 ? "2" : "1"));
    timelinecontainer.addClass("timeline-order" + (timelineIndex % 2 === 0 ? "1" : "2"));
    timelineEvents.each(function (index, event) {
        event = $(event);
        if (event.data("timeline-index") !== timelineIndex) {
            event.hide();
        } else {
            images.hide(); 
            arrows.hide();
            event.addClass("timeline__event_detailed timeline_detailed_" + (timelineIndex % 2 === 0 ? "left" : "right"));
            event.removeClass("timeline__event");
        }
    });
}

function showTimeline() {
    timelineEvents.each(function (index, event) {
        event = $(event);
        event.show();
        event.removeClass("timeline__event_detailed timeline-order1 timeline-order2 timeline_detailed_left timeline_detailed_right");
        event.addClass("timeline__event");
        $(".additional-content").each(function (index, event) {
            $(event).hide().removeClass("additional-content-order1 additional-content-order2");
        });
        images.show();
        arrows.show();
    });
}
