<?php
$ruta1_CategoriaDAO = '../dao/CategoriaDAO.php';
$ruta2_CategoriaDAO = 'dao/CategoriaDAO.php';

if (file_exists($ruta1_CategoriaDAO)) {
    require_once $ruta1_CategoriaDAO;
}

if (file_exists($ruta2_CategoriaDAO)) {
    require_once $ruta2_CategoriaDAO;
}

if (isset($_GET['paramCategorias'])) {
    $paramCategorias = $_GET['paramCategorias'];
}
if (isset($_POST['paramCategorias'])) {
    $paramCategorias = $_POST['paramCategorias'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$daoCategorias = new DaoCategorias("funkoplanet");
$daoCategorias->listar();

switch ($paramCategorias) {
    case 'categoriesMenu':
        //header('Content-Type: application/json');
        echo json_encode($daoCategorias->categorias, JSON_UNESCAPED_UNICODE);
        break;
    case 'categoriesCentral':
        require_once 'views/categories_view.php';
        break;
    case 'categoryClicked':
        header("Location: /funkoplanet/views/products.php?category=$id");
        exit();
        break;
    default:
        # code...
        break;
}
