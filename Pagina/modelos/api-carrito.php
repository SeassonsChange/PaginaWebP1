<?php

include_once 'carrito.php';
include_once './conexiones/PDOdb.php';

// header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['action'])) {
    $accion = $_GET['action'];
    $carrito = new Carrito();

    switch ($accion) {
        case 'mostrar':
            mostrar($carrito);
            break;

        case 'add':
            add($carrito);
            break;

        case 'remove':
            remove($carrito);
            break;

        case 'terminar':
            terminar($carrito);
            break;

        default:
    }
} else {
    echo json_encode([
        'statuscode' => 404,
        'response' => 'No se puede procesar la solicitud'
    ]);
}

function mostrar($carrito)
{
    //cargar arreglo en la sesion
    //consultar DB para actualizar valores de productos
    $itemsCarrito = json_decode($carrito->load(), 1);
    $fullItems = [];
    $total = 0;
    $totalItems = 0;

    foreach ($itemsCarrito as $itemCarrito) {
        $httpRequest = file_get_contents('http://localhost/Pagina/modelos/api-producto.php?get-item=' . $itemCarrito['id']);
        $itemProducto = json_decode($httpRequest, 1)['item'];
        $itemProducto['cantidad'] = $itemCarrito['cantidad'];
        $itemProducto['subtotal'] = $itemProducto['cantidad'] * $itemProducto['Precio'];

        $total += $itemProducto['subtotal'];    
        $totalItems += $itemProducto['cantidad'];

        array_push($fullItems, $itemProducto);
    }
    $resArray = array('info' => ['count' => $totalItems, 'total' => $total], 'items' => $fullItems);

    echo json_encode($resArray);
}

function add($carrito)
{
    if (isset($_GET['id'])) {
        $res = $carrito->add($_GET['id']);
        echo $res;
    } else {
        //error
        echo json_encode([
            'statuscode' => 404,
            'response' => 'No se puede procesar la solicitud id vacio.'
        ]);
    }
}

function remove($carrito)
{
    if (isset($_GET['id'])) {
        $res = $carrito->remove($_GET['id']);
        if ($res) {
            echo json_encode(['statuscode' => 200]);
        } else {
            echo json_encode(['statuscode' => 400]);
        }
    } else {
        echo json_encode([
            'statuscode' => 404,
            'response' => 'No se puede procesar la solicitud, id vacio'
        ]);
    }
}

function terminar($carrito)
{

    $data = json_decode($carrito->load(), true);

    $connect = new mysqli('localhost', 'webmaster', '1234', 'prograweb');
    if ($connect->connect_error) {
        echo json_encode(array('status' => 'error', 'message' => 'Error en la conexión a la base de datos: ' . $connect->connect_error));
        die();
    }

    //if(isset($data['id'], $data['cantidad'])){
        foreach ($data as $item) {
            echo json_encode($data);
            $idProducto = $item['id'];
            $cantidad = $item['cantidad']; 

            $query = $connect->prepare("UPDATE tblcarro set UnitStock = UnitStock - $cantidad, UnitComprome = UnitComprome + $cantidad WHERE idCarro = $idProducto");
            $query->execute();
            //$query->bind_param("iii", $cantidad, $cantidad, $idProducto);

            // if ($connect->query($query) !== TRUE) {
            //     echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar la base de datos.' . $query->error));
            //     echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar la base de datos.(nivel connect)', 'error' => $connect->error));
            //     die();
            // }
        }

        //$carrito->clear();
        
        echo json_encode(json_decode($carrito->load(),1));
        $connect->close();

        $idProducto = [];
        $cantidad = [];

//           echo json_encode(array('status' => 'success', 'message' => 'Compra Finalizada con exito.'));
//       } else {
//          echo json_encode(array('status' => 'error', 'message' => 'El array de items es nulo o no es un array.'));
//          die();
//   }
}
