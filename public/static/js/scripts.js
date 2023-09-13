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
const closebutton = $(".close-button");


function hideTimeline(timelineIndex) {
    additionalcontent
        .eq(timelineIndex - 1)
        .show()
        .addClass(
            "additional-content-order" + (timelineIndex % 2 === 0 ? "2" : "1")
        );
    timelinecontainer.addClass(
        "timeline-order" + (timelineIndex % 2 === 0 ? "1" : "2")
    );
    timelineEvents.each(function (index, event) {
        closebutton.show();
        event = $(event);
        if (event.data("timeline-index") !== timelineIndex) {
            event.hide();
        } else {
            images.hide();
            arrows.hide();
            event.addClass(
                "timeline__event_detailed timeline_detailed_" +
                    (timelineIndex % 2 === 0 ? "left" : "right")
            );
            event.removeClass("timeline__event");
        }
    });
}

function showTimeline() {
    timelineEvents.each(function (index, event) {
        event = $(event);
        closebutton.hide();
        event.show();
        event.removeClass(
            "timeline__event_detailed timeline-order1 timeline-order2 timeline_detailed_left timeline_detailed_right"
        );
        event.addClass("timeline__event");
        $(".additional-content").each(function (index, event) {
            $(event)
                .hide()
                .removeClass(
                    "additional-content-order1 additional-content-order2"
                );
        });
        images.show();
        arrows.show();
    });
}


// sort script

document.addEventListener("DOMContentLoaded", function () {
    var randomButton = document.getElementById("randomButton");
    var sortButton = document.getElementById("sortButton");
    var sortAlgorithmContainer = document.getElementById("sortAlgorithm");
    var arrayElementsInput = document.getElementById("arrayElements");
    var sortAlgorithmOptions = document.querySelectorAll(
        "#sortAlgorithm-option"
    );

    var randomArray = generateRandomArray(10, 5, 50);
    arrayElementsInput.value = randomArray.join(", ");

    randomButton.addEventListener("click", function () {
        var randomArray = generateRandomArray(10, 5, 50);
        arrayElementsInput.value = randomArray.join(", ");
    });

    sortButton.addEventListener("click", async function () {
        var selectedAlgorithm =
            sortAlgorithmContainer.getAttribute("data-selected");
        var arrayString = arrayElementsInput.value;
        var array = arrayString
            .split(",")
            .map((element) => parseInt(element.trim()));

        if (selectedAlgorithm === "insertion") {
            await insertionSort(array);
        } else if (selectedAlgorithm === "bubble") {
            await bubbleSort(array);
        } else if (selectedAlgorithm === "selection") {
            await selectionSort(array);
        }
    });

    sortAlgorithmOptions.forEach(function (option) {
        option.addEventListener("click", function () {
            var selectedValue = option.getAttribute("data-value");
            sortAlgorithmContainer.setAttribute("data-selected", selectedValue);
            sortAlgorithmContainer.querySelector("h3").textContent =
                option.textContent;
        });
    });
});

function generateRandomArray(length, minValue, maxValue) {
    var randomArray = [];
    for (var i = 0; i < length; i++) {
        var randomNumber =
            Math.floor(Math.random() * (maxValue - minValue + 1)) + minValue;
        randomArray.push(randomNumber);
    }
    return randomArray;
}
async function bubbleSort(array) {
    for (var i = 0; i < array.length; i++) {
        var swaped = false;

        for (var j = 0; j < array.length - i - 1; j++) {
            if (array[j + 1] < array[j]) {
                // Swap elements
                var temp = array[j + 1];
                array[j + 1] = array[j];
                array[j] = temp;
            }
            displayBars(array, [j + 1, j]);
            await sleep(500);
        }
        if (swaped) {
            break;
        }
    }
    displayBars(array);
}

function displayBars(array, selectedIndexes = []) {
    var barsDiv = document.getElementById("bars");
    barsDiv.innerHTML = "";
    var maxValue = Math.max(...array);
    for (var i = 0; i < array.length; i++) {
        var barHeight = (array[i] / maxValue) * 19;
        var bar = document.createElement("div");
        bar.className = "bar";
        if (selectedIndexes.includes(i)) {
            bar.style.backgroundColor = "red";
        }
        bar.style.height = barHeight + "em";

        var valueSpan = document.createElement("span");
        valueSpan.textContent = array[i];

        bar.appendChild(valueSpan);
        barsDiv.appendChild(bar);
    }
}

async function insertionSort(array) {
    for (var i = 0; i < array.length; i++) {
        var temp = array[i];
        j = i - 1;

        while (j >= 0 && temp < array[j]) {
            array[j + 1] = array[j];
            j--;

            displayBars(array, [j + 1]);
            await sleep(500);
        }
        array[j + 1] = temp;
    }

    displayBars(array);
}
async function selectionSort(inputArr) {
    let n = inputArr.length;

    for (let i = 0; i < n; i++) {
        // Finding the smallest number in the subarray
        let min = i;
        for (let j = i + 1; j < n; j++) {
            if (inputArr[j] < inputArr[min]) {
                min = j;
            }
            displayBars(inputArr, [j + 1]);
            await sleep(500);
        }
        if (min != i) {
            // Swapping the elements
            let tmp = inputArr[i];
            inputArr[i] = inputArr[min];
            inputArr[min] = tmp;
        }
    }
    displayBars(inputArr);
}
function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

async function startVisualizationBubble(event) {
    event.preventDefault();
    var arrayElementsInput = document.getElementById("arrayElements");
    var arrayString = arrayElementsInput.value;
    var array = arrayString
        .split(",")
        .map((element) => parseInt(element.trim()));

    await bubbleSort(array);
}

async function startVisualizationInsertion(event) {
    event.preventDefault();
    var arrayElementsInput = document.getElementById("arrayElements");
    var arrayString = arrayElementsInput.value;
    var array = arrayString
        .split(",")
        .map((element) => parseInt(element.trim()));

    await insertionSort(array);
}

async function startVisualizationSelection(event) {
    event.preventDefault();
    var arrayElementsInput = document.getElementById("arrayElements");
    var arrayString = arrayElementsInput.value;
    var array = arrayString
        .split(",")
        .map((element) => parseInt(element.trim()));

    await selectionSort(array);
}

// End

let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides((slideIndex += n));
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides((slideIndex = n));
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");

    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex - 1].style.display = "block";
}


$(function(){
    $('.selectpicker').selectpicker();
});

function setLanguage(langID, language) {
    document.cookie = `selected_language_id=${langID}; path=/`;
    document.cookie = `selected_language=${language}; path=/`;
    var currentURL = window.location.pathname;
    var currentPathWithoutLanguage = currentURL.substring(4);
    var newURL = '/' + language + '/' + currentPathWithoutLanguage;

    window.location.pathname = newURL;
}

