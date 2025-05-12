<?php
include './db_conn.php';
if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $email = $_POST['Email'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password != $confirm_new_password) {
        echo "<script>alert(`Your password is not matched.......`)</script>";
    } else {

        $emailsql = "SELECT email FROM doctor_register WHERE email = ?";
        $stmtemail = $conn->prepare($emailsql);
        $stmtemail->bind_param("s", $email);
        $stmtemail->execute();

        $res = $stmtemail->get_result();

        if ($res->num_rows === 1) {
            $sql = "UPDATE doctor_register SET password = ? WHERE email = ? ";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param('ss', $new_password, $email);

            if ($stmt->execute()) {
                echo "<script>
                                alert(`Your Password updated successfully....`);
                                window.location.href='Doctor_Login.php';
                            </script>";
            } else {
                echo "<script>alert(`Somthing Error in updation.....`)</script>";
            }
        } else {

            echo "<script>alert(`NO Doctor Found Try again.......`)</script>";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="shortcut icon" href="../logo.jpg" type="image/jpg">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-b from-blue-100 to-white">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <h2 class="text-2xl font-semibold text-blue-900">Reset Password</h2>
        <form class="mt-4" method="post" action="./changepassword.php">
            <input type="text" name="Email" placeholder="Email" required class="w-full px-3 py-2 border rounded-md mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="new_password" placeholder="New Password" required class="w-full px-3 py-2 border rounded-md mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="confirm_new_password" placeholder="Re-enter New Password" required class="w-full px-3 py-2 border rounded-md mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded-md hover:bg-blue-700 mt-[2px]" id="btn">Reset Password</button>
        </form>
    </div>
</body>