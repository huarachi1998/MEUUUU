
<?php
require_once '../routeros_api.class.php';
$API = new RouterosAPI();
$estado = [];

if ($API->connect('192.168.10.100', 'admin', 'mega1234')) {
    $activos = $API->comm('/ppp/active/print');
    foreach ($activos as $cli) {
        $estado[$cli['name']] = ['online' => true, 'ip' => $cli['address']];
    }

    $morosos = $API->comm('/ip/firewall/address-list/print', ["?list" => "morosos"]);
    foreach ($morosos as $entry) {
        $nombre = $entry['address'];
        if (!isset($estado[$nombre])) $estado[$nombre] = ['online' => false];
        $estado[$nombre]['moroso'] = true;
    }

    $API->disconnect();
}

header('Content-Type: application/json');
echo json_encode($estado);
?>
