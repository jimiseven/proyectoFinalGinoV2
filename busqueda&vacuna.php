<?php
include 'config.php';

$niño_encontrado = false;
$niño = null;

// Buscar niño
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM NIÑO WHERE nombre LIKE '%$query%' OR apellido LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $niño = $result->fetch_assoc();
        $niño_encontrado = true;
    }
}

// Registrar vacuna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_niño'])) {
    $id_niño = $_POST['id_niño'];
    $id_vacuna = $_POST['id_vacuna'];
    $fecha_aplicacion = $_POST['fecha_aplicacion'];

    $sql = "INSERT INTO REGISTRO_VACUNAS (id_niño, id_vacuna, fecha_aplicacion, dosis, aplicada) VALUES ('$id_niño', '$id_vacuna', '$fecha_aplicacion', 1, TRUE)";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Vacuna registrada exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar y Registrar Vacuna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 600px;
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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Navbar</a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="#">Niños Registrados <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Búsqueda de Niños</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="buscar.php" method="get">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar niño" aria-label="Buscar" name="query">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="form-container mt-5">
        <h2 class="mb-4">Buscar Niño</h2>
        <form action="buscar.php" method="get">
            <div class="form-group">
                <label for="query">Nombre o Apellido del Niño</label>
                <input type="text" class="form-control" id="query" name="query" placeholder="Ingrese nombre o apellido" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Buscar</button>
        </form>

        <?php if ($niño_encontrado): ?>
            <h3 class="mt-4">Información del Niño</h3>
            <p><strong>Nombre:</strong> <?php echo $niño['nombre']; ?></p>
            <p><strong>Apellido:</strong> <?php echo $niño['apellido']; ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo $niño['fecha_nac']; ?></p>
            <p><strong>Sexo:</strong> <?php echo $niño['sexo']; ?></p>

            <h4 class="mt-4">Registrar Vacuna</h4>
            <form action="buscar.php" method="post">
                <input type="hidden" name="id_niño" value="<?php echo $niño['id_niño']; ?>">
                <div class="form-group">
                    <label for="id_vacuna">Vacuna</label>
                    <select class="form-control" id="id_vacuna" name="id_vacuna" required>
                        <?php
                        $sql_vacunas = "SELECT id_vacuna, nombre_vacuna FROM VACUNA";
                        $result_vacunas = $conn->query($sql_vacunas);
                        if ($result_vacunas->num_rows > 0) {
                            while($row_vacuna = $result_vacunas->fetch_assoc()) {
                                echo "<option value='{$row_vacuna['id_vacuna']}'>{$row_vacuna['nombre_vacuna']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_aplicacion">Fecha de Aplicación</label>
                    <input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Registrar Vacuna</button>
            </form>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])): ?>
            <div class="alert alert-danger mt-4">No se encontró ningún niño con ese nombre o apellido.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
