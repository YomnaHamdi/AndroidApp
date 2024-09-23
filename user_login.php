<?php

include_once 'db_connection.php'; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    
    $stmt = $con->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        
        if (password_verify($password, $hashedPassword)) {
            
            echo json_encode(array(
                "message" => "Login successful",
                "user_id" => $id
            ));
        } else {
            
            echo json_encode(array("message" => "Invalid password"));
        }
    } else {
        
        echo json_encode(array("message" => "User not found"));
    }
} else {
    echo json_encode(array("message" => "Invalid input"));
}

mysqli_close($con);

?>
