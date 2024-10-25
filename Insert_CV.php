<?php
require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;  

include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        exit();
    }

    try {
        $token = str_replace("Bearer ", "", $token);
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));  
        $user_id = $decoded->user_id;  
    } catch (ExpiredException $e) {
        echo json_encode(["error" => "Token has expired."]);
        exit();
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        exit();
    }

    $sql_user = "SELECT User_name, Phone FROM users WHERE User_id = ?";
    $stmt_user = $con->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows === 0) {
        echo json_encode(["error" => "User not found."]);
        exit();
    }

    $user_data = $result_user->fetch_assoc();
    $user_name = $user_data['User_name'];
    $phone = $user_data['Phone'];

    $data = json_decode(file_get_contents("php://input"));
    $skills = $data->skills ?? []; 
    $description = $data->description ?? null; 
    $education = $data->Education ?? null;  
    $languages = $data->Languages ?? [];    

   
    if (!$description || empty($skills) || !$education || empty($languages)) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

   
    $sql_experience = "INSERT INTO experience (User_id, description) VALUES (?, ?)";
    $stmt_experience = $con->prepare($sql_experience);
    $stmt_experience->bind_param("is", $user_id, $description);

    if (!$stmt_experience->execute()) {
        echo json_encode(["error" => "Error inserting experience data"]);
        exit();
    }

    // تحويل قائمة اللغات إلى سلسلة نصية
    $languagesString = implode(',', $languages); 
    $sql_cv = "INSERT INTO curriculum_vitae (User_id, Languages, Education, Created_at) VALUES (?, ?, ?, NOW())";
    $stmt_cv = $con->prepare($sql_cv);
    $stmt_cv->bind_param("iss", $user_id, $languagesString, $education);

    if (!$stmt_cv->execute()) {
        echo json_encode(["error" => "Error inserting CV data"]);
        exit();
    }

  
    foreach ($skills as $skill) {
        $sql_skill = "INSERT INTO skills (User_id, skill_name) VALUES (?, ?)";
        $stmt_skill = $con->prepare($sql_skill);
        $stmt_skill->bind_param("is", $user_id, $skill);

        if (!$stmt_skill->execute()) {
            echo json_encode(["error" => "Error inserting skill data"]);
            exit();
        }
    }

    
    echo json_encode(["message" => "CV inserted successfully.", "user_name" => $user_name, "phone" => $phone]);

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
