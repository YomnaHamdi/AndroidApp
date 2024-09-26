<?php
require 'db_connection.php';
require 'vendor/autoload.php'; 
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $jwt = str_replace('Bearer ', '', $authHeader);

    try {
        $secret_key = "9%fG8@h7!wQ4$zR2*vX3&bJ1#nL6!mP5"; 
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $User_id = $decoded->data->User_id; 

    
        $Gender = mysqli_real_escape_string($con, $_POST['Gender']);
        $Age = mysqli_real_escape_string($con, $_POST['Age']);
        $Location = mysqli_real_escape_string($con, $_POST['Location']);
        $Phone = mysqli_real_escape_string($con, $_POST['Phone']);
        $Bio = mysqli_real_escape_string($con, $_POST['Bio']);
        $User_image = mysqli_real_escape_string($con, $_POST['User_image']);
        $user_name = mysqli_real_escape_string($con, $_POST['user_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $job_name = mysqli_real_escape_string($con, $_POST['job_name']);

        
        $sql_check_user = "SELECT User_id FROM users WHERE User_id='$User_id'";
        $result = mysqli_query($con, $sql_check_user);

        if (mysqli_num_rows($result) > 0) {
            $sql_insert = "INSERT INTO user_profile (User_id, Gender, Age, Location, Phone, Bio, User_image, user_name, email, job_name) 
                           VALUES ('$User_id', '$Gender', '$Age', '$Location', '$Phone', '$Bio', '$User_image', '$user_name', '$email', '$job_name')";

            if (mysqli_query($con, $sql_insert)) {
                echo json_encode(["status" => "success", "message" => "User profile inserted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error inserting user profile: " . mysqli_error($con)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error: User ID does not exist in Users table"]);
        }

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Invalid token: " . $e->getMessage()]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Authorization header not found"]);
}

mysqli_close($con);
?>
