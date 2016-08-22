<?php
namespace App;

class PagosABM {
	public static function leer_lista($order_by = false, $desde = false, $cantidad = false) {
		/*
			Lee todos los registros de la tabla `pagos`, con JOIN hacia la tabla de `usuariospagos`, pudiéndose proporcionar los siguientes parámetros:
			- $order_by: un Array por medio del cual se puede indicar la composición de la cláusula "ORDER BY", del siguiente modo:
				[
					"CAMPO_1" => {asc|desc},
					"CAMPO_2" => {asc|desc},
					...
					"CAMPO_N" => {asc|desc}
				]
				- Ejemplo:
				[
					"importe" => asc,
					"fecha" => asc
				]
				- Equivale a:
					ORDER BY importe ASC,fecha ASC
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
		
		$retorno = UsuariosPagos::with(["pago", "usuario"]);
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
			Inserta un registro en la tabla de `pagos` y su usuario relacionado en la de `usuariospagos`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"importe" => 150,
					"fecha" => "2016-04-15",
					"codigousuario" => 2
				]
		*/
		
		try {
			\DB::transaction(function($campos) use($campos) {
				$registro = new Pagos;
				foreach ($campos as $campo => $valor) {
					if ($campo == "codigousuario") continue;
					$registro->$campo = $valor;
				}
				$registro->save();
				if (count(Pagos::$ultimos_errores) == 0) {
					try {
						$last_insert_id = $registro->codigopago;
						$registro = new UsuariosPagos;
						$registro->codigopago = $last_insert_id;
						$registro->codigousuario = $campos["codigousuario"];
						$registro->save();
						if (count(UsuariosPagos::$ultimos_errores) > 0) {
							throw new \Exception("usuarios");
						}
					}
					catch (\Exception $e) {
						throw new \Exception($e->getMessage());
					}
				}
				else {
					throw new \Exception("pagos");
				}
			});
		}
		catch (\Exception $e) {
			$error = $e->getMessage();
			switch ($error) {
				case "pagos":
					return Pagos::$ultimos_errores;
				case "usuarios":
					return UsuariosPagos::$ultimos_errores;
			}
		}
		
		return Pagos::$last_insert_id;
	}
	
	public static function eliminar_por_id($id) {
		/*
			Elimina a un determinado usuario, cuya clave principal coincida con el valor del campo `id`.
		*/
		
		Pagos::where("codigopago", $id)->delete();
	}
	
	public static function modificar_por_id($campos, $id) {
		/*
			Modifica registros en las tablas de `pagos` y `usuariospagos`.
			- $campos: un Array por medio del cual se especifican los valores para el registro que se debe insertar, de acuerdo con la siguiente sintaxis:
				[
					"CAMPO_1" => VALOR_1,
					"CAMPO_2" => VALOR_2,
					...
					"CAMPO_N" => VALOR_N
				]
				- Ejemplo:
				[
					"importe" => 150,
					"fecha" => "2016-04-15",
					"codigousuario" => 2
				]
			- $id: valor de la clave principal del registro que se modificará, en la tabla de `pagos`.
		*/
		
		try {
			$campos["codigopago"] = $id;
			\DB::transaction(function($campos) use($campos) {
				if (count($campos) == 0) return false;
				$registro = Pagos::find($campos["codigopago"]);
				if (count($registro) == 0) return false;
				foreach ($campos as $campo => $valor) {
					if ($campo == "codigousuario" || $campo == "codigopago") continue;
					$registro->$campo = $valor;
				}
				$registro->fecha = mb_substr($registro->fecha, 0, 10);
				$registro->save();
				if (count(Pagos::$ultimos_errores) == 0) {
					if (isset($campos["codigousuario"])) {
						$registro = UsuariosPagos::wherecodigopago($campos["codigopago"])->first();
						$registro->codigousuario = $campos["codigousuario"];
						$registro->save();
						if (count(UsuariosPagos::$ultimos_errores) > 0) {
							throw new \Exception("");
						}
						else {
							return true;
						}
					}
					else {
						return true;
					}
				}
				else {
					return Pagos::$ultimos_errores;
				}
			});
		}
		catch (\Exception $e) {
			return UsuariosPagos::$ultimos_errores;
		}
		
		return true;
	}
}
