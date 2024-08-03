
<?php

	require_once  './controller/VotoController.php';
	require_once './model/ConexionModel.php';

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	$database = new Database();
	$db = $database->getConnection();

	$controller = new VotoController($db);
	$action = isset($_GET['action']) ? $_GET['action'] : '';

// Se llama al controlador de la votacion  para que ejecute su funcion crear()
	if ($action == 'create') {
		$controller->create();
	} elseif ($action == 'getComunas') {
		//se llama al controlador para ejecutar getcomunas el cual devuelve  las comunas de la region solicitada

		$region_id = isset($_GET['region_id']) ? $_GET['region_id'] : 0;
		$comunas = $controller->getComunas($region_id);
		// se recorren las comunas y genero las opciones del select las que se agregaran al formulario
		echo '<option value="">Seleccione una comuna</option>';
		foreach ($comunas as $comuna) {
			echo '<option value="' . $comuna['id'] . '">' . $comuna['nombre'] . '</option>';
		}
	} elseif ($action === 'checkRut') {
		header('Content-Type: application/json');

		// se llama al contralador para ejecutar la validacion de la existencia de rut en las votaciones

		$rut = $_GET['rut'] ?? '';
		$existe = $controller->rutExists($rut);

		// Devuelve una respuesta JSON
		echo json_encode(['exists' => $existe]);
	} else {
		//esta es la opcion por defecto que ejecuta la vista del formulario
		$controller->showForm();
	}
?>
