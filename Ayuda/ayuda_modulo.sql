-- Registra el módulo "Ayuda" para que aparezca en el menú del administrador (rol 3).
-- Ejecutar UNA sola vez en la base gestionspabd (phpMyAdmin / HeidiSQL de Laragon).
--
-- Las tablas ayuda_faq y ayuda_config NO hace falta crearlas: se crean solas
-- la primera vez que el administrador entra a la pantalla de Ayuda.

INSERT INTO modulo (Nombre, Descripcion, Ruta, Icono)
VALUES ('Ayuda', 'Preguntas frecuentes, contacto y redes sociales', '/Spaa/Ayuda/AyudaController.php', 'bx-help-circle');

INSERT INTO modulo_rol (id_Modulo, id_Rol)
VALUES (LAST_INSERT_ID(), 3);
