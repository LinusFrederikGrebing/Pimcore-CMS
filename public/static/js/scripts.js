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
//End

// toggle popup
function togglePopupContainer(userIsLoggedIn) {
    const $popupContainer = $("#popupContainer");
    const $popupContainerLogout = $("#popupContainerLogout");
    
    const $targetElement = userIsLoggedIn ? $popupContainerLogout : $popupContainer;
    $targetElement.show();

    $targetElement.on("click", function (event) {
        if (event.target === this) {
            $targetElement.hide();
        }
    });
}

function loadUserDataAndTogglePopup(element) {
    var userAttribute = element.getAttribute("data-user");
    var userData = JSON.parse(userAttribute);
    togglePopupContainer(userData);
}
// End


// Timeline - script
const timelineEvents = $(".timeline__event");
function hideTimeline(timelineIndex) {
    $(".additional-content").eq(timelineIndex - 1).show().addClass("additional-content-order" + (timelineIndex % 2 === 0 ? "2" : "1"));
    $("#timeline-container").addClass("timeline-order" + (timelineIndex % 2 === 0 ? "1" : "2"));
    timelineEvents.each(function (index, event) {
        event = $(event);
        $(".image, .arrow").hide();
        $(".close-button").show();
        event.addClass("timeline__event_detailed timeline_detailed_" + (timelineIndex % 2 === 0 ? "left" : "right"));
        event.removeClass("timeline__event");
        if (event.data("timeline-index") !== timelineIndex) {
            event.hide();
        }
    });
}

function showTimeline() {
    timelineEvents.each(function (index, event) {
        event = $(event);
        event.show().removeClass("timeline__event_detailed timeline-order1 timeline-order2 timeline_detailed_left timeline_detailed_right").addClass("timeline__event");
        $(".additional-content").each(function (index, event) { event = $(event); event.hide().removeClass("additional-content-order1 additional-content-order2")}); 
        $(".close-button").hide();
        $(".image, .arrow").show();
    });
}
// End

// Forgot password
function forgot_pass(){
    $("#popupContainerRP").show();
}

function close_button(){
    $("#popupContainerRP").hide();
}

function popupContainerRP(event){
    if (event.target ===  $("#popupContainerRP")[0]) {
        $("#popupContainerRP").hide();
    }
}
// End

// Login cover
function coverLogin() {
    window.location.href = "#cover";
}

function coverRegister() {
    window.location.href = "#";
}
// End


// sort script
$(document).ready(function() {
    // Initial random array
    generateAndSetRandomArray();
    // Handle sort algorithm selection
    $("#sortAlgorithm-options").on("click", ".sortAlgorithm-option", updateSelectedAlgorithm);

});

function updateSelectedAlgorithm() {
    var selectedValue = $(this).data("value");
    $("#sortAlgorithm").data("selected", selectedValue);
    $("#sortAlgorithm h3").text($(this).text());
}

function sortArray() {
    var selectedAlgorithm = $("#sortAlgorithm").data("selected"); 
    var array = getArrayFromInput();
    switch (selectedAlgorithm) {
        case "insertion":
            insertionSort(array);
            break;
        case "bubble":
            bubbleSort(array);
            break;
        case "selection":
            selectionSort(array);
            break;
        default:
            break;
    }
}

function generateAndSetRandomArray() {
    var randomArray = generateRandomArray(10, 5, 50);
    displayBars(randomArray);
    $("#arrayElements").val(randomArray.join(", "));
}

function getArrayFromInput() {
    var arrayString = $("#arrayElements").val();
    return arrayString.split(",").map(function(element) {
        return parseInt(element.trim());
    });
}

function generateRandomArray(length, minValue, maxValue) {
    var randomArray = [];
    for (var i = 0; i < length; i++) {
        var randomNumber =
            Math.floor(Math.random() * (maxValue - minValue + 1)) + minValue;
        randomArray.push(randomNumber);
    }
    return randomArray;
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
            var selectedAlgorithm = $("#sortAlgorithm").data("selected"); 
            if(selectedAlgorithm == 'bubble'){
                displayBars(array, [j + 1, j]);
                await sleep(500);
            }
        }
        if (swaped) {
            break;
        }
    }
    if(selectedAlgorithm == 'bubble'){
        displayBars(array);
    }
}

