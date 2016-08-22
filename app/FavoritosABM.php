<?php
namespace App;

class FavoritosABM {
	public static function leer_lista($order_by = false, $desde = false, $cantidad = false) {
		/*
			Lee todos los registros de la tabla `favoritos`, con JOIN hacia la tabla de `usuarios`, pudiéndose proporcionar los siguientes parámetros:
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
		
		$retorno = Favoritos::with(["usuario", "favorito"]);
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
			Inserta un registro en la tabla de `favoritos`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"codigousuario" => 1,
					"codigousuariofavorito" => 4
				]
		*/
		
		if (count($campos) == 0) return false;
		$registro = new Favoritos;
		foreach ($campos as $campo => $valor) {
			$registro->$campo = $valor;
		}
		
		try {
			$registro->save();
			if (count(Favoritos::$ultimos_errores) > 0) {
				return Favoritos::$ultimos_errores;
			}
			else {
				return $registro->codigofavorito;
			}
		}
		catch (\Illuminate\Database\QueryException $e) {
			return ["codigousuario_codigousuariofavorito" => [\Lang::get("validation.custom.codigousuario_codigousuariofavorito.unique")]];
		}
	}
	
	public static function eliminar_por_id($id) {
		/*
			Elimina a una determinada asociación de un usuario con su 'favorito', a base de la clave principal de la tabla `favoritos`.
		*/
		
		Favoritos::where("codigofavorito", $id)->delete();
	}
	
	public static function modificar_por_id($campos, $id) {
		/*
			Modifica un registro en la tabla de `favoritos`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"codigousuario" => 2,
					"codigousuariofavorito" => 3
				]
			- $id: valor de la clave principal del registro que se modificará.
		*/
		
		if (count($campos) == 0) return false;
		$registro = Favoritos::find($id);
		foreach ($campos as $campo => $valor) {
			$registro->$campo = $valor;
		}
		try {
			$registro->save();
			if (count(Favoritos::$ultimos_errores) > 0) {
				return Favoritos::$ultimos_errores;
			}
			else {
				return true;
			}
		}
		catch (\Illuminate\Database\QueryException $e) {
			return ["codigousuario_codigousuariofavorito" => [\Lang::get("validation.custom.codigousuario_codigousuariofavorito.unique")]];
		}
	}
}
