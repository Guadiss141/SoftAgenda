<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turno - SoftAgenda</title>

    <style>

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
            background: url('img/relax.png') no-repeat center center fixed;
            background-size: cover;
            background-color: #fcf9f2;
            color: #4a4a4a;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(253, 251, 247, 0.88);
            z-index: -1;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h1, h2 {
            color: #5c4a3d;
            font-weight: 300;
            letter-spacing: 1px;
            border-bottom: 2px solid #e9e3dc;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #e9edc9;
            color: #4e5a37;
            border-left: 4px solid #a3b18a;
        }

        .alert-danger {
            background: #f9ebea;
            color: #78281f;
            border-left: 4px solid #d98880;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-row > .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 500;
            color: #5c4a3d;
            margin-bottom: 8px;
        }

        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #dcd3cb;
            box-sizing: border-box;
            background: #fff;
            transition: 0.3s;
            color: #555;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #8da399;
        }

        .button-group {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        button,
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background: #8da399;
            color: white;
        }

        .btn-secondary {
            background: #dcd3cb;
            color: #5c4a3d;
        }

        button:hover,
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .info-box {
            background: #fdfbf7;
            padding: 20px;
            border-radius: 15px;
            border-left: 6px solid #b5a397;
            margin-bottom: 25px;
        }

        .info-box p {
            margin: 8px 0;
            color: #555;
        }

        .info-box strong {
            color: #5c4a3d;
        }

    </style>

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <h1>Editar Turno</h1>

    <?php echo $mensaje; ?>

    <div class="info-box">

        <p>
            <strong>Servicio actual:</strong>
            <?php echo htmlspecialchars($turno['Nombre_servicio']); ?>
        </p>

        <p>
            <strong>Terapeuta actual:</strong>
            <?php
            echo htmlspecialchars(
                $turno['Terapeuta_Nombre'] . ' ' .
                $turno['Terapeuta_Apellido']
            );
            ?>
        </p>

        <p>
            <strong>Fecha y hora actual:</strong>

            <?php
            echo date(
                'd/m/Y H:i',
                strtotime(
                    $turno['Fecha_Turno'] . ' ' .
                    $turno['Hora_Turno']
                )
            );
            ?>
        </p>

    </div>

    <form method="POST">

        <input type="hidden" name="action" value="actualizar_turno">

        <div class="form-row">

            <div class="form-group">

                <label for="fecha">
                    Fecha del Turno:
                </label>

                <input
                    type="date"
                    id="fecha"
                    name="fecha"
                    value="<?php echo $turno['Fecha_Turno']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="hora">
                    Hora del Turno:
                </label>

                <input
                    type="time"
                    id="hora"
                    name="hora"
                    value="<?php echo substr($turno['Hora_Turno'], 0, 5); ?>"
                    required
                >

            </div>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label for="servicio">
                    Servicio:
                </label>

                <select id="servicio" name="servicio" required>

                    <option value="">
                        -- Selecciona un servicio --
                    </option>

                    <?php foreach ($servicios as $servicio): ?>

                        <option
                            value="<?php echo $servicio['id_Servicio']; ?>"

                            <?php
                            echo (
                                $servicio['id_Servicio']
                                == $turno['id_Servicio']
                            )
                            ? 'selected'
                            : '';
                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $servicio['Nombre_servicio']
                            );
                            ?>

                            - $

                            <?php echo $servicio['Costo']; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label for="empleado">
                    Terapeuta:
                </label>

                <select id="empleado" name="empleado" required>

                    <option value="">
                        -- Selecciona un terapeuta --
                    </option>

                    <?php foreach ($empleados as $empleado): ?>

                        <option
                            value="<?php echo $empleado['id_Empleado']; ?>"

                            <?php
                            echo (
                                $empleado['id_Empleado']
                                == $turno['id_Empleado']
                            )
                            ? 'selected'
                            : '';
                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $empleado['Persona_Nombre']
                                . ' ' .
                                $empleado['Persona_Apellido']
                            );
                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <div class="form-group">

            <label for="estado">
                Estado del Turno:
            </label>

            <select id="estado" name="estado" required>

                <option value="Pendiente"
                    <?php echo ($turno['Estado_Turno'] == 'Pendiente') ? 'selected' : ''; ?>>
                    Pendiente
                </option>

                <option value="Confirmado"
                    <?php echo ($turno['Estado_Turno'] == 'Confirmado') ? 'selected' : ''; ?>>
                    Confirmado
                </option>

                <option value="Completado"
                    <?php echo ($turno['Estado_Turno'] == 'Completado') ? 'selected' : ''; ?>>
                    Completado
                </option>

                <option value="Cancelado"
                    <?php echo ($turno['Estado_Turno'] == 'Cancelado') ? 'selected' : ''; ?>>
                    Cancelado
                </option>

            </select>

        </div>

        <div class="button-group">

            <button type="submit" class="btn btn-primary">
                Guardar Cambios
            </button>

            <a href="perfil.php" class="btn btn-secondary">
                Cancelar
            </a>

        </div>

    </form>

</div>

</body>
</html>