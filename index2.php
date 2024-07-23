<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Vacunas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Niños Vacunados</h1>
    <a href="register.php" class="btn btn-primary mb-3">Registrar Nuevo Niño</a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Sexo</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM NIÑO";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['apellido']}</td>
                        <td>{$row['fecha_nac']}</td>
                        <td>{$row['sexo']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay niños registrados</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <h2 class="mt-5">Notificaciones</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Mensaje</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM NOTIFICACIONES";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['mensaje']}</td>
                        <td>{$row['fecha']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No hay notificaciones</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
