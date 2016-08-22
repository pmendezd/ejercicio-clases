<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model {
	protected $primaryKey = "codigopago";
	protected $table = "pagos";
	public $timestamps = false;
	public static $ultimos_errores;
	public static $last_insert_id;
	private static $rules = [
		"importe"  => "required|filled|numeric|min:0.01|max:999999.99",
		"fecha"  => "required|filled|date|date_format:Y-m-d"
    ];
	
	public static function boot() {
		self::creating(function ($pago) {
			$valido = self::validar((array)$pago->attributes);
			if ($valido === true) {
				self::$ultimos_errores = [];
				return true;
			}
			else {
				self::$ultimos_errores = $valido;
				return false;
			}
		});
		
		self::created(function ($pago) {
			self::$last_insert_id = $pago->codigopago;
			return true;
		});
		
		self::updating(function ($pago) {
			$valido = self::validar((array)$pago->attributes);
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
	
	public function usuarios_pagos() {
		return $this->hasMany('App\UsuariosPagos', 'codigopago');
	}
}
