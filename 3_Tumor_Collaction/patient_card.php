<?php
ob_start();
include("../1_Admin_Panel/db_conn.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cancerType = mysqli_real_escape_string($conn, $_POST['cancer_type']);
    header("Location: pat_card.php?cancer_type=" . urlencode($cancerType));
    exit();
}

$searchQuery = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$patients = [];
$count = 0;
// $status = null;

// if (isset($_SESSION['case_id'])) {
//     $case_id = $_SESSION['case_id'];
//     // echo "$case_id";
//     $stmt = $conn->prepare("SELECT approval_status FROM cases WHERE c_id = ?");
//     $stmt->bind_param("i", $case_id);
//     $stmt->execute();
//     $res = $stmt->get_result();

//     if ($row = $res->fetch_assoc()) {
//         $status = $row['approval_status'];
//     }
// }

if (isset($_GET['cancer_type'])) {
    $cancerType = mysqli_real_escape_string($conn, $_GET['cancer_type']);
    $query = "SELECT 
        ca.c_id as CancerID,
        p.P_name AS Name,
        p.age AS Age,
        p.p_image as Image,
        c.cancer_name AS CancerType,
        h.h_name AS Hospital,
        h.country AS Country,
        t.t_given AS TreatmentDetails
    FROM cases ca
    JOIN patient p ON ca.p_id = p.p_id
    JOIN cancer c ON ca.cancer_type = c.cancer_name
    JOIN hospital h ON ca.h_id = h.h_id
    LEFT JOIN treatment t ON ca.t_id = t.t_id
    WHERE c.cancer_name = '$cancerType'
    AND ca.approval_status = 'approved'";

    if (!empty($searchQuery)) {
        $query .= " AND (LOWER(p.P_name) LIKE LOWER('%$searchQuery%') 
                        OR LOWER(h.h_name) LIKE LOWER('%$searchQuery%') 
                        OR LOWER(h.country) LIKE LOWER('%$searchQuery%')
                        OR LOWER(c.cancer_name) LIKE LOWER('%$searchQuery%')
                        OR LOWER(p.age) LIKE LOWER('%$searchQuery%')
                        OR LOWER(t.t_given) LIKE LOWER('%$searchQuery%'))";
    }

    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $count++;
            $patients[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OncoLogix - Patient Cards</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
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
    <div class="count-box mt-[80px] lg:ml-[70px]">Patients: <?php echo $count; ?></div>

    <div class="mt-[30px] w-full flex justify-center px-6 lg:px-12">
        <form action="" method="GET" class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 bg-white shadow-md p-3 rounded-lg items-center ml-[70px]">
            <input type="hidden" name="cancer_type" value="<?= htmlspecialchars($_GET['cancer_type'] ?? '') ?>">
            <input type="text" id="search" name="search" placeholder="Search by Name / Hospital / Country / Treatment..."
                value="<?= htmlspecialchars($searchQuery) ?>"
                class="border p-2 w-[500px] rounded-md">
            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                Search
            </button>
        </form>
    </div>

        <div class="flex-grow flex flex-col justify-center items-center w-full px-4 md:px-0 mt-5">
            <h1 class="text-3xl font-bold text-blue-900 text-center mb-8">Patient Records</h1>
                        <div class="flex flex-col space-y-6 w-full max-w-4xl mb-10" id="patient-list">
                            <?php if (!empty($patients)): ?>
                                <?php foreach ($patients as $patient): ?>
                                    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col md:flex-row items-center md:justify-between w-full md:w-auto patient-card">
                                        <img src="<?= !empty($patient['Image']) ? '../1_Admin_Panel/uploads/' . htmlspecialchars($patient['Image']) : './assets/img/unknown.jpg' ?>"
                                            alt="Patient Image" class="w-40 h-40 rounded-lg object-cover mr-6 mb-4 md:mb-0">
                                        <div class="flex-1 flex flex-col md:flex-row justify-between items-center w-full">
                                            <div class="text-center md:text-left">
                                                <h2 class="text-xl font-semibold text-blue-900 patient-name"> <?= htmlspecialchars($patient['Name']) ?> </h2>
                                                <p class="text-gray-700 text-lg"><strong>Age:</strong> <?= htmlspecialchars($patient['Age']) ?></p>
                                            </div>
                                            <div>|</div>
                                            <div class="text-center md:text-left">
                                                <p class="text-gray-700 text-lg"><strong>Cancer:</strong> <?= htmlspecialchars($patient['CancerType']) ?></p>
                                                <p class="text-gray-700 text-lg patient-hospital"><strong>Hospital:</strong> <?= htmlspecialchars($patient['Hospital']) ?></p>
                                            </div>
                                            <div>|</div>
                                            <div class="text-center md:text-left">
                                                <p class="text-gray-700 text-lg patient-country"><strong>Country:</strong> <?= htmlspecialchars($patient['Country']) ?></p>
                                            </div>
                                        </div>
                                        <input type="hidden" class="treatment-details" value="<?= htmlspecialchars($patient['TreatmentDetails'] ?? '') ?>">
                                        <form action="./pat_details.php" method="POST">
                                            <input type="hidden" name="c_id" value="<?= htmlspecialchars($patient['CancerID']); ?>">
                                            <button type="submit" class="bg-blue-700 text-white px-3 py-[8px] rounded-lg text-lg hover:bg-blue-500 transition mt-4 md:mt-0 md:ml-[11px]">
                                                View More
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-700 text-center text-lg font-semibold">No patient records found for the selected criteria.</p>
                            <?php endif;
                            ob_end_flush(); ?>
                        </div>
        </div>
    </body>

    </html>