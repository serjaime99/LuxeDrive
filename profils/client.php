<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not authorized
if ($_SESSION['role_id'] != 2) {
    header('location: ../index.php');
    exit();
}

require_once __DIR__ . '/../classes/reserve.php';
$reservation = new reservation();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - LuxeDrive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen p-8">
        <!-- Header with Logout -->
        <div class="flex justify-between items-center mb-8 bg-gradient-to-r from-blue-800 to-blue-600 text-white p-6 rounded-2xl shadow-xl">
            <h1 class="text-3xl font-bold tracking-tight">Personal Dashboard</h1>
            <div class="flex gap-4">
                <a href="../index.php" class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-bold transition duration-300">Home</a>
                <form action="logout.php" method="post">
                    <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-xl flex items-center font-bold transition duration-300" name="LogoutBTN">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column - Profile Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-32"></div>
                    <div class="relative px-4 pb-8">
                        <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nom']); ?>&background=random&size=128" alt="Profile" class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-2xl">
                        </div>
                        <div class="pt-20 text-center">
                            <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION["nom"] . " " . $_SESSION["prenom"]); ?></h2>
                            <p class="text-blue-600 font-bold uppercase tracking-widest text-sm mt-1">Premium Client</p>
                            <p class="text-gray-500 mt-4 bg-gray-50 py-2 rounded-lg border"><?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Account Summary -->
                <div class="bg-white rounded-2xl shadow-xl mt-8 p-8 border border-gray-100">
                    <h3 class="text-lg font-bold mb-6 text-gray-800 border-b pb-4">Account Summary</h3>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-400 font-bold uppercase">Phone</p>
                                <p class="text-sm font-semibold">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-600">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-400 font-bold uppercase">Location</p>
                                <p class="text-sm font-semibold">New York, USA</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-400 font-bold uppercase">Member Since</p>
                                <p class="text-sm font-semibold">March 2024</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Reservations -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex justify-between items-center mb-8 border-b pb-6">
                        <h3 class="text-2xl font-bold text-gray-900">My Vehicle History</h3>
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest">Active Requests</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-gray-100">
                                    <th class="pb-4 font-bold text-gray-400 uppercase text-xs tracking-wider">Vehicle Details</th>
                                    <th class="pb-4 font-bold text-gray-400 uppercase text-xs tracking-wider">Rental Period</th>
                                    <th class="pb-4 font-bold text-gray-400 uppercase text-xs tracking-wider">Total Price</th>
                                    <th class="pb-4 font-bold text-gray-400 uppercase text-xs tracking-wider">Status</th>
                                    <th class="pb-4 font-bold text-gray-400 uppercase text-xs tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php
                                $user_reservations = $reservation->showClientReservations();
                                foreach ($user_reservations as $row) {
                                    $statusClass = $row['status'] === 'accepte' ? 'bg-green-100 text-green-700' : ($row['status'] === 'refuse' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                    $statusIcon = $row['status'] === 'accepte' ? 'fa-check-circle' : ($row['status'] === 'refuse' ? 'fa-times-circle' : 'fa-clock');
                                ?>
                                    <tr class="hover:bg-gray-50 transition duration-300">
                                        <td class="py-6">
                                            <div class="font-bold text-gray-900"><?php echo htmlspecialchars($row['marque'] . ' ' . $row['modele']); ?></div>
                                            <div class="text-xs text-gray-400">Premium Series</div>
                                        </td>
                                        <td class="py-6">
                                            <div class="text-sm font-semibold text-gray-700"><?php echo date('M d', strtotime($row['date_debut'])); ?> - <?php echo date('M d', strtotime($row['date_fin'])); ?></div>
                                            <div class="text-xs text-gray-400"><?php echo date('Y', strtotime($row['date_debut'])); ?></div>
                                        </td>
                                        <td class="py-6">
                                            <div class="text-lg font-bold text-blue-600">$<?php echo number_format($row['prix'], 2); ?></div>
                                        </td>
                                        <td class="py-6">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase flex items-center w-fit <?php echo $statusClass; ?>">
                                                <i class="fas <?php echo $statusIcon; ?> mr-1"></i> <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-6 text-right">
                                            <?php if ($row['status'] == 'waiting') { ?>
                                                <form action="../classes/user.php" method="POST">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition duration-300">
                                                        Cancel Request
                                                    </button>
                                                </form>
                                            <?php } else { ?>
                                                <button class="text-gray-300 cursor-not-allowed text-xs font-bold uppercase">Locked</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if (empty($user_reservations)) { ?>
                            <div class="text-center py-12">
                                <i class="fas fa-folder-open text-gray-200 text-5xl mb-4"></i>
                                <p class="text-gray-400 font-medium">No reservations found. Time to hit the road!</p>
                                <a href="../pages/menu.php" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Browse Collection</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
