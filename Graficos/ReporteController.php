<?php
require_once __DIR__ . '/ServicioModel.php';

class ReporteController {
    public function graficoServiciosMasSolicitados() {
        // Si no viene mes/año por la URL, usamos el mes actual por defecto
        if (isset($_GET['mesAnio']) && strpos($_GET['mesAnio'], '-') !== false) {
            [$mes, $anio] = explode('-', $_GET['mesAnio']);
            $mes = (int)$mes;
            $anio = (int)$anio;
        } else {
            $mes = (int)date('n');
            $anio = (int)date('Y');
        }

        $model = new ServicioModel();

        $datosServicios = $model->serviciosMasSolicitados($mes, $anio);
        $servicios = array_column($datosServicios, 'Nombre_servicio');
        $cantidadesServicios = array_column($datosServicios, 'cantidad');

        $datosEmpleados = $model->turnosPorEmpleado($mes, $anio);
        $empleados = array_column($datosEmpleados, 'nombre_empleado');
        $cantidadesEmpleados = array_column($datosEmpleados, 'cantidad');

        $datosDiaSemana = $model->turnosPorDiaSemana($mes, $anio);
        $diasSemana = array_column($datosDiaSemana, 'dia');
        $cantidadesDiaSemana = array_column($datosDiaSemana, 'cantidad');

        $datosFranjaHoraria = $model->turnosPorFranjaHoraria($mes, $anio);
        $franjasHorarias = array_column($datosFranjaHoraria, 'franja');
        $cantidadesFranjaHoraria = array_column($datosFranjaHoraria, 'cantidad');

        require __DIR__ . '/GraficoServicio.php';
    }
}

// Punto de entrada: esto hace que ReporteController.php sea accesible
// directamente por URL, igual que tu TurnoController.php
$controller = new ReporteController();
$controller->graficoServiciosMasSolicitados();