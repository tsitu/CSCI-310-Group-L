<?php

session_start();

unset($_SESSION['user_id']);
unset($_SESSION['username']);

header('Location: /CSCI-310-Group-L/www/login');
exit();