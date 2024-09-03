<?php

include "db_connection.php";

$users = array();

$select_users =  mysqli_query($con, "SELECT * FROM `users` WHERE 1;");

if (mysqli_num_rows($select_users) > 0){

    while($one_user = mysqli_fetch_object($select_users)){

        $one_user->day = "str";
        $users[] = $one_user;
    }


    echo json_encode($users);


}else{
    echo json_encode("no_data");
}

?>