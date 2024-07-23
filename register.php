<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nac = $_POST['fecha_nac'];
    $sexo = $_POST['sexo'];
    $vacunas = $_POST['vacunas'];
    $fecha_aplicacion = $_POST['fecha_aplicacion'];

    $sql = "INSERT INTO NIÑO (nombre, apellido, fecha_nac, sexo) VALUES ('$nombre', '$apellido', '$fecha_nac', '$sexo')";
    if ($conn->query($sql) === TRUE) {
        $id_niño = $conn->insert_id;
        foreach ($vacunas as $id_vacuna) {
            $sql_vacuna = "INSERT INTO REGISTRO_VACUNAS (id_niño, id_vacuna, fecha_aplicacion, dosis, aplicada) VALUES ('$id_niño', '$id_vacuna', '$fecha_aplicacion', 1, TRUE)";
            $conn->query($sql_vacuna);
        }
        echo "<div class='alert alert-success'>Niño registrado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Niño</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Registrar Nuevo Niño</h1>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="form-group">
            <label for="fecha_nac">Fecha de Nacimiento:</label>
            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
        </div>
        <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select class="form-control" id="sexo" name="sexo" required>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
        <div class="form-group">
            <label for="vacunas">Vacunas:</label>
            <select multiple class="form-control" id="vacunas" name="vacunas[]" required>
                <?php
                $sql = "SELECT * FROM VACUNA";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id_vacuna']}'>{$row['nombre_vacuna']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_aplicacion">Fecha de Aplicación:</label>
            <input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="index.php" class="btn btn-secondary">Volver a la página principal</a>
    </form>
</div>

<script src="js/bootstrap.min.js"></script>
</body>
</html>
