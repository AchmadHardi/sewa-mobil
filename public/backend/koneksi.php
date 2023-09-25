<?php

    session_start();
    $servername = "localhost";
    $usernama = "root";
    $password = "";
    $dbname = "sumber_daya_manusia";

    $conn = mysqli_connect($servername,$usernama,$password,$dbname);


    if(!$conn){
        die("Database tidak terhubung");
        
    }

    

?>