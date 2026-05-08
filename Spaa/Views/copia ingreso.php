<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login usuario</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #74b9ff, #a29bfe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #e7e7e7ff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: center;
        }
        h1 {
            color: #2d3436;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #b2bec3;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #0984e3;
            box-shadow: 0 0 5px rgba(9, 132, 227, 0.3);
        }
        input[type="submit"] {
            background: #0984e3;
            color: white;
            font-weight: bold;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background: #74b9ff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php
            var_dump($_POST);
            if ($_POST){
                if ($_POST['nombre_usuario'] != "" && $_POST['correo_usuario'] != ""&& $_POST['contraseña_usuario'] != "") {
                    $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
                    if ($conexion->connect_error) {
                        die("Conexion Fallida: " . $conexion->connect_error);
                    }

                    $sql = "insert into productos(nombre_usuario, correo_usuario, contraseña_usuario)
                    values ('".$_POST['nombre_usuario']."', '".$_POST['correo_usuario']."', '".$_POST['contraseña_usuario']."')";
                    if ($conexion->query($sql) === TRUE) {
                        echo "Inicio de sesion completo";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conexion->error;
                    }
                    $conexion->commit();
                    $conexion->close();
                }
            }
        ?>
        <form action="" method="post">
            <input type="text" placeholder="Nombre de usuario" name="nombre_usuario" id="id_nombre_usuario">
            <input type="text" placeholder="Correo electrónico" name="correo_usuario">
            <input type="password" placeholder="Contraseña" name="contraseña_usuario">
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>