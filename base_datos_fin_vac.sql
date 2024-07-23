-- Creación de la tabla VACUNA
CREATE TABLE VACUNA (
    id_vacuna INT PRIMARY KEY AUTO_INCREMENT,
    nombre_vacuna VARCHAR(255) NOT NULL,
    descripcion TEXT,
    edad_recomendada INT NOT NULL,
    intervalo_dias INT NOT NULL,
    dosis_total INT NOT NULL,
    fecha_vencimiento DATE,
    stock INT NOT NULL
);

-- Inserción de datos en la tabla VACUNA
INSERT INTO VACUNA (nombre_vacuna, descripcion, edad_recomendada, intervalo_dias, dosis_total, fecha_vencimiento, stock)
VALUES
('BCG', 'Vacuna contra la tuberculosis', 0, 0, 1, '2024-07-24', 500),
('ANTIPOLIO', 'Vacuna contra la poliomielitis', 2, 60, 3, '2024-11-30', 200),
('PENTAVALENTE', 'Vacuna contra la difteria, tétanos, tos ferina, hepatitis B y Hib', 2, 60, 3, '2025-03-15', 400),
('ANTINEUMOCOCCICA', 'Vacuna contra el neumococo', 2, 60, 3, '2024-10-31', 250),
('ANTIROTAVIRICA', 'Vacuna contra el rotavirus', 2, 60, 3, '2025-02-28', 150),
('SRP', 'Vacuna contra el sarampión, rubéola y paperas', 12, 0, 1, '2024-09-30', 200),
('VIA', 'Vacuna contra la varicela', 12, 0, 1, '2025-05-31', 150),
('INFLUENZA', 'Vacuna contra la influenza', 6, 180, 2, '2025-06-30', 100);

-- Creación de la tabla PROVEEDOR
CREATE TABLE PROVEEDOR (
    id_proveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre_proveedor VARCHAR(255) NOT NULL,
    direccion TEXT,
    ciudad VARCHAR(255),
    telefono VARCHAR(20),
    correo VARCHAR(255)
);

-- Inserción de datos en la tabla PROVEEDOR
INSERT INTO PROVEEDOR (nombre_proveedor, direccion, ciudad, telefono, correo)
VALUES
('Laboratorios S.A.', 'Av. Siempre Viva 123', 'Ciudad', '123456789', 'contacto@labs.com'),
('Vacunas Plus', 'Calle Salud 456', 'Ciudad', '987654321', 'info@vacunasplus.com'),
('BioTech', 'Av. Ciencia 789', 'Ciudad', '1122334455', 'ventas@biotech.com');

-- Creación de la tabla PEDIDO
CREATE TABLE PEDIDO (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_proveedor INT,
    id_vacuna INT,
    fecha_pedido DATE NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_proveedor) REFERENCES PROVEEDOR(id_proveedor),
    FOREIGN KEY (id_vacuna) REFERENCES VACUNA(id_vacuna)
);

-- Inserción de datos en la tabla PEDIDO
INSERT INTO PEDIDO (id_proveedor, id_vacuna, fecha_pedido, cantidad)
VALUES
(1, 1, '2024-07-01', 100),
(2, 2, '2024-06-15', 150),
(3, 3, '2024-07-10', 200),
(1, 4, '2024-05-20', 50),
(2, 5, '2024-06-25', 100);

-- Creación de la tabla DETALLE_PEDIDO
CREATE TABLE DETALLE_PEDIDO (
    id_detalle_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES PEDIDO(id_pedido)
);

-- Inserción de datos en la tabla DETALLE_PEDIDO
INSERT INTO DETALLE_PEDIDO (id_pedido, cantidad)
VALUES
(1, 100),
(2, 150),
(3, 200),
(4, 50),
(5, 100);

-- Creación de la tabla NIÑO
CREATE TABLE NIÑO (
    id_niño INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    fecha_nac DATE NOT NULL,
    sexo CHAR(1) NOT NULL
);

