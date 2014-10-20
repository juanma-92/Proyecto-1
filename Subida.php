<?php

class Subida {

    private $input;
    private $files;
    private $destino;
    private $nombre;

    const RENOMBRAR = 1, REEMPLAZAR = 2;
    const ERROR_INPUT = -1;

    private $accion;
    private $maximo;
    private $extension;
    private $tipo;
    private $error_php;
    private $error;
    private $crearCarpeta;

    function __construct($param) {
        $this->input = $param;
        $this->destino = "./";
        $this->nombre = "";
        $this->accion = Subida::REEMPLAZAR;
        $this->maximo = 2 * 1024 * 1024;
        $this->tipo = array();
        $this->extension = array();
        $this->error_php = UPLOAD_ERR_OK;
        $this->error = 0;
        $this->files = $_FILES[$param];
        $this->crearCarpeta = FALSE;
    }

    /**
     * Devuelve el codigo del error de subida del archivo
     * @access public
     */
    public function getErrorPHP() {
        return $this->errorPHP;
    }

    public function getError() {
        return $this->error;
    }

    public function getErrorMensaje() {
        //Usar switch
        if ($this->error == -1) {
            return "Error";
        }
    }

    public function setCrearCarpeta($crearCarpeta) {
        $this->crearCarpeta = $crearCarpeta;
    }

    /**
     * Establece la ruta relativa donde subir el archivo.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setDestino($param) {
        $caracter = substr($param, -1);
        if ($caracter != "/") {
            $param.="/";
        }
        $this->destino = $param;
    }

    public function getDestino() {
        return $this->destino;
    }

    /**
     * Establece el nombre sin extension con que se guarda el archivo.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setNombre($param) {
        $this->nombre = $param;
    }

    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Establece la politica de guardado, sobreescribe o
     * ignora si el archivo ya existe.
     * @access public
     * @param string $param Cadena con el nombre del parámetro
     */
    public function setAccion($param) {
        if ($param == self::RENOMBRAR || $param == self::REEMPLAZAR) {
            $this->accion = $param;
        } else {
            $this->accion = self::REEMPLAZAR;
        }
    }

    /**
     * Establece el tamaño máximo del archivo a subir.
     * @access public
     * @param integer $param Entero
     */
    public function setMaximo($maximo) {
        $this->maximo = $maximo;
    }

    /**
     * Añade una extensión que vamos a permitir subir.
     * @access public
     * @param string|array $param Cadena con el nombre del parámetro
     */
    public function addExtension($param) {
        if (is_array($param)) {
            $this->extension = array_merge($this->extension, $param);
        } else {
            $this->extension[] = $param;
        }
    }

    /**
     * Añade el tipo MIME que vamos a permitir subir.
     * @access public
     * @param string|array $param Cadena con el nombre del tipo MIME
     */
    public function addTipo($param) {
        if (is_array($param)) {
            $this->tipo = array_merge($this->tipo, $param);
        } else {
            $this->tipo[] = $param;
        }
    }

    public function getTipo() {
        foreach ($this->tipo as $value) {
            echo $value . "<br/>";
        }
    }

    /**
     * Devuelve el mensaje de subida del archivo
     * @access public
     * @return 
     */
    public function getMensajeError() {
        return $this->error_php;
    }

    private function isInput() {
        if (!isset($_FILES[$this->input])) {
            $this->error_php = "NO existe el campo";
            return false;
        }
        return true;
    }

    private function isError() {
        if ($this->files["error"] != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }

    private function isTamano() {
        if ($this->files["size"] > $this->maximo) {
            $this->error_php = "sobre pasa tamaño";
            return false;
        }
        return true;
    }

    private function isExtension($param) {
        if (sizeof($this->extension) > 0 &&
                !in_array($param, $this->extension)) {
            $this->error_php = "extension no valida";
            return false;
        }
        return true;
    }

    private function isTipo($param) {
        if (sizeof($this->tipo) > 0 &&
                !in_array($param, $this->tipo)) {
            $this->error_php = "tipo MIME no valido";
            return false;
        }
        return true;
    }

    private function isCarpeta() {
        if (!file_exists($this->destino) && !is_dir($this->destino)) {
            //$this->mensaje = "Carpeta no valida";
            $this->error_php = -4;
            return false;
        }
        return true;
    }

    private function crearCarpeta() {
        return mkdir($this->destino, 0777, true);
    }

    public function subida() {
        foreach ($_FILES[$this->input]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                echo"$error_codes[$error]";
                $this->error = 0;
                if (!$this->isInput()) {
                    return false;
                }
                
                $this->files = $_FILES[$this->input];
                $this->errorPHP = $this->files["error"];
                
                if (!$this->isCarpeta()) {
                    if ($this->crearCarpeta) {
                        $this->error_php = 0;
                        if (!$this->crearCarpeta()) {
                            $this->error_php = -7;
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
                $partes = pathinfo($this->files["name"][$key]);
                $extension = $partes['extension'];
                $nombreOriginal = $partes['filename'];
                if (!$this->isExtension($extension)) {
                    return false;
                }
                if ($this->nombre === "") {
                    $this->nombre = $nombreOriginal;
                    echo $nombreOriginal;
                }
                $origen = $this->files["tmp_name"][$key];
                $destino = $this->destino . $this->nombre . "." . $extension;

                if ($this->accion == Subida::RENOMBRAR) {
                    $i = 1;
                    while (file_exists($destino)) {
                        $destino = $destino = $this->destino .
                                $this->nombre . "_$i." . $extension;
                        $i++;
                    }
                    move_uploaded_file($origen, $destino);
                    $this->nombre = "";
                }
                if ($this->accion == Subida::REEMPLAZAR) {
                    $destino = $destino = $this->destino .
                            $this->nombre . "." . $extension;
                    move_uploaded_file($origen, $destino);
                    $this->nombre = "";
                }
            }
        }
    }

}
