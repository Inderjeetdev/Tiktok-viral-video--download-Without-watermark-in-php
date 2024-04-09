<?php

    setcookie("user","",1,'/');
    unset($_COOKIE["user"]);

    $baseURL = 'https://ziscom.in/projects/test/login.php';
    header("Location: $baseURL");