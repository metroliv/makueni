/* Password Validations */
var newpassword = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
var match = document.getElementById("match");
var symbol = document.getElementById("symbol");
var confirm_password = document.getElementById("confirm_password");

// When the user clicks on the password field, show the message box
newpassword.onfocus = function () {
  document.getElementById("message").style.display = "block";
};

confirm_password.onfocus = function () {
  document.getElementById("confirm_message").style.display = "block";
};

// When the user clicks outside of the password field, hide the message box
newpassword.onblur = function () {
  document.getElementById("message").style.display = "none";
};

confirm_password.onblur = function () {
  document.getElementById("confirm_message").style.display = "none";
};

// When the user starts to type something inside the password field
newpassword.onkeyup = function () {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if (newpassword.value.match(lowerCaseLetters)) {
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if (newpassword.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if (newpassword.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if (newpassword.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }

  //Validate symbols
  var symbols = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
  if (newpassword.value.match(symbols)) {
    symbol.classList.remove("invalid");
    symbol.classList.add("valid");
  } else {
    symbol.classList.remove("valid");
    symbol.classList.add("invalid");
  }
};

confirm_password.onkeyup = function () {
  //Check If Passwords Match
  var password = document.getElementById("password"),
    confirm_password = document.getElementById("confirm_password");
  if (password.value != confirm_password.value) {
    match.classList.remove("valid");
    match.classList.add("invalid");
  } else {
    match.classList.remove("invalid");
    match.classList.add("valid");
  }
};
