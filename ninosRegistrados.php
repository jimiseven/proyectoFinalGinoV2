<?php
include 'config.php';

// Obtener listado de niños
$sql = "SELECT nombre, apellido, TIMESTAMPDIFF(MONTH, fecha_nac, CURDATE()) AS edad FROM NIÑO";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Niños</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .btn-register {
            display: block;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container mt-5">
        <h2 class="mb-4">Listado de niños</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre niño</th>
                        <th>Edad (meses)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['nombre']} {$row['apellido']}</td>
                                    <td>{$row['edad']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No hay niños registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="register.php" class="btn btn-primary btn-register">Registrar</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
