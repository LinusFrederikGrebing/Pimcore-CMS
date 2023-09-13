function myFunction(userIsLoggedIn) {
    // 'userData' will be the JSON data stored in the 'data-user' attribute
    const popupContainer = document.getElementById("popupContainer");
    const popupContainerLogout = document.getElementById(
        "popupContainerLogout"
    );
    if (userIsLoggedIn) {
        popupContainerLogout.style.display = "block";
    } else {
        popupContainer.style.display = "block";
    }

    if (userIsLoggedIn) {
        popupContainerLogout.addEventListener("click", function (event) {
            if (event.target === popupContainerLogout) {
                popupContainerLogout.style.display = "none";
            }
        });
    } else {
        popupContainer.addEventListener("click", function (event) {
            if (event.target === popupContainer) {
                popupContainer.style.display = "none";
            }
        });
    }
}
document.getElementById("showPopup").addEventListener("click", function () {
    var userAttribute = this.getAttribute("data-user");
    var userData = JSON.parse(userAttribute);
    myFunction(userData);
});

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

function resetPassword(event){
    // Use SweetAlert to display a popup
    event.preventDefault(); // Prevent form submission
    var emailInput = document.getElementById("emailInputRp");
    var email = emailInput.value;
    sweetAlert("/resetPassword", email);
};

function login(event){
// Use SweetAlert to display a popup
event.preventDefault(); // Prevent form submission
var emailInput = document.getElementById("emailInputLogin");
var email = emailInput.value;
var passwordInput = document.getElementById("passwordInputLogin");
var password = passwordInput.value;
console.log("test");
sweetAlert("/login", email, password);
};

function register(event){
// Use SweetAlert to display a popup
event.preventDefault(); // Prevent form submission
var emailInput = document.getElementById("emailInputRegister");
var email = emailInput.value;
var nameInput = document.getElementById("nameInputRegister");
var name = nameInput.value;
var passwordInput = document.getElementById("passwordInputRegister");
var password = passwordInput.value;
var passwordInputConfirmRegister = document.getElementById(
    "passwordInputConfirmRegister"
);
var confirmPassword = passwordInputConfirmRegister.value;
var profileimageInput = document.getElementById("profileimage");
var profileimage = profileimageInput.files[0]; // Get the selected file

sweetAlert(
    "/register",
    email,
    password,
    confirmPassword,
    profileimage,
    name
);
};

function sweetAlert(
route,
email,
password,
confirmPassword,
profileimage,
name
) {
const formData = new FormData();
formData.append("email", email);
if (name) {
    formData.append("name", name);
}
if (password) {
    formData.append("password", password);
}
if (confirmPassword) {
    formData.append("confirmPassword", confirmPassword);
}
if (profileimage) {
    formData.append("profileimage", profileimage);
}
fetch(route, {
    method: "POST",
    body: formData,
})
    .then((response) => response.text())
    .then((responseText) => {
        if (responseText.startsWith("/")) {
            window.location.href = responseText; // Redirect dynamically to the route
        } else {
            // Inside the .then(responseText => {...}) block
            if (responseText.toLowerCase().includes("error:")) {
                // Handle error response
                Swal.fire({
                    title: "Fehler!",
                    text: responseText.replace(/Error:/i, ""), // Remove "Error:" from the message
                    icon: "error",
                    confirmButtonText: "Okay",
                });
            } else {
                // Handle success response
                Swal.fire({
                    title: "Erfolg!",
                    text: responseText,
                    icon: "success",
                    confirmButtonText: "Okay",
                });
            }
        }
    });
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


