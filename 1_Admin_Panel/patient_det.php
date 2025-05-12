<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("./header.php");
include("../1_Admin_Panel/db_conn.php");

if (!isset($_SESSION['admin'])) {
    header("Location:Admin_login.php");
    exit();
}

$patients = [];
$count = 0;

// Fetch pending cases
$query = "SELECT 
    ca.c_id as CancerID,
    p.P_name AS Name,
    p.age AS Age,
    p.p_image as Image,
    c.cancer_name AS CancerType,
    h.h_name AS Hospital,
    h.country AS Country
FROM cases ca
JOIN patient p ON ca.p_id = p.p_id
JOIN cancer c ON ca.cancer_type = c.cancer_name
JOIN hospital h ON ca.h_id = h.h_id 
WHERE ca.approval_status = 'pending'";

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $patients[] = $row;
    }
}

if (isset($_POST['acceptbtn']) && isset($_POST['c_id'])) {
    $case_id = $_POST['c_id'];

    $status_update = "approved";
    $stmt = $conn->prepare("UPDATE cases SET approval_status = ? WHERE c_id = ?");
    $stmt->bind_param("si", $status_update, $case_id);

    if ($stmt->execute()) {
        echo "<script>alert('Case Approved!'); window.location.href='patient_det.php';</script>";
    } else {
        echo "<script>alert('Error approving case!');</script>";
    }

    $stmt->close();
}
if (isset($_POST['rejectbtn']) && isset($_POST['c_id'])) {
    $case_id = $_POST['c_id'];
    
    // $status_update = "rejected";
    // $stmt = $conn->prepare("UPDATE cases SET approval_status = ? WHERE c_id = ?");
    // $stmt->bind_param("si", $status_update, $case_id);

    $stmt = $conn->prepare("DELETE FROM cases WHERE c_id = ?");
    $stmt->bind_param("i",$case_id);

    if ($stmt->execute()) {
        echo "<script>alert('Case Rejected!'); window.location.href='patient_det.php';</script>";
    } else {
        echo "<script>alert('Error Rejecting case!');</script>";
    }

    $stmt->close();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OncoLogix - Patient Cards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .count-box {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-blue-50 to-white flex flex-col items-center min-h-screen pt-0 relative">
    <div class="count-box mt-[80px] lg:ml-[100px]">Patients: <?php echo $count; ?></div>
<form action="" method="post" enctype="multipart/form-data">
    <div class="flex-grow flex flex-col justify-center items-center w-full px-4 md:px-0 mt-5">
        <h1 class="text-3xl font-bold text-blue-900 text-center mb-8">Patient Records</h1>
        <div class="flex flex-col space-y-6 w-full max-w-4xl mb-10">
            <?php if (!empty($patients)): ?>
                <?php foreach ($patients as $patient): ?>
                    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col md:flex-row items-center w-full md:w-auto">
                        <!-- Patient Image -->
                        <img src="<?= !empty($patient['Image']) ? '../1_Admin_Panel/uploads/' . htmlspecialchars($patient['Image']) : './assets/img/unknown.jpg' ?>"
                            alt="Patient Image" class="w-40 h-40 rounded-lg object-cover md:mr-6 mb-4 md:mb-0">

                        <!-- Patient Info -->
                        <div class="flex-1 flex flex-col md:flex-row justify-between items-start w-full">
                            <div class="flex flex-col md:items-start items-center text-center md:text-left w-full">
                                <h2 class="text-xl font-semibold text-blue-900"><?= htmlspecialchars($patient['Name']) ?></h2>
                                <p class="text-gray-700 text-lg"><strong>Age:</strong> <?= htmlspecialchars($patient['Age']) ?></p>
                            </div>
                            <div class="flex flex-col md:items-start items-center text-center md:text-left w-full">
                                <p class="text-gray-700 text-lg"><strong>Cancer:</strong> <?= htmlspecialchars($patient['CancerType']) ?></p>
                                <p class="text-gray-700 text-lg"><strong>Hospital:</strong> <?= htmlspecialchars($patient['Hospital']) ?></p>
                            </div>

                            <div class="flex flex-col md:items-start items-center text-center md:text-left w-full">
                                <p class="text-gray-700 text-lg"><strong>Country:</strong> <?= htmlspecialchars($patient['Country']) ?></p>
                            </div>
                        </div>

                        <!-- Buttons Section -->
                        <div class="flex flex-col items-center mt-4 md:mt-0 md:ml-6 space-y-2">
                            <!-- View More Button -->
                             <form action="./patient_det.php" method="POST">
                                            <input type="hidden" name="c_id" value="<?= htmlspecialchars($patient['CancerID']); ?>">
                                            <button type="submit" class="bg-blue-700 text-white px-3 py-[8px] rounded-lg text-lg hover:bg-blue-500 transition mt-4 md:mt-0 md:ml-[11px]">
                                                View More
                                            </button>
                                        </form>

                            <!-- Accept & Reject Buttons -->
                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg text-lg hover:bg-green-500 transition w-40" name="acceptbtn" type="submit">
                                Accept
                            </button>
                            <button class="bg-red-600 text-white px-6 py-2 rounded-lg text-lg hover:bg-red-500 transition w-40" name="rejectbtn" type="submit">
                                Reject
                            </button>
                        </div>
                    </div>
                    </form>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-700 text-center text-lg mt-[150px] font-semibold ml-[12px]">No patient records found.</p>
            <?php endif; ?>
        </div>
            </div>
</body>
</html>