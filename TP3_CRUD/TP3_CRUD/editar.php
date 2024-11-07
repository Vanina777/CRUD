<?php
// Conexión a la base de datos
include 'db.php';

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario para actualizar el registro
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Subir la imagen si se ha seleccionado una
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaImagen = 'imagenes/' . $nombreImagen;

        // Mover la imagen a la carpeta 'imagenes'
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            // Si la imagen se carga correctamente, actualizamos con la nueva imagen
            $sql = "UPDATE registros SET titulo='$titulo', descripcion='$descripcion', imagen='$rutaImagen' WHERE id=$id";
        } else {
            echo "Error al cargar la imagen.";
        }
    } else {
        // Si no se seleccionó imagen, mantener la imagen existente
        $rutaImagen = $_POST['existing_image'];
        $sql = "UPDATE registros SET titulo='$titulo', descripcion='$descripcion', imagen='$rutaImagen' WHERE id=$id";
    }

    // Ejecutar la consulta de actualización
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar el registro: " . mysqli_error($conn);
    }
}

// Obtener los datos del registro para editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM registros WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $registro = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Registro</h1>
    <form method="POST" action="editar.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($registro['titulo']); ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required><?php echo htmlspecialchars($registro['descripcion']); ?></textarea>

        <label for="imagen">Selecciona una Imagen:</label>
        <input type="file" name="imagen" accept="image/*">
        
        <!-- Mostrar imagen existente si la hay -->
        <?php if ($registro['imagen']): ?>
            <div class="image-preview">
                <p>Imagen actual:</p>
                <img src="<?php echo htmlspecialchars($registro['imagen']); ?>" alt="Imagen actual" width="200">
            </div>
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($registro['imagen']); ?>">
        <?php endif; ?>

        <button type="submit" name="update">Actualizar Registro</button>
    </form>
    <a href="index.php">Volver a la lista de registros</a>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conn);
?>
