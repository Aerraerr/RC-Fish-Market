function checkUsername() {
    var usernameInput = document.querySelector('.username').value;
    let username1 = "ADMINISTRATOR";
    let username2 = "CASHIER";

    if (usernameInput === username1) {
        window.location.href = "pages/RCFishMarket.php";
    } else if (usernameInput === username2){
        window.location.href = "pages/index.html";
    }
    else {
        alert("Invalid username!");
    }
    
}