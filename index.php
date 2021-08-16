<?php

isset($_SESSION) ?  : @session_start();
$session = $_SESSION;
//print_r($session);
if(isset($session['loggedIn'])){
    header('Location: dashboard');
}else{
    header('Location: login');
}

?>