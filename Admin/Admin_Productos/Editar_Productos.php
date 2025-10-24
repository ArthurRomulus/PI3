<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $precio = $_POST['precio'];
    $categorias = $_POST['categoria']; // ahora es un array
    $sabor = $_POST['sabor'];

    // Manejar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $targetDir = "../img/";
        $targetFile = $targetDir . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile);
        $imagen = $targetFile;

        $sql = "UPDATE productos SET namep=?, precio=?, sabor=?, ruta_imagen=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissi", $name, $precio, $sabor, $imagen, $id);
    } else {
        $sql = "UPDATE productos SET namep=?, precio=?, sabor=? WHERE idp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $name, $precio, $sabor, $id);
    }

    $stmt->execute();
    $stmt->close();

    // ---- Actualizar categorías ----
    // Primero eliminamos las categorías viejas
    $conn->query("DELETE FROM producto_categorias WHERE idp = $id");

    // Luego insertamos las nuevas
    $stmtCat = $conn->prepare("INSERT INTO producto_categorias (idp, id_categoria) VALUES (?, ?)");
    foreach ($categorias as $catNombre) {
        // Obtener el id_categoria según el nombre
        $res = $conn->query("SELECT id_categoria FROM categorias WHERE nombrecategoria = '".$conn->real_escape_string($catNombre)."' LIMIT 1");
        if($res && $res->num_rows > 0){
            $row = $res->fetch_assoc();
            $id_categoria = $row['id_categoria'];
            $stmtCat->bind_param("ii", $id, $id_categoria);
            $stmtCat->execute();
        }
    }
    $stmtCat->close();

    header("Location: index.php");
    exit;
}
?>
