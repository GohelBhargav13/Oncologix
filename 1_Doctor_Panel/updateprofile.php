<?php
include './db_conn.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if session is not set.....
if (!isset($_SESSION['doc_id'])) {
    echo "<script>alert('Session expired. Please log in again.'); window.location.href = 'login.php';</script>";
    exit();
}
$doc_id = intval($_SESSION['doc_id']);

//Fetching the data to display the details.....
$result = $conn->query("SELECT * FROM doctor_register WHERE doc_id = $doc_id");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
if (isset($_POST['savepro'])) {
    echo "<script>alert('Your profile is saved.....');window.location.href='index.php'</script>";
}
if (isset($_POST['updatepro'])) {
    if (!empty($_POST['doctor_name']) || !empty($_POST['email']) || !empty($_POST['experience']) || !empty($_POST['specialization'])) {

        $doc_name = htmlspecialchars(trim($_POST['doctor_name']));
        $doc_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $doc_exp = htmlspecialchars($_POST['experience']); 
        $doc_sep = htmlspecialchars(trim($_POST['specialization']));
        $update_status = 1;

        $stmt = $conn->prepare("UPDATE doctor_register SET name = ?, email = ?, experience = ?, specialization = ?, update_status = ? WHERE doc_id = ?");
        $stmt->bind_param("ssssii", $doc_name, $doc_email, $doc_exp, $doc_sep, $update_status,$doc_id); 

        if ($stmt->execute()) {
            $_SESSION['doc_name'] = htmlspecialchars($_POST['doctor_name']);
            echo "<script>alert('Profile updated successfully'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error updating profile: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Doctor Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-blue-200 to-white-50 min-h-screen flex items-center justify-center mt-[30px] mb-[20px]">

    <form id="updateDoctorForm" action="" method="POST" enctype="multipart/form-data"
        class="max-w-2xl w-full bg-white/30 backdrop-blur-md p-6 rounded-lg shadow-lg border border-gray-300">

        <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Doctor Profile</h2>

        <!-- Doctor Name & Specialization -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-gray-50/50 p-4 rounded-lg shadow-md backdrop-blur-md">
                <label class="block text-gray-700 font-semibold mb-2">Doctor Name:</label>
                <input type="text" name="doctor_name"
                    class="w-full p-3 border border-gray-300 rounded-md text-gray-700 font-semibold placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter Doctor Name" value="<?= htmlspecialchars($row['name']) ?>" required>
            </div>
            <div class="bg-gray-50/50 p-4 rounded-lg shadow-md backdrop-blur-md">
                <label class="block text-gray-700 font-semibold mb-2">Specialization:</label>
                <input type="text" name="specialization"
                    class="w-full p-3 border border-gray-300 rounded-md text-gray-700 font-semibold placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter Specialization" value="<?= htmlspecialchars($row['specialization']) ?>" required>
            </div>
        </div>

        <!-- Experience & Email -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-gray-50/50 p-4 rounded-lg shadow-md backdrop-blur-md">
                <label class="block text-gray-700 font-semibold mb-2">Experience (Years):</label>
                <input type="number" name="experience"
                    class="w-full p-3 border border-gray-300 rounded-md text-gray-700 font-semibold placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter Experience" value="<?= htmlspecialchars($row['experience']) ?>" required>
            </div>
            <div class="bg-gray-50/50 p-4 rounded-lg shadow-md backdrop-blur-md">
                <label class="block text-gray-700 font-semibold mb-2">Email:</label>
                <input type="email" name="email"
                    class="w-full p-3 border border-gray-300 rounded-md text-gray-700 font-semibold placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter Email" value="<?= htmlspecialchars($row['email']) ?>" required>
            </div>
        </div>

        <!-- Doctor Image -->
        <div class="bg-gray-50/50 p-4 rounded-lg shadow-md backdrop-blur-md mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Doctor Image:</label>
            <img src="doctorimages/<?= htmlspecialchars($row['doc_photo']) ?>" alt="Doctor Image"
                class="w-28 h-28 rounded-lg border border-gray-300 shadow-md">
                <input type="file" name="doc_img" class="w-full p-2 border rounded-md mt-2" accept="image/*">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between">
            <button type="submit" name="updatepro"
                class="w-1/2 bg-blue-600 text-white py-2 mr-2 rounded-lg mt-4 transition duration-300 hover:bg-blue-800 font-semibold">Update
                Profile</button>
            <button type="submit" name="savepro"
                class="w-1/2 bg-gray-400 text-white py-2 rounded-lg mt-4 transition duration-300 hover:bg-gray-600 font-semibold">Save
                Profile</button>
        </div>

    </form>

</body>

</html>