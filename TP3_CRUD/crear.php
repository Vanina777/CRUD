<?php
include 'db.php';

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Verificar si se cargó una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaImagen = 'imagenes/' . $nombreImagen;

        // Mover la imagen a la carpeta 'imagenes'
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            $stmt = $conn->prepare("INSERT INTO registros (titulo, descripcion, imagen) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die("Error en la preparación: " . $conn->error);
            }

            $stmt->bind_param("sss", $titulo, $descripcion, $rutaImagen);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al cargar la imagen.";
        }
    } else {
        echo "Por favor selecciona una imagen.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Crear Nuevo Registro</h1>
    <form method="POST" action="crear.php" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label for="imagen">Selecciona una Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="submit">Crear Registro</button>
    </form>
    <a href="index.php">Volver a la lista de registros</a>
</body>
</html>

