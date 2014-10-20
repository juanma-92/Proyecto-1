<!DOCTYPE html>
<?php

//incluimos clases
require_once './clases/Leer.php';
require_once 'Subida.php';

//creamos un objeto de la clase subida pasandole un String
$subida = new Subida("input");

//leemos el nombre
$nombre = Leer::post("nombre");

//enviamos el nombre a la clase subida si no esta vacio
if ($nombre !== NULL && $nombre !== "") {
    $subida->setNombre($nombre);
}

//enviamos el destino a la clase subida si no esta vacio
$destino = Leer::post("destino");
if ($destino !== NULL && $destino !== "") {
    $subida->setDestino($destino);
}

//enviamos si queremos crear una carpeta
$carpeta = Leer::post("radioCrear");
if ($carpeta === "si") {
    $subida->setCrearCarpeta("TRUE");
}

//leemos si queremos reemplazar o renombrar
$politica = Leer::post("radio");

//filtramos que no este vacia la politica
if ($politica == null) {
    echo "Debes seleccionar una opcion <br/> Reemplazar o renombrar";
} else {
    //enviamos si queremos reemplazar o renombrar
    if ($politica === "renombrar") {
        $subida->setAccion("1");
    } else if ($politica === "reemplazar") {
        $subida->setAccion("2");
    }

    //añadimos unas cuantas extensiones y tipos manualmente
    
    $subida->addExtension("txt");
    $subida->addExtension("php");
    $subida->addTipo("text/plain");
    $subida->addTipo("text/html");
    $subida->addTipo("application/acad");
    echo($subida->getMensajeError());
    $subida->subida();
}
?>
