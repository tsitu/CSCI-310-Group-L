<?php

session_start();

if ( isset($_SESSION['user_id']) )
{
    header('Location: /CSCI-310-Group-L/www/');
    exit();
}


//any login errors
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);

?>

<!DOCType html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <meta charset="utf-8">
    <title>minance login</title>
    
    <link rel='stylesheet' href='../css/global.css'>
    <link rel='stylesheet' href='../css/login.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
</head>
<body>
    
    <div id='' class='module'>
        <h1 id='appname'>minance</h1>

        <form id='login-form' class='auth-form' action='../src/scripts/login.php' method='post'>
            
            <input type='text'     placeholder='username' name='username' 
                   id='login-username' class='login-input' value='test@gmail.com'>
            
            <input type='password' placeholder='password' name='password' 
                   id='login-password' class='login-input' value='test'>
            
            <button id='login-button' class='auth-button'>Login</button>
        </form>
        
        <p id='error' class='error'> <?= $error ?> </p>
    </div>
    
</body>
</html>