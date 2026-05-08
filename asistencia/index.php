<?php
include("conexion.php");

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = trim($_POST["dni"]);

    
    $sql = "SELECT * FROM personas WHERE documento = '$dni'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $persona = $resultado->fetch_assoc();
        $persona_id = $persona["id"];

       
        $fecha_hoy = date("Y-m-d");
        $verificar = "SELECT * FROM asistencias WHERE persona_id = $persona_id AND fecha = '$fecha_hoy'";
        $verificar_resultado = $conexion->query($verificar);

        if ($verificar_resultado->num_rows == 0) {
            
            $hora = date("H:i:s");
            $insertar = "INSERT INTO asistencias (persona_id, fecha, hora) VALUES ($persona_id, '$fecha_hoy', '$hora')";
            $conexion->query($insertar);
            $mensaje = "Asistencia registrada para " . $persona["nombre"] . " " . $persona["apellido"];
        } else {
            $mensaje = "Esta persona ya marcó asistencia hoy.";
        }
    } else {
        $mensaje = "DNI no encontrado.";
    }
}


$fecha_hoy = date("Y-m-d");
$listado = "
    SELECT p.nombre, p.apellido, p.documento, a.hora 
    FROM asistencias a
    JOIN personas p ON a.persona_id = p.id
    WHERE a.fecha = '$fecha_hoy'
    ORDER BY a.hora ASC
";
$resultado_listado = $conexion->query($listado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Asistencia</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; }
        h1 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 8px; font-size: 16px; }
        input[type="submit"] { padding: 8px 16px; font-size: 16px; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        .mensaje { margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>

    <h1>📋 Sistema de Asistencia</h1>

    <form method="POST" action="">
        <label for="dni">Ingrese DNI:</label>
        <input type="text" name="dni" id="dni" required>
        <input type="submit" value="Guardar Asistencia">
    </form>

    <div class="mensaje"><?= $mensaje ?></div>

    <h2>Asistencias del día <?= date("d/m/Y") ?></h2>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Hora</th>
        </tr>
        <?php while ($fila = $resultado_listado->fetch_assoc()) { ?>
        <tr>
            <td><?= $fila["nombre"] ?></td>
            <td><?= $fila["apellido"] ?></td>
            <td><?= $fila["documento"] ?></td>
            <td><?= $fila["hora"] ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
