<?php
include "./db_conn.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doc_name = $_POST['name'];
    $doc_exp = $_POST['experience'];
    $doc_sep = $_POST['specialization'];
    $doc_email = $_POST['email'];
    $doc_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $target_dir = "doctorimages/";
    $doctor_img = $_FILES['DoctorImage']['name'];

    if ($doc_password !== $confirm_password) {
        echo "<script>alert('Your password does not match.'); window.location.href='Doctor_Register.php';</script>";
        exit;
    }

    try {
        $check_stmt = $conn->prepare("SELECT email FROM doctor_register WHERE email = ?");
        $check_stmt->bind_param("s", $doc_email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Error: This email is already registered.');</script>";
        } else {
            if (move_uploaded_file($_FILES['DoctorImage']['tmp_name'], $target_dir . $doctor_img)) {
                // Insert new doctor
                $stmt = $conn->prepare("INSERT INTO doctor_register (name, experience, specialization, email, doc_photo, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sissss", $doc_name, $doc_exp, $doc_sep, $doc_email, $doctor_img, $doc_password);
                $stmt->execute();
                $_SESSION['doctor_id'] = $stmt->insert_id;
                echo "<script>alert('Registration successful!'); window.location.href='Doctor_Login.php';</script>";
            } else {
                echo "<script>alert('Image upload failed. Please try again.');</script>";
            }
        }
        
        $check_stmt->close();
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Something went wrong: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            alert(`Right-click is disabled.`);
        });
    </script>
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-b from-blue-100 to-white">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <h2 class="text-2xl font-semibold text-blue-900">Doctor Registration</h2>
        <form class="mt-4" method="post" action="" onsubmit="handleSubmit(event)" enctype="multipart/form-data" >
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="name" placeholder="Name" required class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="experience" placeholder="Experience" minlength="2" maxlength="2" required class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="text" name="specialization" placeholder="Specialization" required class="w-full px-3 py-2 border rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="grid grid-cols-2 gap-3 mt-3">
                <input type="password" name="password" placeholder="Password" required class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <input type="file" name="DoctorImage" required accept="image/*" class="w-full px-3 py-2 border rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded-md hover:bg-blue-700 mt-4" id="registerBtn">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="./Doctor_Login.php" class="text-blue-500">Login</a></p>
    </div>
    <script>
        function handleSubmit(event) {
            event.preventDefault();
            document.getElementById('registerBtn').innerHTML = 'Registering...';
            setTimeout(() => event.target.submit(), 500);
        }
    </script>
</body>

</html>