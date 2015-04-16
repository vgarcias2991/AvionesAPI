<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// Cargamos Fabricante por que lo usamos mas abajo.
use App\Fabricante;
use Response;
class FabricanteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Devolvemos un JSON con todos los fabricantes
		//return Fabricante::all();


		// Para devolver un JSON con codigo de respuesta HTTP.
		return response()->json([

				'status'=>'ok',
				'data' => Fabricante::all()
			],200);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	// No se utiliza este metodo por que se usaria para mostrar un formulario
	// de creacion de Fabricantes. Y una API REST no hace eso.
	/*
	public function create()
	{
		//
	}
	*/
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Metodo llamado al hacer un POST.
		// Comprobamos que recibimos todos los campos.
		if(!$request->input('nombre') || !$request->input('direccion')||!$request->input('telefono')){

			//NO estamos recibiendo los campos necesarios. Devolvemos error.
			return response()->json(['errors'=>Array(['code'=>422,'message'=>'Faltan datos necesarios para procesar el alta.'])],422);
		}
		// Insertamos los datos recibidos en la tabla.
		$nuevoFabricante = Fabricante::create($request->all());

		//Devolvemos la respuesta Http 201 (Created) + los datos del nuevo fabricante + una cabecera de Location

		$respuesta = Response::make(json_encode(['data'=>$nuevoFabricante]),201)->header('Location','http://www.dominio.local/fabricantes/'.$nuevoFabricante->id)->header('Content-Type','application/json');

		return $respuesta;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		/*
		return response()->json([

				'status'=>'ok',
				'data' => Fabricante::find($id)
			],200);
		*/
		$fabricante = Fabricante::find($id);

		if(!$fabricante){
			// Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json([
				'errors'=>Array(['code'=>404,'mensaje'=>'No se encuentra un fabricante con ese codigo.'])
			],404);
		}

		// Devolvemos la informacion encontrada.
		return response()->json(['status'=>'ok','data'=>$fabricante],200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*
	public function edit($id)
	{
		//
	}
	*/
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
		// Comprobamos si el fabricante existe. En otro caso devolvemos error
		$fabricante=Fabricante::find($id);

		// Si no existe mostramos error.
		if(!$fabricante){
			// Devolvemos error 404.
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese codigo'])],404);
		}
		$nombre =$request->input('nombre');
		$direccion = $request->input('direccion');
		$telefono=$request->input('telefono');

		//Comprobamos si recibimos peticion PATH(parcial) o PUT(total)
		if($request->method()=='PATCH'){

			$bandera=false;
			//Actualizacion parcial de datos.
			if($nombre != null && $nombre != ''){
				$fabricante->nombre=$nombre;
				$bandera=true;
			}
			if($direccion != null && $direccion != ''){
				$fabricante->direccion=$direccion;
				$bandera=true;
			}
			if($telefono != null && $telefono != ''){
				$fabricante->telefono=$telefono;
				$bandera=true;
			}
			if($bandera){

				//Grabamos el fabricante
				$fabricante->save();
				//Devolvemos un codigo 200.
				return response()->json(['status'=>'ok','data'=>$fabricante],200);

			}else{
				// Devolvemos un codigo 304 not modified.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningun dato del fabricante'])],304);
			}
		}
		// Metodo PUT actualizamos todos los campos.
		// Comprobamos que recibimos todos.
		if(!$nombre || $direccion || $telefono){
			// Se devuelve codigo 422 Unprocessable Entity.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}	
		//Actualizamos los 3 campos.
		$fabricante->nombre=$nombre;
		$fabricante->direccion=$direccion;
		$fabricante->telefono=$telefono;

		$fabricante->save();
		return response()->json(['status'=>'ok','data'=>$fabricante],200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//Comprobamos si existe el registro indicado
		$fabricante = Fabricante::find($id);

		if(!$fabricante){
			// Se devuelve un array errors con los errores detectados y codigo 404
			return response()->json([
				'errors'=>Array(['code'=>404,'mensaje'=>'No se encuentra un fabricante con ese codigo.'])
			],404);
		}

		//Borramos el fabricante y devolvemos el codigo 204.
		// 204 significa "No Content".
		// Este codigo no muestra texto en el body.
		// Si quisieramos ver el mensaje devolveriamos
		// un 200.
		$fabricante->delete();

		// Devolvemos la informacion encontrada.
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el fabricante'],204);
	}

}
