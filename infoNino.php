<?php
include 'config.php';

// Obtener el ID del niño (esto puede venir de un parámetro GET o POST, aquí se usa un valor fijo para el ejemplo)
$id_niño = 1; // Ejemplo fijo, ajusta según tu necesidad

// // Consultar información del niño
// $sql_niño = "SELECT n.nombre AS nombre_niño, n.fecha_nac, r.nombre AS nombre_responsable, r.apellido AS apellido_responsable, n.sexo 
//             FROM NIÑO n 
//             JOIN RESPONSABLE r ON n.id_responsable = r.id_responsable 
//             WHERE n.id_niño = $id_niño";
// $result_niño = $conn->query($sql_niño);
// $niño = $result_niño->fetch_assoc();

// Calcular la edad del niño en meses
$fecha_nac = new DateTime($niño['fecha_nac']);
$hoy = new DateTime();
$edad = $hoy->diff($fecha_nac)->m + ($hoy->diff($fecha_nac)->y * 12);

// Consultar vacunas recibidas
$sql_vacunas = "SELECT v.nombre_vacuna, rv.fecha_aplicacion, rv.dosis, rv.aplicada 
               FROM REGISTRO_VACUNAS rv 
               JOIN VACUNA v ON rv.id_vacuna = v.id_vacuna 
               WHERE rv.id_niño = $id_niño";
$result_vacunas = $conn->query($sql_vacunas);

// Consultar próximas vacunas
$sql_proximas = "SELECT v.nombre_vacuna, rv.fecha_aplicacion 
                FROM REGISTRO_VACUNAS rv 
                JOIN VACUNA v ON rv.id_vacuna = v.id_vacuna 
                WHERE rv.id_niño = $id_niño AND rv.aplicada = FALSE";
$result_proximas = $conn->query($sql_proximas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Vacunas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .vacuna-list {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }
        .vacuna-list span {
            margin-left: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container mt-5">
        <h2 class="mb-4">Registro de Vacunas</h2>
        <p><strong>Nombre niño(a):</strong> <?php echo $niño['nombre_niño']; ?></p>
        <p><strong>Nombre responsable:</strong> <?php echo $niño['nombre_responsable'] . ' ' . $niño['apellido_responsable']; ?></p>
        <p><strong>Fecha de nacimiento:</strong> <?php echo date_format($fecha_nac, 'd F Y'); ?></p>
        <p><strong>Edad:</strong> <?php echo $edad . ' meses'; ?></p>
        
        <h3>Vacunas Recibidas</h3>
        <div>
            <?php
            if ($result_vacunas->num_rows > 0) {
                while($vacuna = $result_vacunas->fetch_assoc()) {
                    echo "<div class='vacuna-list'>
                            <span>{$vacuna['nombre_vacuna']}</span>
                            <span>{$vacuna['dosis']}</span>
                            <span>" . ($vacuna['aplicada'] ? '✔' : '✘') . "</span>
                        </div>";
                }
            } else {
                echo "<p>No hay vacunas registradas</p>";
            }
            ?>
        </div>
        
        <h3>Siguientes Vacunas</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Vacuna</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($result_proximas->num_rows > 0) {
                    while($proxima = $result_proximas->fetch_assoc()) {
                        echo "<tr>
                                <td>{$proxima['nombre_vacuna']}</td>
                                <td>{$proxima['fecha_aplicacion']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No hay próximas vacunas registradas</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        
        <button type="button" class="btn btn-primary btn-block mt-4">Continuar</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
