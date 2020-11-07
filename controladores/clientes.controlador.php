<?php 

class ControladorClientes{

	/*=============================================
	Crear un registro
	=============================================*/

	public function create($datos){

		/*=============================================
		Validar nombre
		=============================================*/

		if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["nombre"])){

			$json = array(

				"status"=>404,
				"detalle"=>"Error en el campo nombre, sólo se permiten letras"

			);

			echo json_encode($json, true);

			return;
		}

		/*=============================================
		Validar apellido
		=============================================*/

		if(isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["apellido"])){

			$json = array(

				"status"=>404,
				"detalle"=>"Error en el campo apellido, sólo se permiten letras"

			);

			echo json_encode($json, true);

			return;
		}

		/*=============================================
		Validar email
		=============================================*/

		if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["email"])){

			$json = array(

				"status"=>404,
				"detalle"=>"Error en el campo email, coloca un email válido"

			);

			echo json_encode($json, true);

			return;
		}

		/*=============================================
		Validar que el email no esté repetido
		=============================================*/

		$clientes  = ModeloClientes::index("clientes");
		
		foreach ($clientes as $key => $value) {
			
			if($value["email"] == $datos["email"]){

				$json = array(

					"status"=>404,
					"detalle"=>"El email ya existe en la base de datos"

				);

				echo json_encode($json, true);

				return;

			}
		}

		/*=============================================
		Generar credenciales del cliente
		=============================================*/

		$id_cliente = str_replace("$", "a", crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
	
		$llave_secreta = str_replace("$", "o", crypt($datos["email"].$datos["apellido"].$datos["nombre"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));


		/*=============================================
		Llevar datos al modelo
		=============================================*/

		$datos = array("nombre"=>$datos["nombre"],
						"apellido"=>$datos["apellido"],
						"email"=>$datos["email"],
						"id_cliente"=>$id_cliente,
						"llave_secreta"=>$llave_secreta,
						"created_at"=>date('Y-m-d h:i:s'),
						"updated_at"=>date('Y-m-d h:i:s')
						);

		$create = ModeloClientes::create("clientes", $datos);

		/*=============================================
		Respuesta del modelo
		=============================================*/

		if($create == "ok"){

			$json = array(

					"status"=>200,
					"detalle"=>"Registro exitoso, tome sus credenciales y guárdelas",
					"credenciales"=>array("id_cliente"=>$id_cliente, "llave_secreta"=>$llave_secreta)

				);

				echo json_encode($json, true);

				return;

		}

	}

}