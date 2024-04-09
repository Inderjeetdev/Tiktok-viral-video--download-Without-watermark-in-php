<?php
    include('header.php');
    
    if(isset($_COOKIE['user'])) {
        $baseURL = 'https://ziscom.in/projects/test';
        header("Location: $baseURL");
    } else {
        echo '';
    }

    if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if($username == "user@gmail.com" && $password == "pass"){
            
            setcookie('user', $username, time() + (43200), "/"); 
            
            $baseURL = 'https://ziscom.in/projects/test';
            header("Location: $baseURL");
        } else {
            echo "<br><br><br><div><center>Logins incorrect.</center></div>";
        }
        
    } else {
        echo "<br><br><br><div><center>Please enter logins</center></div>";
    }
    
    echo '<center>';
        echo '<br><br><br>';
        echo '<form action="" method="POST">';
            echo 'Enter Username:';
            echo '<input type="email" name="username" placeholder="Your email" required><br>';
            echo 'Enter Password:';
            echo '<input type="password" name="password" placeholder="Password" required><br>';
            echo '<br>';
            echo '<input type="submit" class="button">';
        echo '</form>';
    echo '</center>';

    include('footer.php');
?>