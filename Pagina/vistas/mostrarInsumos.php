<!Doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Compras
    </title>
    <link rel="stylesheet" href="../assets/css/main.css"
</head>

<body>
    <?php
        include_once('../assets/layout/menu.php');
    ?> 
    
    
    <main>   
        <?php
            $response = json_decode(file_get_contents('http://localhost/Pagina/modelos/api-producto.php?categoria=Insumo'), true);
            if($response['statuscode'] == 200){
                foreach($response['items'] as $item){
                    include('../assets/layout/items.php');  
                }
            }else{
                //error
            }
        ?>
    
    </main>

    <script src="../assets/js/main.js"></script>
</body>

</html>
