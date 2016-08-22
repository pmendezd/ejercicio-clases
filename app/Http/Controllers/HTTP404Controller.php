<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class HTTP404Controller extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$datos = [
			"idioma" => \App::getLocale(),
			"title" => \Lang::get("texts.header.title")
		];
		
		return Response(
			view(
				"404",
				[
					"datos" => $datos
				]
			),
			404
		);
	}
}
