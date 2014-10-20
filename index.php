<html>
<head>
<title>HTML Form for uploading image to server</title>
</head>
<body>
    <form action = "proceso.php" method = "post" enctype = "multipart/form-data">
            <input type="text" id="text" name="nombre" value="" />
            <label for="text">Nombre del archivo (dejar vacio si vas a reemplazar)</label>
            <br />
            <br /><input type="text" id="text" name="destino" value="" />
            <label for="text">Destino del archivo</label>
            <br />
            <br />
            <input type="radio" id="crear1" name="radioCrear" value="no" 
                   checked="checked" >
            <label for="radio1">No Crear Carpeta</label>
            <input type="radio" id="crear2" name="radioCrear" value="si">
            <label for="radio2">Si Crear Carpeta</label>
            <br />
            <br />
            <input type = "file" name = "input[]" multiple="multiple"/>  
            <br />
            <br />
            <input type="radio" id="radio1" name="radio" value="reemplazar">
            <label for="radio1">Reemplazar</label>
            <input type="radio" id="radio2" name="radio" value="renombrar">
            <label for="radio2">Renombrar</label>
            <br />
            <br />
            <input type="reset" name="reset" value="Reiniciar">
            <input type = "submit" />            
        </form>
</body>
</html>