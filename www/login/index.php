<?php

if ( !session_id() )
    @session_start();
else
{
    header('Location: /');
    exit();
}


include_once 'login-page.html';