-- Inserción de datos en la tabla NIÑO
INSERT INTO NIÑO (nombre, apellido, fecha_nac, sexo)
VALUES
('Juan', 'Pérez', '2024-05-17', 'M'),
('María', 'García', '2024-02-15', 'F'),
('Pedro', 'Rodríguez', '2024-03-20', 'M'),
('Ana', 'López', '2024-04-25', 'F'),
('Luis', 'Martínez', '2024-05-30', 'M');

-- Creación de la tabla REGISTRO_VACUNAS
CREATE TABLE REGISTRO_VACUNAS (
    id_registro INT PRIMARY KEY AUTO_INCREMENT,
    id_niño INT,
    id_vacuna INT,
    fecha_aplicacion DATE,
    dosis INT,
    aplicada BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_niño) REFERENCES NIÑO(id_niño),
    FOREIGN KEY (id_vacuna) REFERENCES VACUNA(id_vacuna)
);

-- Inserción de datos en la tabla REGISTRO_VACUNAS
INSERT INTO REGISTRO_VACUNAS (id_niño, id_vacuna, fecha_aplicacion, dosis, aplicada)
VALUES
(1, 1, '2024-05-17', 1, TRUE),
(2, 2, '2024-02-15', 1, TRUE),
(3, 3, '2024-03-20', 1, TRUE),
(4, 4, '2024-04-25', 1, TRUE),
(5, 5, '2024-05-30', 1, TRUE),
(1, 2, '2024-07-17', 1, FALSE),
(1, 3, '2024-07-17', 1, FALSE),
(1, 4, '2024-07-17', 1, FALSE),
(1, 5, '2024-07-17', 1, FALSE);

-- Creación de la tabla NOTIFICACIONES
CREATE TABLE NOTIFICACIONES (
    id_notificacion INT PRIMARY KEY AUTO_INCREMENT,
    mensaje VARCHAR(255),
    fecha DATE
);

-- Cambiamos el delimitador para crear los triggers
DELIMITER $$

-- Trigger para notificación de vencimiento de vacuna
CREATE TRIGGER notificacion_vencimiento_vacunas
AFTER UPDATE ON VACUNA
FOR EACH ROW
BEGIN
    DECLARE v_dias_restantes INT;

    SET v_dias_restantes = DATEDIFF(NEW.fecha_vencimiento, CURDATE());

    IF v_dias_restantes = 7 THEN
        INSERT INTO NOTIFICACIONES (mensaje, fecha)
        VALUES (CONCAT('La vacuna ', NEW.nombre_vacuna, ' está a punto de vencer en 7 días.'), CURDATE());
    END IF;
END$$

