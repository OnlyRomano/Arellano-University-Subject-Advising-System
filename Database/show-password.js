function togglePassword() {
    var passwordField = document.getElementById("txtpassword");
    var eyeIcon = document.getElementById("eye-icon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.src = "eye-open.png"; // Change the source to the open eye icon image
    } else {
        passwordField.type = "password";
        eyeIcon.src = "eye-close.png"; // Change the source to the closed eye icon image
    }
}