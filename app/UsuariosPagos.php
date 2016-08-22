<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuariosPagos extends Model {
	protected $primaryKey = "codigousuariopago";
	protected $table = "usuariospagos";
	public $timestamps = false;
	public static $ultimos_errores;
	private static $rules = [
		"codigopago"  => "required|filled|integer|exists:pagos,codigopago",
		"codigousuario"  => "required|filled|integer|exists:usuarios,codigousuario"
    ];
	
	public static function boot() {
		self::creating(function ($usuario_pago) {
			$valido = self::validar((array)$usuario_pago->attributes);
			if ($valido === true) {
				self::$ultimos_errores = [];
				return true;
			}
			else {
				self::$ultimos_errores = $valido;
				return false;
			}
		});
		
		self::updating(function ($usuario_pago) {
			$valido = self::validar((array)$usuario_pago->attributes);
			if ($valido === true) {
				self::$ultimos_errores = [];
				return true;
			}
			else {
				self::$ultimos_errores = $valido;
				return false;
			}
		});
    }
	
	private static function validar($campos) {
		$validador = \Validator::make(
			$campos,
			self::$rules
		);
		
		if ($validador->fails()) {
			return $validador->messages()->getMessages();
		}
		else {
			return true;
		}
	}
	
	public function pago() {
		return $this->belongsTo('App\Pagos', 'codigopago');
	}
	
	public function usuario() {
		return $this->belongsTo('App\Usuarios', 'codigousuario');
	}
}
