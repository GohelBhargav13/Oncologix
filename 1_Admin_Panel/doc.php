<?php
include './db_conn.php';
include './header.php';
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_id'])) {
    $doctor_id = intval($_POST['doctor_id']);
}elseif(isset($_GET['doctor_id'])){
    $doctor_id = intval($_GET['doctor_id']);
}else {
    
    echo "No selceted the id........";
}
$count=0;
$doctor_details = $conn->query("SELECT * FROM doctor_register WHERE doc_id = $doctor_id");

$countcases = $conn->query("SELECT * FROM cases WHERE doc_id = $doctor_id");
while ($row1 = $countcases->fetch_assoc()) {
    $_SESSION['casescount'] = $count++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient from-white to-blue-200">
<?php while($row = $doctor_details->fetch_assoc()): ?>
    <div class="container mx-auto p-8 bg-white/70 shadow-lg rounded-lg border border-gray-200 mb-8 w-full">
      <!-- Doctor Header -->
      <h1 class="text-4xl font-bold text-blue-900 mb-8">Dr. <?= htmlspecialchars($row['name']); ?> - Profile Details</h1>

      <!-- Doctor Image and Basic Details -->
      <div class="flex space-x-8 mb-8">
        <img src="uploads/<?= htmlspecialchars($row['doc_photo']) ?>" alt="Doctor Photo" class="w-50 h-50 object-cover rounded-lg border-4 border-blue-500" />
        <div class="mt-[60px]">
          <p class="text-xl font-semibold text-gray-800">Specialization: <?= htmlspecialchars($row['specialization']); ?></p>
          <p class="text-xl text-gray-600">Experience: <?= htmlspecialchars($row['experience']); ?> Years</p>
          <p class="text-xl text-gray-700">Email: <?= htmlspecialchars($row['email']); ?></p>
        </div>
      </div>

      <!-- Additional Details Section -->
      <div class="mt-8">
        <h2 class="text-2xl font-semibold text-blue-800 mb-4">Additional Details</h2>
        <p class="text-gray-700 text-lg font-semibold"><strong>Working Hours:</strong> 9:00 AM TO 7:00 PM</p>
        <p class="text-gray-700 text-lg font-semibold"><strong>Total cases: </strong><?= intval($count); ?></p>
      </div>

    </div>
  <?php endwhile; ?>
</body>
</html>