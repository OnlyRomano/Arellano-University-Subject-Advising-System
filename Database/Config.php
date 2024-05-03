<?php
    define('DB_SEVER', '127.0.0.1');
    define('DB_USERNAME', 'jhunmark');
    define('DB_PASSWORD','romano987');
    define('DB_NAME', 'itc1272C');
    
    $link =  mysqli_connect(DB_SEVER, DB_USERNAME , DB_PASSWORD, DB_NAME); 

    if($link == false){
        die("ERROR: Could not Connect." . mysqli_connect_error()); 
    }

    date_default_timezone_set("Asia/Manila");
?>