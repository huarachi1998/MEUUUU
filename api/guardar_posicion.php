
<?php
$datos = json_decode(file_get_contents("../api/datos.json"), true);
$input = json_decode(file_get_contents("php://input"), true);
$pppoe = $input["pppoe"];
$lat = $input["lat"];
$lng = $input["lng"];

foreach ($datos as &$nap) {
  foreach ($nap["clientes"] as &$cli) {
    if ($cli["pppoe"] === $pppoe) {
      $cli["lat"] = $lat;
      $cli["lng"] = $lng;
      break 2;
    }
  }
}

file_put_contents("../api/datos.json", json_encode($datos, JSON_PRETTY_PRINT));
echo json_encode(["status" => "ok"]);
?>
