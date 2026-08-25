<?php
// Punto de entrada — instancia el Controller y delega todo
require_once __DIR__ . '/PerfilController.php';

$controller = new PerfilController();
$controller->manejarRequest();