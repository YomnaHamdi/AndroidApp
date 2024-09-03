<?php

include "db_connection.php";

$first_name = "amr";
$last_name ="ali";
$email = "amr@gmail.com";
$password ="0000000";
$User_Education = "uuuuuuu";
$User_Experience = "gggbvvd";
$User_Skills = "njnjnjnj";


if($first_name &&  $last_name && $email && $password && $User_Education && $User_Experience && $User_Education  ){

    $user_login = mysqli_query($con, "SELECT * FROM `users` WHERE `email` = '$email' ;");

    if (mysqli_num_rows($user_login) == 0){

        $insert_user = mysqli_query($con, "INSERT INTO `users` (`first_name`,`last_name`, `email`, `password`, `User_Education`, `User_Experience`,`User_Skills`) VALUES ('$first_name','$last_name','$email','$password','$User_Education','$User_Experience','$User_Skills')");

        if(mysqli_affected_rows($con) > 0){

            $user_id = mysqli_insert_id($con);
            echo json_encode($user_id);
        }else{
            echo json_encode("error");
        }


    }else{
        echo json_encode("email_found");
    }


    

}else{
    echo json_encode("error");
}


/*
*/
?>