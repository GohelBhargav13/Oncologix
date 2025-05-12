<?php
require './db_conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query to fetch doctor details
    $sql = "SELECT doc_id, email, password, name FROM doctor_register WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $doctor = $result->fetch_assoc();
        
        if ($password == $doctor['password']) {
            // Regenerate session ID to prevent session fixation
            // session_regenerate_id(true);

            // Set session variables
            $_SESSION['doctor'] = $doctor['email'];
            $_SESSION['doc_id'] = $doctor['doc_id'];
            $_SESSION['doc_name'] = $doctor['name'];
            $_SESSION['login_activity'] = time();
 
            // Redirect to dashboard
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='Doctor_Login.php';</script>";
        }
    } else {
        echo "<script>alert('Doctor not found!'); window.location.href='Doctor_Login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <link rel="shortcut icon" href="../logo.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            alert(`Right-click is disabled.`);
        });
    </script>
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-b from-blue-100 to-white">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h2 class="text-2xl font-semibold text-blue-900">Doctor Login</h2>
        <form class="mt-4" method="post" action="">
            <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded-md mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 border rounded-md mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <a href="./forgetpassword.php" class="text-blue-500">Forget password ?</a>
            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded-md hover:bg-blue-700">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="./Doctor_Register.php" class="text-blue-500">Register</a></p>
    </div>
</body>

</html>