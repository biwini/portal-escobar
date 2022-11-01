<?php
	//Verifico si la variable de session MODIFICACION esta creada, esta se crea en la pagina obtenerDatosModificacion.php.
	if(isset($_SESSION['MODIFICACION'])){
		//Obtengo la pagina a la cual se realizo la modificacion.
		$id_pag = $_SESSION['ID_PAGINA'];

		if($id_pag == "ModificarLiquidacion" || $id_pag == "ModificarLiquidacionMoratoria" || $id_pag == "ModificarLiquidacionArt126"){
			//Eliminacion de arrays a las variables de session utilizadas para la modificacion del registro.
			unset($_SESSION['ARRAY_CONTRATO']);
			unset($_SESSION['ARRAY_ID_CONTRATO']);

			unset($_SESSION['ARRAY_MULTAS']);
			unset($_SESSION['ARRAY_ID_MULTAS']);
			if($id_pag == "ModificarLiquidacion" || $id_pag == "ModificarLiquidacionMoratoria"){
				unset($_SESSION['DESCUENTO']);
			}
			
		}
		else{
			//Identifico la pagina en la cual se realizo la modificacion.
			switch ($id_pag) {
				case 'ModificarLiquidacionIncendio':
					unset($_SESSION['ARRAY_LIQ_INCENDIO']);
					unset($_SESSION['ID_LIQ_INCENDIO']);
				break;
				case 'ModificarLiquidacionElectromecanico':
					unset($_SESSION['ARRAY_LIQ_ELECTRO']);
					unset($_SESSION['ARRAY_ID_ELECTRO']);
				break;
				case 'ModificarLiquidacionDemolicion':
					unset($_SESSION['DESCUENTO']);
					unset($_SESSION['ARRAY_DEMOLICION']);
				break;
				case 'ModificarLiquidacionCarteles':
					unset($_SESSION['ARRAY_LIQ_CARTELES']);
					unset($_SESSION['ID_CARTELES']);
				break;
				case 'ModificarLiquidacionArt13':
					unset($_SESSION['ID_FILA_CONTRATO1']);
					unset($_SESSION['ARRAY_ID_CONTRATO2']);
				break;
				
			}
		}
		if(isset($_SESSION['MODIFICACION_REALIZADA'])){
			unset($_SESSION['MODIFICACION_REALIZADA']);
		}
		//Eliminacion de variables de session globales utilizadas en la modificacion.
		//Variables de session de los ID
		unset($_SESSION["ID_LIQUIDACION"]);
		unset($_SESSION["ID_NOMENCLATURA"]);
		unset($_SESSION["ID_CLIENTE"]);
		//Variables de session para almacenar el antiguo valor de los campos.
		unset($_SESSION["ANTIGUO_NOMBRE_CLIENTE"]);
		unset($_SESSION["ANTIGUA_ZONIFICACION"]);
		unset($_SESSION["ANTIGUO_DESCUENTO"]);
		unset($_SESSION["ANTIGUO_TOTAL"]);
		//Variables de session de los formulario de liquidaciones.
		unset($_SESSION['FechaLiquidacion']);
		unset($_SESSION['NombreCliente']);
		unset($_SESSION['ZONIFICACION']);
		unset($_SESSION['TOTAL']);
		unset($_SESSION['ARRAY_NOMENCLATURA']);
		unset($_SESSION["MODIFICACION"]);
		unset($_SESSION['FORMULARIO_COMPLETO']);
	}
?>