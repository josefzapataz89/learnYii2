CREATE TABLE IF NOT EXISTS alumnos
(
	id_alumno INT(11) NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(50) NOT NULL,
	apellidos VARCHAR(80) NOT NULL,
	clase INT(11) NOT NULL,
	note_final FLOAT NOT NULL,
	PRIMARY KEY(id_alumno)
);