<?php
require_once __DIR__ . '/vehicle.php';
$shercheDy = new Vehicule();
if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $searchDynamicByName = $_GET["letHimCoock"];
  $result = $shercheDy->searchByName($searchDynamicByName);
  echo json_encode($result);
}

?>