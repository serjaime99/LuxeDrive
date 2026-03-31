<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../classes/categorie.php';
require_once '../classes/vehicle.php';
require_once '../classes/reserve.php';

$vehicule = new Vehicule();
$categorie = new Categorie();
$reservation = new reservation();

// Handle form submissions
if (isset($_POST['Category_submit'])) {
    $categorie_name = $_POST['cat_name'];
    $categorie_desc = $_POST['cat_desc'];
    $categorie->ajouterCategorie($categorie_name, $categorie_desc);
}

if (isset($_POST['Vehicle_submit'])) {
    $modele = $_POST['modele'];
    $marque = $_POST['marque'];
    $Category = $_POST['Category'];
    $price = $_POST['price'];
    $Vehicle_Image = $_POST['Vehicle_Image'];
    $vehicule->AjouterVehicule($modele, $marque, $price, $Vehicle_Image, $Category);
}

if (isset($_POST['deletevehicule_id'])) {
    $vehicule_id = $_POST['deletevehicule_id'];
    if ($vehicule->removeVehicule($vehicule_id)) {
        header('Location: ../pages/dashboard.php');
        exit();
    }
}

if (isset($_POST['approve_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    if ($reservation->updateReservationStatus($reservation_id, 'accepte')) {
        header('Location: ../pages/dashboard.php');
        exit();
    }
}

if (isset($_POST['refuse_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    if ($reservation->updateReservationStatus($reservation_id, 'refuse')) {
        header('Location: ../pages/dashboard.php');
        exit();
    }
}

if ($_SESSION['role_id'] == 1) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LuxeDrive - Admin Dashboard</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            .bg-gradient-primary {
                background: linear-gradient(135deg, rgb(37, 99, 235), rgb(37, 143, 235));
            }
            .shadow-soft {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            .modal.active {
                display: flex;
            }
        </style>
    </head>

    <body class="bg-gray-100">
        <!-- Top Navigation -->
        <nav class="bg-gradient-primary shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-white">LuxeDrive Admin</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="menu.php" class="text-white hover:text-gray-200 transition-all">View Collection</a>
                        <span class="text-white font-medium"><?php echo $_SESSION['nom'] ?></span>
                        <a href="../profils/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all font-bold">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            
            <!-- Category Management -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Categories Management</h2>
                <button onclick="openModal('addCategoryModal')" class="bg-gradient-primary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all">
                    Add Category
                </button>
            </div>
            <div class="bg-white rounded-lg shadow-soft overflow-hidden mb-8">
                <table class="w-full">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $arrays = $categorie->showCategorie();
                        foreach ($arrays as $array) {
                        ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo $array['Categorie_id'] ?></td>
                                <td class="px-6 py-4 font-bold"><?php echo $array['nom'] ?></td>
                                <td class="px-6 py-4"><?php echo $array['description'] ?></td>
                                <td class="px-6 py-4 flex gap-4">
                                    <button class="text-blue-600 hover:text-blue-800">Edit</button>
                                    <button class="text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Vehicles Management -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Vehicles Management</h2>
                <button onclick="openModal('addVehicleModal')" class="bg-gradient-primary text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all">
                    Add Vehicle
                </button>
            </div>
            <div class="bg-white rounded-lg shadow-soft overflow-hidden mb-8">
                <table class="w-full">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Model</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Marque</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Price/Day</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $rows = $vehicule->showAllVehicule();
                        foreach ($rows as $row) {
                        ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo $row['vehicule_id'] ?></td>
                                <td class="px-6 py-4 font-bold"><?php echo $row['modele'] ?></td>
                                <td class="px-6 py-4"><?php echo $row['marque'] ?></td>
                                <td class="px-6 py-4"><?php echo $row['nom'] ?></td>
                                <td class="px-6 py-4">$<?php echo $row['prix'] ?></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"><?php echo $row['status'] ?></span></td>
                                <td class="px-6 py-4">
                                    <form action="" method="post">
                                        <input type="hidden" name="deletevehicule_id" value="<?php echo $row['vehicule_id'] ?>">
                                        <button class="text-red-600 hover:text-red-800 font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Reservations Management -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Reservations Management</h2>
            <div class="bg-white rounded-lg shadow-soft overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Start</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">End</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $reservations = $reservation->getAllReservations();
                        foreach ($reservations as $res) {
                            $status_color = $res['status'] == 'waiting' ? 'bg-yellow-100 text-yellow-800' : ($res['status'] == 'accepte' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                        ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo $res['client_name'] ?></td>
                                <td class="px-6 py-4"><?php echo $res['vehicle_name'] ?></td>
                                <td class="px-6 py-4"><?php echo $res['date_debut'] ?></td>
                                <td class="px-6 py-4"><?php echo $res['date_fin'] ?></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs <?php echo $status_color ?>"><?php echo $res['status'] ?></span></td>
                                <td class="px-6 py-4">
                                    <?php if ($res['status'] == 'waiting') { ?>
                                        <form method="POST" class="flex space-x-2">
                                            <input type="hidden" name="reservation_id" value="<?php echo $res['reservation_id'] ?>">
                                            <button type="submit" name="approve_reservation" class="text-green-600 font-bold">Approve</button>
                                            <button type="submit" name="refuse_reservation" class="text-red-600 font-bold">Refuse</button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Vehicle Modal -->
        <div id="addVehicleModal" class="modal">
            <div class="bg-white rounded-lg w-1/2 p-6 shadow-2xl">
                <h3 class="text-xl font-bold mb-4">Add New Vehicle</h3>
                <form class="space-y-4" method="post">
                    <input type="text" placeholder="Model" class="w-full border p-2 rounded" name="modele" required>
                    <input type="text" placeholder="Marque" class="w-full border p-2 rounded" name="marque" required>
                    <select class="w-full border p-2 rounded" name="Category" required>
                        <?php
                        $arr = $categorie->showCategorie();
                        foreach ($arr as $row) {
                            echo "<option value='" . $row['Categorie_id'] . "'>" . $row['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" placeholder="Price" class="w-full border p-2 rounded" name="price" required>
                    <input type="text" placeholder="Image URL (can be empty)" class="w-full border p-2 rounded" name="Vehicle_Image">
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('addVehicleModal')" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" name="Vehicle_submit" class="px-4 py-2 bg-gradient-primary text-white rounded">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div id="addCategoryModal" class="modal">
            <div class="bg-white rounded-lg w-1/3 p-6 shadow-2xl">
                <h3 class="text-xl font-bold mb-4">Add New Category</h3>
                <form class="space-y-4" method="POST">
                    <input type="text" name="cat_name" placeholder="Name" class="w-full border p-2 rounded" required>
                    <textarea name="cat_desc" placeholder="Description" class="w-full border p-2 rounded"></textarea>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('addCategoryModal')" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" name="Category_submit" class="px-4 py-2 bg-gradient-primary text-white rounded">Add Category</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModal(id) { document.getElementById(id).classList.add('active'); }
            function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        </script>
    </body>
    </html>
<?php
} else {
    header('location:../index.php');
    exit();
}
?>