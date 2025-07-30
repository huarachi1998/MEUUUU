
<?php
$datos = json_decode(file_get_contents("../api/datos.json"), true);
$input = json_decode(file_get_contents("php://input"), true);

$pppoe = $input["pppoe"];
$nuevoNombre = $input["nombre"];
$nuevaNAP = $input["nap_id"];

foreach ($datos as &$nap) {
    foreach ($nap["clientes"] as $i => $cli) {
        if ($cli["pppoe"] === $pppoe) {
            if ($nap["nap_id"] === $nuevaNAP) {
                $nap["clientes"][$i]["nombre"] = $nuevoNombre;
            } else {
                $cliente = $nap["clientes"][$i];
                unset($nap["clientes"][$i]);
                foreach ($datos as &$destNAP) {
                    if ($destNAP["nap_id"] === $nuevaNAP && count($destNAP["clientes"]) < $destNAP["capacidad"]) {
                        $cliente["nombre"] = $nuevoNombre;
                        $destNAP["clientes"][] = $cliente;
                        break;
                    }
                }
            }
            break 2;
        }
    }
}

file_put_contents("../api/datos.json", json_encode(array_values($datos), JSON_PRETTY_PRINT));
echo json_encode(["status" => "ok"]);
?>
