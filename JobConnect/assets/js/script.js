// Confirm before delete actions
function confirmDelete() {
    return confirm("Are you sure you want to delete this?");
}


// Password show/hide
function togglePassword() {
    let password = document.getElementById("password");

    if(password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}


// Auto hide alerts
setTimeout(function(){
    let alerts = document.querySelectorAll(".alert");

    alerts.forEach(function(alert){
        alert.style.display = "none";
    });

},4000);