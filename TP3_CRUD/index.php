<?php
include 'db.php';

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$query = "SELECT * FROM registros";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⋆ Perfiles ⋆</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="./img/icons8-skyrim-16.png" type="image/x-icon">
</head>
<body>
    <h2>࿔‧ ֶָ֢˚˖ BIENVENIDO ˖˚ֶָ֢ ‧࿔</h2>
    <a href="crear.php" class="crear-nuevo">Crear Nuevo Registro</a>
    <div class="registro-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="registro-box">
                <div class="registro-header">
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                </div>
                <div class="registro-body">
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($row['descripcion']); ?></p>
                    <div class="registro-img">
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen">
                    </div>
                </div>
                <div class="registro-footer">
                    <a href="editar.php?id=<?php echo $row['id']; ?>" class="accion">Editar</a>
                    <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="accion">Eliminar</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
mysqli_close($conn); // Cierra la conexión
?>
