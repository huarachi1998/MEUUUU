
<?php
require_once 'routeros_api.class.php';

$API = new RouterosAPI();
if ($API->connect('192.168.10.100', 'admin', 'mega1234')) {

    $activos = $API->comm('/ppp/active/print');
    $usuarios = $API->comm('/ppp/secret/print');

    $clientes = [];
    $tiempo_actual = time();
    $datosExistentes = file_exists("datos.json") ? json_decode(file_get_contents("datos.json"), true) : [];

    foreach ($usuarios as $u) {
        $pppoe = $u['name'];
        $conectado = false;
        $ip = '';
        $ultima_conexion = null;

        foreach ($activos as $a) {
            if ($a['name'] == $pppoe) {
                $conectado = true;
                $ip = $a['address'];
                $ultima_conexion = $tiempo_actual;
                break;
            }
        }

        $existente = isset($datosExistentes[$pppoe]) ? $datosExistentes[$pppoe] : [];

        // Coordenadas válidas si no existen o son incorrectas
        $x = $existente['x'] ?? rand(100, 800);
        $y = $existente['y'] ?? rand(100, 600);
        if ($x < -180 || $x > 180) $x = -68.13 + (rand(-500, 500) / 10000);
        if ($y < -90 || $y > 90) $y = -16.5 + (rand(-500, 500) / 10000);

        $clientes[$pppoe] = [
            'nombre' => $u['name'],
            'pppoe' => $pppoe,
            'ip' => $ip,
            'estado' => $conectado ? 'online' : 'offline',
            'nap' => $existente['nap'] ?? 'NAP-DESCONOCIDO',
            'x' => $x,
            'y' => $y,
            'ultima' => $ultima_conexion ?? ($existente['ultima'] ?? ($tiempo_actual - rand(0, 60*60*24*30)))
        ];
    }

    file_put_contents('datos.json', json_encode($clientes, JSON_PRETTY_PRINT));
    echo "✅ Clientes importados correctamente.";
    $API->disconnect();

} else {
    echo "❌ Error al conectar con MikroTik.";
}
?>
