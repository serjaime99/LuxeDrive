<?php
require_once __DIR__ . '/vehicle.php';

$vehiculs = new Vehicule();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $categoryId = $_GET["category_id"];

  if ($categoryId == "all") {
    $vehicles = $vehiculs->showAllVehicule();
  } else {
    $vehicles = $vehiculs->getVehiculesByCategorie($categoryId);
  }

  echo json_encode($vehicles);
}
