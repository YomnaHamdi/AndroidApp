<?php

include "db_connection.php";

$email = "yomna@gmail.com";
$pass = "";

if($email && $pass){

    $user_login = mysqli_query($con, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$pass';");

    if (mysqli_num_rows($user_login) > 0){

        $user = mysqli_fetch_object($user_login);

        echo json_encode($user);

    }else{
        echo json_encode("not_found");
    }

}else{
    echo json_encode("error");
}


?>