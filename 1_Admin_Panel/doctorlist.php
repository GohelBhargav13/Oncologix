<?php
include './db_conn.php';
include './header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$doctorDetails = $conn->query("SELECT * FROM doctor_register");

// $count=0;
// $countcases = $conn->query("SELECT * FROM cases WHERE doc_id = $docc_id");
// while ($row = $countcases->fetch_assoc()) {
//     $_SESSION['casescount'] = $count++;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List - Oncologix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-white to-blue-200 min-h-screen">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-[20px] p-4 ml-[12px]">
        <?php while ($row = $doctorDetails->fetch_assoc()):
        ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6">
                <!-- Doctor Image and Name -->
                <div class="flex items-center space-x-4">
                    <img src="uploads/<?= htmlspecialchars($row['doc_photo']) ?>" alt="Doctor Photo" class="w-20 h-20 object-cover rounded-full border-2 border-blue-500" />
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Dr. <?= htmlspecialchars($row['name']); ?></h3>
                        <p class="text-sm text-gray-600">Specialization: <?= htmlspecialchars($row['specialization']); ?></p>
                    </div>
                </div>

                <!-- Doctor Details -->
                <div class="mt-4">
                    <p class="text-gray-700"><strong>Experience:</strong> <?= htmlspecialchars($row['experience']); ?> Years</p>
                    <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($row['email']); ?></p>
                </div>
                <form action="./doc.php" method="POST" class="grid place-items-start mt-[20px]">
                    <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($row['doc_id']); ?>">
                    <button type="submit" class="bg-blue-700 text-white px-3 py-[8px] rounded-lg text-lg hover:bg-blue-500 transition mt-4 md:mt-0 md:ml-[11px]">
                        View More
                    </button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>