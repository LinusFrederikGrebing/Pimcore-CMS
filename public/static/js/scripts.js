
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

window.addEventListener("DOMContentLoaded", function () {
    const showButton = document.getElementById("showPopup");
    const popupContainer = document.getElementById("popupContainer");

    showButton.addEventListener("click", function () {
        popupContainer.style.display = "block";
    });

    popupContainer.addEventListener("click", function (event) {
        if (event.target === popupContainer) {
            popupContainer.style.display = "none";
        }
    });
});

const timelinecontainer = $("#timeline-container");
const timelineEvents = $(".timeline__event");
const arrows = $(".arrow");
const images = $(".image");
const additionalcontent = $(".additional-content");

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

document.addEventListener("DOMContentLoaded", function () {
    const showButtonRP = document.getElementById("forgot-pass");
    const popupContainerRP = document.getElementById("popupContainerRP");

    showButtonRP.addEventListener("click", function () {
        popupContainerRP.style.display = "block";
    });

    popupContainerRP.addEventListener("click", function (event) {
        if (event.target === popupContainer) {
            popupContainer.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var popupContainer = document.getElementById("popupContainerRP");
    var closeButton = document.getElementById("closeButton");

    closeButton.addEventListener("click", function () {
        popupContainer.style.display = "none";
    });
});

function coverLogin() {
    document.location.href = "#cover";
}

function coverRegister() {
    document.location.href = "#";
}


// sort script

document.addEventListener("DOMContentLoaded", function() {
    var randomButton = document.getElementById("randomButton");
    var sortButton = document.getElementById("sortButton");
    var sortAlgorithmContainer = document.getElementById("sortAlgorithm");
    var arrayElementsInput = document.getElementById("arrayElements");
    var sortAlgorithmOptions = document.querySelectorAll("#sortAlgorithm-option");

    var randomArray = generateRandomArray(10, 5, 50);
    arrayElementsInput.value = randomArray.join(", ");

    randomButton.addEventListener("click", function() {
        var randomArray = generateRandomArray(10, 5, 50);
        arrayElementsInput.value = randomArray.join(", ");
    });

    sortButton.addEventListener("click", async function() {
        var selectedAlgorithm = sortAlgorithmContainer.getAttribute("data-selected");
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

    sortAlgorithmOptions.forEach(function(option) {
        option.addEventListener("click", function() {
            var selectedValue = option.getAttribute("data-value");
            sortAlgorithmContainer.setAttribute("data-selected", selectedValue);
            sortAlgorithmContainer.querySelector("h3").textContent = option.textContent;
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
        var barHeight = (array[i] / maxValue) * 25;
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
        
    for(let i = 0; i < n; i++) {
        // Finding the smallest number in the subarray
        let min = i;
        for(let j = i+1; j < n; j++){
            if(inputArr[j] < inputArr[min]) {
                min=j; 
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
document.getElementById('show-alert-button').addEventListener('click', function() {
    // Use SweetAlert to display a popup
    event.preventDefault(); // Prevent form submission
    var emailInput = document.getElementById('emailInputRp');
    var email = emailInput.value;
    sweetAlert('/resetPassword', email)
  });

  document.getElementById('loginBtn').addEventListener('click', function() {
    // Use SweetAlert to display a popup
    event.preventDefault(); // Prevent form submission
    var emailInput = document.getElementById('emailInputLogin');
    var email = emailInput.value;
    var passwordInput = document.getElementById('passwordInputLogin');
    var password = passwordInput.value;
    sweetAlert('/login', email, password);
  });

  function sweetAlert(route, email, password) {
    const formData = new FormData();
    formData.append('email', email);
  
    if (password) {
      formData.append('password', password);
    }
  
    fetch(route, {
      method: 'POST',
      body: formData,
    })
    .then(response => response.text())
    .then(responseText => {
      if (responseText.startsWith('/')) {
        window.location.href = responseText; // Redirect dynamically to the route
      } else {
        // Handle error response
        Swal.fire({
          title: 'Oops...',
          text: responseText,
          icon: 'error',
          confirmButtonText: 'Okay'
        });
      }
    })
    .catch(error => {
      Swal.fire({
        title: 'Oops...',
        text: error.message,
        icon: 'error',
        confirmButtonText: 'Okay'
      });
    });
  }
  