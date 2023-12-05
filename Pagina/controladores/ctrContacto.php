<?php
    require_once '../modelos/mensajes_model.php';
    require_once '../modelos/conexion.php';
    include_once '../assets/adodb5/adodb.inc.php';

    if( isset($_GET['opc']) ){
        $msjModel = new MensajesModel();

        switch($_GET['opc']){
            case 1:
                // INSERT TO DB
                $nom = $_POST['txtNombre'];
                if(!empty($_POST['hddId']) )
                    $msjModel->updateMensaje($nom);
                else
                    $msjModel->insertMensaje($nom);
                echo getMensajes($msjModel);
                break;
            case 2: // UPDATE TO BD
                $msjModel->updateMensaje();
                break;
            case 3: // DELETE TO DB
                $idMsj = $_POST['idMsj'];
                $msjModel->deleteMensaje($idMsj);
                echo '<h5 class="miclasecss"><strong>Respuesta a la peticion AJAX</strong></h5>';
                break;
            case 4: // SELECT TO DB
                echo getMensajes($msjModel);
        }
    }else{
        header('Location: ../index.html');
    }

    function getMensajes($msjModel) {
        $response = '';
        $mensajes = $msjModel->getAllMensajes();
        while(!$mensajes->EOF){
            $response .= '<tr>
                <th scope="row">1</th>
                <td>'.$mensajes->fields[1].'</td>
                <td>'.$mensajes->fields[2].'</td>
                <td>'.$mensajes->fields[3].'</td>
                <td>'.$mensajes->fields[4].'</td>
                <td>'.$mensajes->fields[5].'</td>
                <td><a href="#" class="btn btn-success" onclick="editar('.$mensajes->fields[0].',\''.$mensajes->fields[1].'\')">Editar</a></td>
                <td><input type="button" class="btn btn-danger" value="Eliminar" onclick="eliminar('.$mensajes->fields[0].')"></td>
            </tr>';
            $mensajes->moveNext();
        }
        return $response;
    }
?> 

