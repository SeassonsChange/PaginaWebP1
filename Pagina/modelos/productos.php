<?php
include_once (__DIR__ . '\conexiones\PDOdb.php');

class Productos extends PDOdb {
 
    function __construct() {
        parent::__construct();
    }

    public function get( $idCarro ) {
        $query = $this->connect()->prepare( 'SELECT * FROM tblcarro where idCarro = :idCarro' );
        $query->execute( ['idCarro' => $idCarro] );

        $row = $query->fetch();

        return[
            'idCarro'      => $row['idCarro'],
            'Precio'       => $row['Precio'],
            'Nombre'       => $row['Nombre'],
            'UnitStock'    => $row['UnitStock'],
            'PunOrden'     => $row['PunOrden'],
            'UnitComprome' => $row['UnitComprome'],
            'Costo'        => $row['Costo'],
            'Url'          => $row['Url'],
            'Categoria'    => $row['Categoria'],

        ];
    }

    public function getItemsByCategory( $category ) {
        $query = $this->connect()->prepare( 'SELECT * FROM tblcarro where Categoria = :cat' );
        $query->execute( ['cat' => $category] );
        
        $items = [];
        
        while( $row = $query->fetch( PDO::FETCH_ASSOC ) ) {

            

            $item = [
                'idCarro'      => $row['idCarro'],
                'Precio'       => $row['Precio'],
                'Nombre'       => $row['Nombre'],
                'UnitStock'    => $row['UnitStock'],
                'PunOrden'     => $row['PunOrden'],
                'UnitComprome' => $row['UnitComprome'],
                'Costo'        => $row['Costo'],
                'Url'          => $row['Url'],
                'Categoria'    => $row['Categoria'],

            ];

            array_push( $items, $item );
        }
        
        return $items;
    }

}

?>
