<?php
include_once('header.html');

// Configurar el .ini 
$config = parse_ini_file('configBD.ini');

$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT id, 
                numero_identificador, 
                imagen, 
                nombre, 
                tipo, 
                descripcion  FROM pokemon";
$result = mysqli_query($conn, $query);



// En caso que la barra de busqueda tenga informacion 
$search = isset($_GET['search']) ? $_GET['search'] : '';
$querySelect = "SELECT * FROM pokemon";
$query = "";

if (!empty($search)) {
    $query = $querySelect . " WHERE nombre LIKE '%$search%' OR tipo LIKE '%$search%' OR numero_identificador LIKE '%$search%'";
}else{
    $query = $querySelect;
}

$result = mysqli_query($conn, $query);

$noexiste = mysqli_num_rows($result) == 0;

if ($noexiste){
    $result = mysqli_query($conn, $querySelect);
}


if (!empty($search) && $noexiste){
    echo 'Pokemon no encontrado';
}

echo '<div class="container-fluid">';
echo '<table class="table table-hover">';
echo '<thead><tr><th scope="col">Identificador</th><th scope="col">Imagen</th><th scope="col">Nombre</th><th scope="col">Tipo</th><th scope="col">Descripcion</th></tr></thead>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tbody>';
    echo '<tr onclick="window.location.href=\'informacion_pokemon.php?id='.$row['id'].'\'">';
  
    echo '<td>' . $row['numero_identificador'] . '</td>';
    echo '<td>' . '<img src="img_pokemon/' . $row['imagen'] . '.png" alt="' . $row['imagen'] . '" width="100" height="90">' . '</td>';
    echo '<td>' . $row['nombre'] . '</td>';
    echo '<td>' . '<img src="img_tipo/' . $row['tipo'] . '.png" alt="' . $row['tipo'] . '" width="80" height="20">' . '</td>';
    echo '<td>' . $row['descripcion'] . '</td>';
    echo '</tr>';
}
echo '</tbody></table>';
echo '</div>';



// No olvides cerrar la conexión cuando termines
mysqli_close($conn);