<?php
$db_con= new mysqli('localhost','mailer','mailer','mailer');

if ($db_con->connect_error){
    die("connection error ".$db_con->connect_errno."  ".$db_con->connect_error);}
    ?>