<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namep = $_POST['name'];
    $precio = $_POST['precio'];
    $categorias = $_POST['categoria']; // ahora es un array
    $sabor = $_POST['sabor'];

    // Subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $targetDir = "../../Images/";
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
            $imagen = $targetFile;
        } else {
            $imagen = "../../Images/default.png";
        }
    } else {
        $imagen = "../../Images/default.png";
    }

    // Insertar producto (sin categoría)
    $sql = "INSERT INTO productos (namep, precio, sabor, ruta_imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) die("Error en la preparación: " . $conn->error);

    $stmt->bind_param("siss", $namep, $precio, $sabor, $imagen);

    if ($stmt->execute()) {
        $id_producto = $stmt->insert_id;

        // Insertar todas las categorías seleccionadas
        foreach ($categorias as $categoria_nombre) {
            // Preparar consulta para obtener id_categoria
            $cat_query = $conn->prepare("SELECT id_categoria FROM categorias WHERE nombrecategoria = ?");
            if ($cat_query === false) die("Error en preparación de categoría: " . $conn->error);
            
            $cat_query->bind_param("s", $categoria_nombre);
            $cat_query->execute();
            $cat_result = $cat_query->get_result();
            $cat_row = $cat_result->fetch_assoc();

            if ($cat_row) {
                $id_categoria = $cat_row['id_categoria'];

                // Insertar relación en producto_categorias
                $relacion = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
                if ($relacion === false) die("Error en preparación de relación: " . $conn->error);
                $relacion->bind_param("ii", $id_producto, $id_categoria);
                $relacion->execute();
                $relacion->close();
            }

            $cat_query->close(); // se cierra aquí, fuera del if
        }

        header("Location: index.php");
        exit;
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
}
?>