-- Trigger para verificar la edad del niño basado en el esquema de vacunación
CREATE TRIGGER verificar_edad_nino
BEFORE INSERT ON REGISTRO_VACUNAS
FOR EACH ROW
BEGIN
    DECLARE v_fecha_nac DATE;
    DECLARE v_nombre_vacuna VARCHAR(255);
    DECLARE v_edad_actual INT;
    DECLARE v_mes_aplicacion INT;

    SELECT fecha_nac INTO v_fecha_nac
    FROM NIÑO
    WHERE id_niño = NEW.id_niño;
    
    SELECT nombre_vacuna INTO v_nombre_vacuna
    FROM VACUNA
    WHERE id_vacuna = NEW.id_vacuna;

    SET v_edad_actual = TIMESTAMPDIFF(DAY, v_fecha_nac, NEW.fecha_aplicacion);
    SET v_mes_aplicacion = v_edad_actual DIV 30;

    -- Establecer los rangos de edad basados en el esquema
    IF v_nombre_vacuna = 'BCG' AND (v_mes_aplicacion > 1 OR (v_edad_actual > 1 AND v_mes_aplicacion = 0)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna BCG debe ser aplicada dentro del primer mes de vida.';
    ELSEIF v_nombre_vacuna = 'ANTIPOLIO' AND NOT (v_mes_aplicacion IN (2, 4, 6)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna ANTIPOLIO debe ser aplicada en los meses 2, 4, o 6.';
    ELSEIF v_nombre_vacuna = 'PENTAVALENTE' AND NOT (v_mes_aplicacion IN (2, 4, 6)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna PENTAVALENTE debe ser aplicada en los meses 2, 4, o 6.';
    ELSEIF v_nombre_vacuna = 'ANTINEUMOCOCCICA' AND NOT (v_mes_aplicacion IN (2, 4, 6)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna ANTINEUMOCOCCICA debe ser aplicada en los meses 2, 4, o 6.';
    ELSEIF v_nombre_vacuna = 'ANTIROTAVIRICA' AND NOT (v_mes_aplicacion IN (2, 4, 6)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna ANTIROTAVIRICA debe ser aplicada en los meses 2, 4, o 6.';
    ELSEIF v_nombre_vacuna = 'SRP' AND NOT (v_mes_aplicacion IN (12, 13)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna SRP debe ser aplicada en el mes 12 o 13.';
    ELSEIF v_nombre_vacuna = 'VIA' AND NOT (v_mes_aplicacion IN (12, 13)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna VIA debe ser aplicada en el mes 12 o 13.';
    ELSEIF v_nombre_vacuna = 'INFLUENZA' AND (v_mes_aplicacion < 6 OR v_mes_aplicacion > 23) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La vacuna INFLUENZA debe ser aplicada entre los meses 6 y 23.';
    END IF;
END$$

-- Trigger para actualizar las vacunas a aplicar hoy
CREATE TRIGGER vacunas_hoy
AFTER INSERT ON NIÑO
FOR EACH ROW
BEGIN
    DECLARE v_fecha_nac DATE;
    DECLARE v_edad_actual INT;

    SELECT fecha_nac INTO v_fecha_nac
    FROM NIÑO
    WHERE id_niño = NEW.id_niño;

    SET v_edad_actual = TIMESTAMPDIFF(DAY, v_fecha_nac, CURDATE());

    IF v_edad_actual >= 60 AND v_edad_actual <= 61 THEN
        INSERT INTO REGISTRO_VACUNAS (id_niño, id_vacuna, fecha_aplicacion, dosis, aplicada)
        VALUES (NEW.id_niño, 2, CURDATE(), 1, FALSE), 
               (NEW.id_niño, 3, CURDATE(), 1, FALSE), 
               (NEW.id_niño, 4, CURDATE(), 1, FALSE), 
               (NEW.id_niño, 5, CURDATE(), 1, FALSE);
    END IF;
END$$

-- Trigger para verificar y notificar las vacunas a aplicar hoy
CREATE TRIGGER notificacion_vacunas_hoy
AFTER INSERT ON REGISTRO_VACUNAS
FOR EACH ROW
BEGIN
    DECLARE v_fecha_aplicacion DATE;
    DECLARE v_nombre VARCHAR(255);
    DECLARE v_apellido VARCHAR(255);

    SET v_fecha_aplicacion = CURDATE();

    SELECT nombre, apellido INTO v_nombre, v_apellido
    FROM NIÑO
    WHERE id_niño = NEW.id_niño;

    IF NEW.fecha_aplicacion = v_fecha_aplicacion AND NEW.aplicada = FALSE THEN
        INSERT INTO NOTIFICACIONES (mensaje, fecha)
        VALUES (CONCAT('Hoy se debe aplicar la vacuna ', (SELECT nombre_vacuna FROM VACUNA WHERE id_vacuna = NEW.id_vacuna), ' al niño ', v_nombre, ' ', v_apellido), CURDATE());
    END IF;
END$$

-- Restauramos el delimitador
DELIMITER ;
