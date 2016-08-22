<?php
namespace App;

class UsuariosABM {
	public static function leer_lista($order_by = false, $desde = false, $cantidad = false) {
		/*
			Lee todos los registros de la tabla `usuarios`, pudiéndose proporcionar los siguientes parámetros:
			- $order_by: un Array por medio del cual se puede indicar la composición de la cláusula "ORDER BY", del siguiente modo:
				[
					"CAMPO_1" => {asc|desc},
					"CAMPO_2" => {asc|desc},
					...
					"CAMPO_N" => {asc|desc}
				]
				- Ejemplo:
				[
					"edad" => asc,
					"usuario" => asc
				]
				- Equivale a:
					ORDER BY edad ASC,usuario ASC
				- Observaciones:
					- Si no se desea aplicar "ORDER BY", conceder el valor false a éste parámetro.
			- $desde y $cantidad: corresponden, respecticamente, a las dos partes componentes de la cláusula "LIMIT", a saber, el Offser y la cantidad por leer.
				- Ejemplo:
					- $desde = 5;
					- $cantidad = 7;
				- Equivale a:
					LIMIT 5,7
				- Observaciones:
					- Si no se desea aplicar "LIMIT", conceder el valor false a cualquiera de estos parámetros.
		*/
		
		$retorno = Usuarios::select(
			"codigousuario",
			"usuario",
			"clave",
			"edad"
		);
		if ($order_by !== false) {
			foreach ($order_by as $campo => $orden) {
				$retorno = $retorno->orderBy($campo, $orden);
			}
		}
		if ($desde !== false && $cantidad !== false) {
			$retorno = $retorno->skip($desde)->take($cantidad);
		}
		
		try {
			return $retorno->get();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return false;
		}
	}
	
	public static function insertar($campos) {
		/*
			Inserta un registro en la tabla de `usuarios`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"usuario" => "gromero",
					"clave" => sha1(md5("1234clave9876")),
					"edad" => 40
				]
		*/
		
		if (count($campos) == 0) return false;
		$registro = new Usuarios;
		foreach ($campos as $campo => $valor) {
			$registro->$campo = $valor;
		}
		$registro->clave = sha1(md5($registro->clave));
		
		try {
			$registro->save();
			if (count(Usuarios::$ultimos_errores) > 0) {
				return Usuarios::$ultimos_errores;
			}
			else {
				return $registro->codigousuario;
			}
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Usuarios::$ultimos_errores;
		}
	}
	
	public static function eliminar_por_id($id) {
		/*
			Elimina a un determinado usuario, cuya clave principal coincida con el valor del campo `id`.
		*/
		
		Usuarios::where("codigousuario", $id)->delete();
	}
	
	public static function modificar_por_id($campos, $id) {
		/*
			Modifica un registro en la tabla de `usuarios`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"usuario" => "gromero",
					"clave" => sha1(md5("1234clave9876")),
					"edad" => 40
				]
			- $id: valor de la clave principal del registro que se modificará.
		*/
		if (count($campos) == 0) return false;
		$registro = Usuarios::find($id);
		foreach ($campos as $campo => $valor) {
			if ($campo == "clave") {
				$registro->clave = sha1(md5($valor));
			}
			else {
				$registro->$campo = $valor;
			}
		}
		try {
			$registro->save();
			if (count(Usuarios::$ultimos_errores) > 0) {
				return Usuarios::$ultimos_errores;
			}
			else {
				return true;
			}
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Usuarios::$ultimos_errores;
		}
	}
}
