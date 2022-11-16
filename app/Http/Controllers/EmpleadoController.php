<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()//Metodo 'index'
    {
        //vamos a consultar toda la info a partir de esta fuente(Empleado)modelo
        //vamos a tomar 5 registros (paginate)
        //vamos a almacernarlos en una variable($datos['empleados'])
        //vamos a acceder directamnete a traves del index

        $datos['empleados']=Empleado::paginate(5);
        return view('empleado.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()  //Metodo 'create'
    {
        //
        return view('empleado.create'); //Le damos al controller la info de view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//recibe toda la info y la prepara para enviarla a la tabla
    {
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje=[
            'required'=>'El :attribute es requerido',
            'Foto.required'=>'La foto es requerida'

        ];
        $this->validate($request, $campos, $mensaje);


       // $datosEmpleado= request()->all();
        $datosEmpleado= request()->except('_token');//recolectar toda la info menos al token

        //antes de insertar vamos a preguntar si existe una foto
        if($request->hasFile('Foto')){
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public');
        }
        //si hay foto, alteramos lo que es ese campo, dsp utilizazmos el nombre de ese campo
        //y luego lo insertamos (en public/uploads)



        Empleado::insert($datosEmpleado);
        //Recolecta todos los datos, a traves del formulario, quitale la llave(token)
        //Agarra el modelo(Empleado), inserta la base de datos, excetuando el toke

       // return response()->json($datosEmpleado);
       return redirect('empleado')->with('mensaje','Empleado agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //cuando vamos a editar tiene que aparecer la info en pantalla, para eso:
        //buscar la info a partir del id
        //lo retornamos a las vistas
        $empleado=Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            
        ];
        $mensaje=[
            'required'=>'El :attribute es requerido',
            

        ];
        $this->validate($request, $campos, $mensaje);

        if($request->hasFile('Foto')){
            $campos=['Foto'=>'required|max:10000|mimes:jpeg,png,jpg',];
            $mensaje=['Foto.required'=>'La foto es requerida'];
        }



        //recepcionamos datos, le quitamos el token y metodo
        //buscamos el registro (id=$id), actualizo los datos
        $datosEmpleado= request()->except(['_token','_method']);

        //antes de actualizar vamos a preguntar si existe una foto
        if($request->hasFile('Foto')){
            $empleado=Empleado::findOrFail($id);//recuperamos la info del empleado
            Storage::delete('public/'.$empleado->Foto);//haga el borrado
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public');//si hubo cambios que actualice
        }


        Empleado::where('id','=',$id)->update($datosEmpleado);
        //vuelvo a buscar la info de acuerdo al id
        //retorno nuevamente al formulario pero con los datos actualizados
        $empleado=Empleado::findOrFail($id);
       // return view('empleado.edit', compact('empleado'));
       return redirect('empleado')->with('mensaje','Empleado modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empleado=Empleado::findOrFail($id);

        if(Storage::delete('public/'.$empleado->Foto)){

        Empleado::destroy($id);    //borra y luego redirecciona  
        }
   
        return redirect('empleado')->with('mensaje','Empleado borrado');
    }
}
