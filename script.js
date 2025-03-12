// OTP Generation
function generateOTP() {
  var otp = Math.floor(100000 + Math.random() * 900000);
  document.getElementById("otp").value = otp;
}

// OTP Validation
function validateOTP() {
  var enteredOTP = document.getElementById("otp").value;
  var actualOTP = document.getElementById("otp_display").innerHTML;

  if (enteredOTP === actualOTP) {
      alert("OTP validated successfully!");
  } else {
      alert("Invalid OTP!");
  }
}

// Form Validation
function validateForm() {
  var name = document.getElementById("name").value;
  var email = document.getElementById("email").value;
  var password = document.getElementById("password").value;

  if (name === "" || email === "" || password === "") {
      alert("Please fill all the fields.");
      return false;
  }
  return true;
}
