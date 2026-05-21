<?php
// Punto de entrada — instancia el Controller y delega todo
require_once __DIR__ . '/../Controllers/PerfilController.php';

$controller = new PerfilController();
$controller->manejarRequest();