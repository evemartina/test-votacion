
<?php
session_start();
include './model/ConexionModel.php';
require_once 'model/VotoModel.php';
require_once 'model/RegionModel.php';
require_once 'model/ComunaModel.php';
require_once 'model/MediosModel.php';
require_once 'model/CandidatoModel.php';
require_once './crsf.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class VotoController {
    private $db;
    private $votoModel;
	private $regionModel;
	private $comunaModel;
	private $medioModel;
	private $candidatoModel;

    public function __construct($db) {
        $this->regionModel 		= new RegionModel($db);
        $this->comunaModel 		= new ComunaModel($db);
		$this->medioModel       = new MedioModel($db);
		$this->candidatoModel   = new CandidatoModel($db);
		$this->votoModel        = new VotoModel($db);
    }

	public function showForm(){
        $regiones 		= $this->regionModel->getAllRegions();
		$medios         = $this->medioModel->getAllMedios();
		$candidatos     = $this->candidatoModel->getAllCandidatos();
        require_once  'view/formulario.php';
   }

    public function create() {
		/*esta funcion recive el post con los datos desde el formulario los limpia y prepara  antes de
		enviarlos al modelo para ser insertados y retorno  el mensaje de acuerdo a el resultado de la insercion
		y seredireciona al formulario nuevamente con el mensaje en la sesion para imprimirlo
		*/
		print_r($_POST);
        if (!validateCsrfToken($_POST['csrf_token'])) {
			die("Invalid CSRF token");
        }
		$data = [];
		$data['nombre']    = htmlspecialchars(trim($_POST['nombre']));
		$data['rut']       = htmlspecialchars(trim($_POST['rut']));
		$data['email']     = htmlspecialchars(trim($_POST['email']));
		$data['region']    = htmlspecialchars(trim($_POST['region']));
		$data['comuna']    = htmlspecialchars(trim($_POST['comuna']));
		$data['candidato'] = htmlspecialchars(trim($_POST['candidato']));
		$data['alias'] 	   = htmlspecialchars(trim($_POST['alias']));
		$data['medio']     = $_POST['medio'];

        if ($this->votoModel->create($data)) {
			$_SESSION['message'] = [
				'type' => 'success',
				'text' => 'Votación registrada exitosamente'
			];
		} else {
			$_SESSION['message'] = [
				'type' => 'error',
				'text' => 'Error al registrar el voto'
			];
		}
		// Redirecciona a la vista del formulario
		header('Location: /votacion-test/');
		exit();
    }


  public function getComunas($idRegion){
	return $this->comunaModel->getComunasByRegion($idRegion);
  }

  public function rutExists($rut){
	return $this->votoModel->rutExists($rut);
  }
}
?>
