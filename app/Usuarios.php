<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model {
	protected $primaryKey = "codigousuario";
	protected $table = "usuarios";
	public $timestamps = false;
	public static $ultimos_errores;
	private static $rules = [
		"usuario"  => "required|filled|string|max:60|unique:usuarios,usuario",
		"clave"  => "required|filled|string|max:40",
		"edad"  => "required|filled|integer|min:19|max:120"
    ];
	
	public static function boot() {
		self::creating(function ($usuario) {
			$valido = self::validar((array)$usuario->attributes);
			if ($valido === true) {
				self::$ultimos_errores = [];
				return true;
			}
			else {
				self::$ultimos_errores = $valido;
				return false;
			}
		});
		
		self::updating(function ($usuario) {
			$valido = self::validar((array)$usuario->attributes);
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
	
	public function favoritos() {
		return $this->hasMany('App\Favoritos', 'codigousuario');
	}
	
	public function favoritos_2() {
		return $this->hasMany('App\Favoritos', 'codigousuariofavorito');
	}
	
	public function usuarios_pagos() {
		return $this->hasMany('App\UsuariosPagos', 'codigousuario');
	}
}
