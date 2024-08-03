<?php
class VotoModel{
    private $conn;

    public $id;
    public $nombre;
    public $rut;
    public $email;
    public $region;
	public $comuna;
	public $candidato;
	public $nosotros;

    public function __construct($db) {
        $this->conn = $db;
    }

  
    /**
	 * La función `create` inserta los datos de la votación en una tabla de la base de datos y también inserta los datos relacionados en otra tabla si existe.
	 * en otra tabla, si existen, mientras que la función `rutExists` comprueba si un RUT determinado existe en
	 * la base de datos.
	 * 
	
	 * función `create` devuelve `true` si la inserción tiene éxito, y `false` si
	 * falla. 
	 * La función `rutExists` devuelve el número de filas que la consulta ha devuelto para la consulta dada
	 * dada. Si hay al menos una fila con la `rut` proporcionada, devolverá un valor mayor que
	 * 0, indicando que la "rut" ya existe en la base de datos.
	 */

	public function create($data) {
        $query = "INSERT INTO votos (nombre, alias, rut, email, id_region, id_comuna, id_candidato) 
                    VALUES (:nombre, :alias, :rut, :email, :region, :comuna, :candidato)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", 	$data['nombre']);
        $stmt->bindParam(":rut", 		$data['rut']);
        $stmt->bindParam(":email", 		$data['email']);
		$stmt->bindParam(":region",		$data['region']);
		$stmt->bindParam(":comuna",		$data['comuna']);
		$stmt->bindParam(":candidato",	$data['candidato']);
		$stmt->bindParam(":alias",		$data['alias']);

        if($stmt->execute()) {
			//  $idVoto para insertar en otra tabla
			$idVoto = $this->conn->lastInsertId();
			if (!empty($data['medio'])) {
				foreach ($data['medio'] as $medio) {
					$this->insertarOptNosotros($idVoto, $medio);
				}
			}

			return true;
		} else {
			return false;
		}
    }

	private function insertarOptNosotros($idVoto, $medio) {
        try {
            $query = "INSERT INTO votos_medio (id_voto, id_medio_nosotros ) VALUES (:id_voto, :id_medio_nosotros)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_voto', $idVoto, PDO::PARAM_INT);
            $stmt->bindParam(':id_medio_nosotros', $medio, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


	public function rutExists($rut) {
        $query = "SELECT id FROM votos WHERE rut = :rut LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rut', $rut);
        $stmt->execute();
		if($stmt->rowCount() > 0){
			return true;
		}else{
			return false;
		}
    }
}

?>
