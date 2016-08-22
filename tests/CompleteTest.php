<?php
use App\UsuariosABM;
use App\FavoritosABM;
use App\PagosABM;

class CompleteTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample() {
		$usuarios = UsuariosABM::leer_lista(
			false,
			0,
			100
		);
		if (count($usuarios) > 0) {
			foreach ($usuarios as $usuario) {
				UsuariosABM::eliminar_por_id($usuario->codigousuario);
			}
		}
		
		$pagos = PagosABM::leer_lista(
			false,
			0,
			100
		);
		if (count($pagos) > 0) {
			foreach ($pagos as $pago) {
				PagosABM::eliminar_por_id($pago->codigopago);
			}
		}
		
		//Test 1: insertar usuario:
		$id = UsuariosABM::insertar(
				[
					"usuario" => "pmendez",
					"clave" => "1234",
					"edad" => 37
				]
		);
		$this->assertTrue($id > 0);
		
		//Test 2: modificar usuario:
		$this->assertTrue(
			UsuariosABM::modificar_por_id(
				[
					"usuario" => "pmendezd",
					"edad" => 33
				],
				$id
			)
		);
		
		//Test 3: leer usuario:
		$usuarios = UsuariosABM::leer_lista(
			false,
			0,
			1
		);
		$this->assertTrue(count($usuarios) > 0);
		
		//Test 4: eliminar usuarios:
		UsuariosABM::eliminar_por_id($id);
		$usuarios = UsuariosABM::leer_lista(
			false,
			0,
			1
		);
		$this->assertTrue(count($usuarios) == 0);
		/////////////////////////////////////////////////////////
		//Test 5: insertar favorito:
		$id = UsuariosABM::insertar(
				[
					"usuario" => "pmendez",
					"clave" => "1234",
					"edad" => 37
				]
		);
		$id_2 = UsuariosABM::insertar(
				[
					"usuario" => "jgarcia",
					"clave" => "5678",
					"edad" => 44
				]
		);
		$id_3 = UsuariosABM::insertar(
				[
					"usuario" => "gsaravia",
					"clave" => "9012",
					"edad" => 55
				]
		);
		$id = FavoritosABM::insertar(
				[
					"codigousuario" => $id,
					"codigousuariofavorito" => $id_2
				]
		);
		FavoritosABM::insertar(
			[
				"codigousuario" => $id_2,
				"codigousuariofavorito" => $id_3
			]
		);
		$this->assertTrue($id > 0);
		
		//Test 6: modificar favorito:
		$this->assertTrue(
			FavoritosABM::modificar_por_id(
				[
					"codigousuariofavorito" => $id_3
				],
				$id
			)
		);
		
		//Test 7: leer favoritos:
		$favoritos = FavoritosABM::leer_lista(
			false,
			0,
			100
		);
		$this->assertTrue(count($favoritos) > 0);
		
		//Test 8: eliminar favoritos:
		FavoritosABM::eliminar_por_id($id);
		$favoritos = FavoritosABM::leer_lista(
			false,
			0,
			100
		);
		$this->assertTrue(count($favoritos) == 1);
		/////////////////////////////////////////////////////////
		//Test 9: insertar pago:
		$id = PagosABM::insertar(
				[
					"importe" => 4000,
					"fecha" => "2016-02-21",
					"codigousuario" => $id_2
				]
		);
		PagosABM::insertar(
			[
				"importe" => 7000,
				"fecha" => "2016-02-27",
				"codigousuario" => $id_3
			]
		);
		$this->assertTrue($id > 0);
		
		//Test 10: modificar pago:
		$this->assertTrue(
			PagosABM::modificar_por_id(
				[
					"importe" => 15230
				],
				$id
			)
		);
		
		//Test 11: leer pagos:
		$pagos = PagosABM::leer_lista(
			false,
			0,
			100
		);
		$this->assertTrue(count($pagos) > 0);
		
		//Test 12: eliminar pagos:
		PagosABM::eliminar_por_id($id);
		$pagos = PagosABM::leer_lista(
			false,
			0,
			100
		);
		$this->assertTrue(count($pagos) == 1);
	}
}
