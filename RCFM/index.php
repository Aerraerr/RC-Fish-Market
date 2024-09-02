<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../RCFM/css/LoginStyle.css">
    <style>
        *{
            background-color: #222222;
        }
        #LoginBTN{
            position: absolute;
            background-color: #A7ABAC;
            width: 255px;
            height: 30px;
            border-radius: 5px;
            margin-top: 45px;
            margin-left: -254px;
            font-size: 16px;
            letter-spacing: 2px;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;


        }
        #LoginBTN:hover{
            background-color: #ACC7D0;
            border: solid 2px whitesmoke;
        }
        .logbox{
            box-shadow: 7px 7px 7px rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    
    <div class="logbox" style="zoom: 80%">
        <img id="RC" src="Img/fish.png" alt="">
    
        <h3 id="text1">RAVEN CHRISTINE</h3>
        <h5 id="text2">POINT OF SALE</h5>
        <div class="forinput">
            
            <div class="inputlogo">
                <img src="Img/username.png" alt=""><br>
                <img style="width: 21px;" src="Img/password.png" alt="">
            </div>
            <input class="username" id="inputtype" type="text"><br>
            <input class="password" id="inputtype" type="password">
            <button id="LoginBTN" onclick="checkCredentials()">LOGIN</button>
        </div>
    </div>
    
    <script src="js/LoginJS.js"></script>
    <script>
        function checkCredentials() {
    var usernameInput = document.querySelector('.username').value;
    var passwordInput = document.querySelector('.password').value;
    
    let username1 = "ADMINISTRATOR";
    let password1 = "admin";
    
    let username2 = "CASHIER";
    let password2 = "cashier";
    
    if (usernameInput === username1 && passwordInput === password1) {
        window.location.href = "pages/ForAdministrator.php";
    } else if (usernameInput === username2 && passwordInput === password2) {
        window.location.href = "pages/ForCashier.php";
    } else {
        alert("Invalid username or password!");
    }
}
    </script>
</body>
</html>