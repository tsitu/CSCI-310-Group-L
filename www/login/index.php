<?php

session_start();

if ( isset($_SESSION['user_id']) )
{
    header('Location: /');
    exit();
}


//any login errors
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);

?>

<!DOCType html>
<html>
<head>
    <meta charset="utf-8">
    <title>minance login</title>
    
    <link rel='stylesheet' href='/css/global.css'>
    <link rel='stylesheet' href='/css/login.css'>
</head>
<body>
    
    <div id='' class='module'>
        <h1 id='appname'>minance</h1>

        <form id='login-form' class='auth-form' action='../src/scripts/login.php' method='post'>
            
            <input type='text'     placeholder='username' name='username' 
                   id='login-username' class='login-input'>
            
            <input type='password' placeholder='password' name='password' 
                   id='login-password' class='login-input'>
            
            <button id='login-button' class='auth-button'>Login</button>
        </form>
        
        <p class='error'> <?= $error ?> </p>
    </div>
    
</body>
</html>