<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favoritos extends Model {
	protected $primaryKey = "codigofavorito";
	protected $table = "favoritos";
	public $timestamps = false;
	public static $ultimos_errores;
	private static $rules = [
		"codigousuario"  => "required|filled|integer|exists:usuarios,codigousuario|different:codigousuariofavorito",
		"codigousuariofavorito"  => "required|filled|integer|exists:usuarios,codigousuario"
    ];
	
	public static function boot() {
		self::creating(function ($favorito) {
			$valido = self::validar((array)$favorito->attributes);
			if ($valido === true) {
				self::$ultimos_errores = [];
				return true;
			}
			else {
				self::$ultimos_errores = $valido;
				return false;
			}
		});
		
		self::updating(function ($favorito) {
			$valido = self::validar((array)$favorito->attributes);
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
	
	public function usuario() {
		return $this->belongsTo('App\Usuarios', 'codigousuario');
	}
	
	public function favorito() {
		return $this->belongsTo('App\Usuarios', 'codigousuariofavorito');
	}
}
