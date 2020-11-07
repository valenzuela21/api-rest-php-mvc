<?php 

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if(isset($_GET["page"]) && is_numeric($_GET["page"])){	

	$cursos = new ControladorCursos();
	$cursos -> index($_GET["page"]);	

}else{

	if(count(array_filter($arrayRutas)) == 0){
		
		/*=============================================
		Cuando no se hace ninguna petición a la API
		=============================================*/
				
		$json = array(

		"detalle"=>"no encontrado"

		);

		echo json_encode($json, true);

		return;

	}else{

		/*=============================================
		Cuando pasamos solo un índice en el array $arrayRutas
		=============================================*/

		if(count(array_filter($arrayRutas)) == 1){	

			/*=============================================
			Cuando se hace peticiones desde registro
			=============================================*/

			if(array_filter($arrayRutas)[1] == "registro"){

				/*=============================================
				Peticiones POST
				=============================================*/

				if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){

					/*=============================================
					Capturar datos
					=============================================*/

					$datos = array( "nombre"=>$_POST["nombre"],
									"apellido"=>$_POST["apellido"],
									"email"=>$_POST["email"]);


					$registro = new ControladorClientes();
					$registro -> create($datos);	

				}else{

					$json = array(

						"detalle"=>"no encontrado"

					);

					echo json_encode($json, true);

					return;

				}

			}

			/*=============================================
			Cuando se hace peticiones desde cursos
			=============================================*/

			else if(array_filter($arrayRutas)[1] == "cursos"){

				/*=============================================
				Peticiones GET
				=============================================*/

				if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){

					$cursos = new ControladorCursos();
					$cursos -> index(null);	

				}

				/*===========================.==================
				Peticiones POST
				=============================================*/

				else if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){

					/*=============================================
					Capturar datos
					=============================================*/

					$datos = array( "titulo"=>$_POST["titulo"],
									"descripcion"=>$_POST["descripcion"],
									"instructor"=>$_POST["instructor"],
									"imagen"=>$_POST["imagen"],
									"precio"=>$_POST["precio"]);

					$crearCurso = new ControladorCursos();
					$crearCurso -> create($datos);	

				}else{

					$json = array(

						"detalle"=>"no encontrado"

					);

					echo json_encode($json, true);

					return;

				}

			}else{

				$json = array(

					"detalle"=>"no encontrado"

				);

				echo json_encode($json, true);

				return;

			}

		}else{

			/*=============================================
			Cuando se hace peticiones desde un solo curso
			=============================================*/

			if(array_filter($arrayRutas)[1] == "cursos" && is_numeric(array_filter($arrayRutas)[2])){

				/*=============================================
				Peticiones GET
				=============================================*/

				if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){

					$curso = new ControladorCursos();
					$curso -> show(array_filter($arrayRutas)[2]);	

				}

				/*=============================================
				Peticiones PUT
				=============================================*/

				else if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT"){

					/*=============================================
					Capturar datos
					=============================================*/

					$datos = array();

					parse_str(file_get_contents('php://input'), $datos);	

					$editarCurso = new ControladorCursos();
					$editarCurso -> update(array_filter($arrayRutas)[2], $datos);	

				}

				/*=============================================
				Peticiones DELETE
				=============================================*/

				else if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE"){

					$borrarCurso = new ControladorCursos();
					$borrarCurso -> delete(array_filter($arrayRutas)[2]);	

				}else{

					$json = array(

						"detalle"=>"no encontrado"

					);

					echo json_encode($json, true);

					return;
				
				}


			}else{

				$json = array(

					"detalle"=>"no encontrado"

				);

				echo json_encode($json, true);

				return;
			}

		}

	}

}