async function insertionSort(array) {
    for (var i = 0; i < array.length; i++) {
        var temp = array[i];
        j = i - 1;

        while (j >= 0 && temp < array[j]) {
            array[j + 1] = array[j];
            j--;
            var selectedAlgorithm = $("#sortAlgorithm").data("selected"); 
            if(selectedAlgorithm == 'insertion'){
                displayBars(array, [j + 1]);
                await sleep(500);
            }
        }
        array[j + 1] = temp;
    }
    if(selectedAlgorithm == 'insertion'){
        displayBars(array);
    }
}

async function selectionSort(array) {
    let n = array.length;

    for (let i = 0; i < n; i++) {
        // Finding the smallest number in the subarray
        let min = i;
        for (let j = i + 1; j < n; j++) {
            if (array[j] < array[min]) {
                min = j;
            }
            var selectedAlgorithm = $("#sortAlgorithm").data("selected"); 
            if(selectedAlgorithm == 'selection'){
                displayBars(array, [j + 1]);
                await sleep(500);
            }
        }
        if (min != i) {
            // Swapping the elements
            let tmp = array[i];
            array[i] = array[min];
            array[min] = tmp;
        }
    }
    if(selectedAlgorithm == 'selection'){
        displayBars(array);
    }
}
function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}
// End


// AV Videoslider
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides((slideIndex += n));
}

function showSlides(n) {
    let slides = $(".mySlides");

    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }

    slides.hide();
    slides.eq(slideIndex - 1).show();
}
// End

//Handle Requests and show Alerts depending on response
//Statuscode 200: request and response successfull -> Show success-alert
//Statuscode 201: request and response successfull -> Don't show an alert
//Statuscode 400: response is a catched error with errormessage -> show error-alert with errormessage
//Statuscode 500: Servererror -> show error-alert
function fetchDataToResponse(route, formData, lang) {
    fetch(route, {
        method: "POST",
        body: formData,
    })
    .then((response) => {
        const status = response.status;
        return response.text().then((responseText) => {
            return { status, responseText };
        });
    })
    .then(({ status, responseText }) => {
        let title, text, icon;
        //Handle response as success and show Success-Alert
        if (status === 200) {
            title = lang == "de" ? "Erfolg!" : "Success!";
            text = responseText;
            icon = "success";
        //Handle response as Error and show Error-Alert
        } else if (status === 400) {
            title = lang == "de" ? "Fehler!" : "Error!";
            text = responseText;
            icon = "error";
        } else if (status === 500) {
            title = lang == "de" ? "Fehler!" : "Error!";
            text = lang == "de" ? "Bitte überprüfe deine Eingaben" : "Please check your entered information" 
            icon = "error";
        } else {
            if (responseText.startsWith("/")) {
                window.location.href = responseText;
                return;
            } else {
                title = lang == "de" ? "Unbekannter Status!" : "Unknown status!";
                text = lang == "de" ? "Unbekannter Statucode:" + status : "Unknown statuscode:" + status;
                icon = "error";
            }
        }
        sweetAlert(title, text, icon, responseText);
    });
}

function sweetAlert(title, text, icon, responseText) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: "Okay",
    }).then((result) => {
        if (result.isConfirmed && responseText.startsWith("/")) {
            window.location.href = responseText;
        }
    });
}


function handleSubmit(event, id, route, lang) {
    event.preventDefault();
    const formData = new FormData(document.getElementById(id));
    fetchDataToResponse(route, formData, lang);
}
// End
function fireLoadingAlert(event, lang) {
    handleSubmit(event, 'sendEmailForm', '/resetPassword', lang)
    let timerInterval
    Swal.fire({
    title: lang == "de" ? 'Email versenden...' : 'Sending mail...',
    timer: 1500,
    timerProgressBar: true,
    didOpen: () => {
        Swal.showLoading()
        const b = Swal.getHtmlContainer().querySelector('b')
        timerInterval = setInterval(() => {
        b.textContent = Swal.getTimerLeft()
        }, 100)
    },
    willClose: () => {
        clearInterval(timerInterval)
    }
    })
}
const editIcon = document.querySelector(".fa-pen-to-square");
const editDialog = document.getElementById("editDialog");
const closeEditDialog = document.getElementById("closeEditDialog");

// Funktion zum Öffnen des Edit-Dialogs
editIcon.addEventListener("click", function() {
    editDialog.style.display = "block";
});

// Funktion zum Schließen des Edit-Dialogs
closeEditDialog.addEventListener("click", function() {
    editDialog.style.display = "none";
});

// Verstecke den editProfileDialog standardmäßig
editDialog.style.display = "none";

