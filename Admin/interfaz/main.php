<?php
$TextoXDesplegar = trim("Bienvenid@ " . trim($NombreUsuario));
$TextoXDesplegar = htmlspecialchars($TextoXDesplegar);

// A continuación sigue el código fuente de la interfaz
?>
<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "Main"; // Este es un parámetro que recibe "menuApp.php"
include "menuApp.php";
?>
<div class="container mt-4">
<blockquote class="blockquote text-center">
  <h1 class="display-4"><?php echo $TextoXDesplegar;?></h1>
</blockquote>
</div>
</body>
</html>
