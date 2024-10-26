<?php
// عرض الأخطاء لتسهيل التحقق من أي مشاكل
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;  

include 'db_connection.php'; 

$secretKey = "9%fG8@h7!wQ4\$zR2*vX3&bJ1#nL6!mP5"; 

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? null;

    if (!$token) {
        echo json_encode(["error" => "Token is required."]);
        http_response_code(401);
        exit();
    }

    try {
        $token = str_replace("Bearer ", "", $token);
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));  
        $user_id = $decoded->user_id;  
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
    $skills = $data->skills ?? null; 
    $description = $data->description ?? null; 
    $education = $data->Education ?? null;  
    $languages = $data->Languages ?? null;

    if ($languages !== null || $education !== null) {
        $sql_cv = "UPDATE curriculum_vitae SET Languages = IFNULL(?, Languages), Education = IFNULL(?, Education) WHERE User_id = ?";
        $stmt_cv = $con->prepare($sql_cv);
        $stmt_cv->bind_param("ssi", $languages ? implode(',', $languages) : null, $education, $user_id);

        if (!$stmt_cv->execute()) {
            echo json_encode(["error" => "Error updating CV data"]);
            exit();
        }
    }

    
    if ($skills !== null) {
      
        $sql_delete_skills = "DELETE FROM skills WHERE User_id = ?";
        $stmt_delete_skills = $con->prepare($sql_delete_skills);
        $stmt_delete_skills->bind_param("i", $user_id);
        $stmt_delete_skills->execute();

        
        foreach ($skills as $skill) {
            $sql_skill = "INSERT INTO skills (User_id, skill_name) VALUES (?, ?)";
            $stmt_skill = $con->prepare($sql_skill);
            $stmt_skill->bind_param("is", $user_id, $skill);

            if (!$stmt_skill->execute()) {
                echo json_encode(["error" => "Error inserting skill data"]);
                exit();
            }
        }
    }

    if ($description !== null) {
        $sql_update_experience = "UPDATE experience SET description = ? WHERE User_id = ?";
        $stmt_experience = $con->prepare($sql_update_experience);
        $stmt_experience->bind_param("si", $description, $user_id);

        if (!$stmt_experience->execute()) {
            echo json_encode(["error" => "Error updating description in experience"]);
            exit();
        }
    }

    echo json_encode(["message" => "CV updated successfully.", "user_name" => $user_name, "phone" => $phone]);

} else {
    echo json_encode(["error" => "Method not allowed"]);
}
