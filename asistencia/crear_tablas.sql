CREATE DATABASE IF NOT EXISTS asistencia_db;
USE asistencia_db;

CREATE TABLE personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    documento VARCHAR(12) UNIQUE
);

INSERT INTO personas (nombre, apellido, documento) VALUES
('Ramiro', 'Fleitas', '46394033'),
('Guadalupe', 'Villalba Garcia', '48323011'),
('Juan', 'Ocampo', '11203093'),
('Lucía', 'Rodriguez', '48408400'),
('Alejandro', 'Magnumefisto', '12123123');

CREATE TABLE asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT,
    fecha DATE,
    hora TIME,
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);
