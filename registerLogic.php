<?php
include_once 'config.php';

if (isset($_POST['submit'])) {
    $emri = trim($_POST['emri'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tempPass = $_POST['password'] ?? '';
    $tempConfirm = $_POST['confirm_password'] ?? '';

    // Check if passwords match
    if ($tempPass !== $tempConfirm) {
        echo "Passwords do not match.";
        exit;
    }

    // Validate empty fields
    if (empty($emri) || empty($username) || empty($email) || empty($tempPass)) {
        echo "Please fill all the fields";
        exit;
    }

    $password = password_hash($tempPass, PASSWORD_DEFAULT);

    // Handle file upload
    $profileImagePath = '';

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create uploads dir if it doesn't exist
        }
        $fileName = basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            $profileImagePath = $targetFilePath;
        } else {
            echo "Failed to upload profile image.";
            exit;
        }
    } else {
        echo "Profile image is required.";
        exit;
    }

    // Prepare and execute insert query
    $sql = "INSERT INTO users (emri, username, email, password, role, profile_image) 
            VALUES (:emri, :username, :email, :password, :role, :profile_image)";
    
    $stmt = $conn->prepare($sql);

    $role = 'user'; // Default role

    $stmt->bindParam(':emri', $emri);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':profile_image', $profileImagePath);

    if ($stmt->execute()) {
        header("Location: signinn.php");
        exit();
    } else {
        echo "Error: Could not register user.";
    }
} else {
    echo "Invalid request.";
}
