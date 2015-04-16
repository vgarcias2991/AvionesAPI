<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Avion;
use App\Fabricante;

use Response;

class FabricanteAvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($idFabricante)
	{
		// Mostramos todos los aviones de un fabricante.
		// Comprobamos si el fabricante existe.
		$fabricante=Fabricante::find($idFabricante);

		if(!$fabricante){
			// Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json([
				'errors'=>Array(['code'=>404,'mensaje'=>'No se encuentra un fabricante con ese codigo.'])
			],404);
		}

		return response()->json(['status'=>'ok','data'=>$fabricante->aviones()->get()],200);
		// O tambien: return response()->json(['status'=>'ok','data'=>$fabricante->aviones],200);




	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($idFabricante,Request $request)
	{
		// Metodo llamado al hacer un POST.
		// Comprobamos que recibimos todos los campos.
		if(!$request->input('serie') || !$request->input('modelo') || !$request->input('longitud')||!$request->input('capacidad')||!$request->input('velocidad')||!$request->input('alcance')){

			//NO estamos recibiendo los campos necesarios. Devolvemos error.
			return response()->json(['errors'=>Array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta.'])],422);
		}
		$fabricante=Fabricante::find($idFabricante);
		if(!$fabricante){
			// Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json([
				'errors'=>Array(['code'=>404,'mensaje'=>'No se encuentra un fabricante con ese codigo.'])
			],404);
		}

		// Insertamos los datos recibidos en la tabla.
		
		$nuevoAvion = $fabricante->aviones()->create($request->all());

		//Devolvemos la respuesta Http 201 (Created) + los datos del nuevo fabricante + una cabecera de Location

		$respuesta = Response::make(json_encode(['data'=>$nuevoAvion]),201)->header('Location','http://www.dominio.local/aviones/'.$nuevoAvion->serie)->header('Content-Type','application/json');

		return $respuesta;

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
