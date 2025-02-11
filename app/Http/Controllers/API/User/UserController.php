<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\UserCreatedJob;
use App\Models\PhysicalActivity;
use App\Models\MedicalRecord;
use App\Models\Suscription;
use App\Models\Appoinment;
use App\Models\Objective;
use App\Models\Address;
use App\Models\Paypal;
use App\Models\Room;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Facades\Config;

/**
* @OA\Info(title="API App - Clinica", version="1.0")
*/
class UserController extends Controller
{
    private $host;

    public function __construct() {
        $this->host = config('app.url');
    }
    /**
     * @OA\Get(
     *     path="/api/show/user/{user}",
     *     summary="Mostrando data del cliente",
     *     operationId="showUser",
     *     tags={"Cliente"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing personal information of Client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function show(User $user)
    {
        if (Auth::user()->hasRole('Usuario')) {

            $cita = Appoinment::where('client_id', Auth::user()->id)->orderByDesc('date')->first();
            $nutricionista = null;
            if (Auth::user()->nutricionist_id != null) {
                $nutricionista = User::find(Auth::user()->nutricionist_id);
            }
            $medical_record = MedicalRecord::where('user_id', $user->id)->first();

            $user = [
                'id' => Auth::user()->id,
                'nombre' => Auth::user()->name,
                'apellido_paterno' => Auth::user()->first_lastname,
                'apellido_materno' => Auth::user()->second_lastname,
                'nombre_completo' =>Auth::user()->name . ' ' . Auth::user()->first_lastname . ' ' . Auth::user()->second_lastname,
                'sexo' => Auth::user()->sex,
                'estatura' => ($medical_record == null) ? 0.0 :$medical_record->height,
                'email' => Auth::user()->email,
                'telefono' => Auth::user()->phone,
                'registro_consumo' => ($medical_record == null) ? null : $medical_record->consumption_record,
                'fecha_nacimiento' => Auth::user()->birthday,
                'condiciones_medicas' => ($medical_record == null) ? null :$medical_record->health_conditions,
                'alergias' => ($medical_record == null) ? null :$medical_record->alergies,
                'historial' => ($medical_record == null) ? null : $medical_record->background,
                'desordenes' => ($medical_record == null) ? null : $medical_record->disorders,
                'medicinas' => ($medical_record == null) ? null : $medical_record->medicines,
                'num_identificacion' => Auth::user()->dni,
                'profesion' => Auth::user()->profesion,
                'estado_civil' => ($medical_record == null) ? null : $medical_record->civil_status,
                'lugar_residencia' => Auth::user()->residence,
                'suscripcion' => [
                    'id' => Auth::user()->suscripcion->id,
                    'empieza' => Auth::user()->suscripcion->start_date,
                    'termina' => Auth::user()->suscripcion->end_date,
                ],
                'nutricionista' => ($nutricionista != null ) ? ['id' => $nutricionista->id, 'nombre' => $nutricionista->name] : 'Sin nutricionista asignado',
                'consultorio' => [
                    'id' => Auth::user()->consultorio->id,
                    'nombre' => Auth::user()->consultorio->name,
                ],
                'cita' => [
                    'fecha' => ($cita != null) ? $cita->date : null,
                ]
            ];

            if (Auth::user()->files != null && Auth::user()->files != 'null' && count(json_decode(Auth::user()->files)) > 0) {
                foreach (json_decode(Auth::user()->files) as $file) {
                    $user['archivos'][] = $this->host . Storage::url($file);
                }
            }

            if ($medical_record != null && $medical_record->objective != null) {
                $subcategory = Subcategory::find($medical_record->objective);
                $user['objetivo'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->physical_activity != null) {
                $subcategory = Subcategory::find($medical_record->physical_activity);
                $user['actividad_fisica'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->alcohol_consumption != null) {
                $subcategory = Subcategory::find($medical_record->alcohol_consumption);
                $user['consumo_alcohol'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->smoke != null) {
                $subcategory = Subcategory::find($medical_record->smoke);
                $user['fumador'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->water_consumption != null) {
                $subcategory = Subcategory::find($medical_record->water_consumption);
                $user['consumo_de_agua_diario'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->stress != null) {
                $subcategory = Subcategory::find($medical_record->stress);
                $user['estres_general'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->hours_of_sleep != null) {
                $subcategory = Subcategory::find($medical_record->hours_of_sleep);
                $user['horas_de_sueno'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            return response()->json([
                'code' => 200,
                'msg' => 'Mostrando al usuario Solicitado.',
                'data' => [
                    'user' => $user
                ]
            ]);

        } else {

            $cita = Appoinment::where('client_id', $user->id)->orderByDesc('date')->first();
            $nutricionista = null;
            $medical_record = MedicalRecord::where('user_id',$user->id)->first();

            if ($user->nutricionist_id != null) {
                $nutricionista = User::find($user->nutricionist_id);
            }
            $usuario = [
                'id' => $user->id,
                'nombre' => $user->name,
                'apellido_paterno' => $user->first_lastname,
                'apellido_materno' => $user->second_lastname,
                'nombre_completo' => $user->name . ' ' . $user->first_lastname . ' ' . $user->second_lastname,
                'sexo' => $user->sex,
                'estatura' => ($medical_record != null) ? $medical_record->height : 0.0,
                'email' => $user->email,
                'telefono' => $user->phone,
                'registro_consumo' => ($medical_record != null) ? $medical_record->consumption_record : null,
                'fecha_nacimiento' => $user->birthday,
                'condiciones_medicas' => ($medical_record == null) ? null :$medical_record->health_conditions,
                'alergias' => ($medical_record == null) ? null :$medical_record->alergies,
                'historial' => ($medical_record == null) ? null : $medical_record->background,
                'desordenes' => ($medical_record == null) ? null : $medical_record->disorders,
                'medicinas' => ($medical_record == null) ? null : $medical_record->medicines,
                'estado_civil' => ($medical_record == null) ? null : $medical_record->civil_status,
                'num_identificacion' => $user->dni,
                'profesion' => $user->profesion,
                'lugar_residencia' => $user->residence,
                'nutricionista' => ($nutricionista != null ) ? ['id' => $nutricionista->id, 'nombre' => $nutricionista->name] : 'Sin nutricionista asignado',
                'cita' => [
                    'fecha' => ($cita != null) ? $cita->date : null,
                ]
            ];

            if ($user->files != null && $user->files != 'null' && count(json_decode($user->files)) > 0) {
                foreach (json_decode($user->files) as $file) {
                    $usuario['archivos'][] = $this->host . Storage::url($file);
                }
            }

            if ($user->room_id != null) {
                $usuario['consultorio'] = [
                    'id' => $user->consultorio->id,
                    'nombre' => $user->consultorio->name,
                ];
            } else {
                $usuario['consultorio'] = null;
            }

            if ($user->suscripcion != null)
            {
                $usuario['suscripcion'] = [
                    'id' => $user->suscripcion->id,
                    'empieza' => $user->suscripcion->start_date,
                    'termina' => $user->suscripcion->end_date,
                ];
            }

            if ($medical_record != null && $medical_record->objective != null) {
                $subcategory = Subcategory::find($medical_record->objective);
                $usuario['objetivo'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->physical_activity != null) {
                $subcategory = Subcategory::find($medical_record->physical_activity);
                $usuario['actividad_fisica'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->alcohol_consumption != null) {
                $subcategory = Subcategory::find($medical_record->alcohol_consumption);
                $usuario['consumo_alcohol'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->smoke != null) {
                $subcategory = Subcategory::find($medical_record->smoke);
                $usuario['fumador'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->water_consumption != null) {
                $subcategory = Subcategory::find($medical_record->water_consumption);
                $usuario['consumo_de_agua_diario'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->stress != null) {
                $subcategory = Subcategory::find($medical_record->stress);
                $usuario['estres_general'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->hours_of_sleep != null) {
                $subcategory = Subcategory::find($medical_record->hours_of_sleep);
                $usuario['horas_de_sueno'] = [
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            return response()->json([
                'code' => 200,
                'msg' => 'Mostrando el usuario solicitado.',
                'data' => [
                    'user' => $usuario
                ]
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/show/clientes",
     *     summary="Mostrando clientes",
     *     operationId="showClientes",
     *     tags={"Cliente"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing list of Clientes"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function showClientes()
    {
        $clientes = User::role('Usuario')->get();

        $users = array();

        foreach ($clientes as $key => $cliente) {
            $cita = Appoinment::where('client_id', $cliente->id)->orderByDesc('date')->first();
            $nutricionista = User::find($cliente->nutricionist_id);
            $medical_record = MedicalRecord::where('user_id', $cliente->id)->first();
    
            $user = [
                'id' => $cliente->id,
                'nombre' => $cliente->name,
                'apellido_paterno' => $cliente->first_lastname,
                'apellido_materno' => $cliente->second_lastname,
                'nombre_completo' => $cliente->name . ' ' . $cliente->first_lastname . ' ' . $cliente->second_lastname,
                'sexo' => $cliente->sex,
                'estatura' => ($medical_record == null) ? 0.0 : $medical_record->height,
                'email' => $cliente->email,
                'telefono' => $cliente->phone,
                'registro_consumo' => ($medical_record == null) ? 'No hay registro de consumo': $medical_record->consumption_record,
                'fecha_nacimiento' => $cliente->birthday,
                'condiciones_medicas' => ($medical_record == null) ? null : $medical_record->health_conditions,
                'alergias' => ($medical_record == null) ? null : $medical_record->alergies,
                'historial' => ($medical_record == null) ? null : $medical_record->background,
                'desordenes' => ($medical_record == null) ? null : $medical_record->disorders,
                'medicinas' => ($medical_record == null) ? null : $medical_record->medicines,
                'estado_civil' => ($medical_record == null) ? null : $medical_record->civil_status,
                'num_identificacion' => $cliente->dni,
                'profesion' => $cliente->profesion,
                'lugar_residencia' => $cliente->residence,
                'suscripcion' => [
                    'id' => ($cliente->suscripcion == null) ? null : $cliente->suscripcion->id,
                    'empieza' => ($cliente->suscripcion == null) ? null : $cliente->suscripcion->start_date,
                    'termina' => ($cliente->suscripcion == null) ? null : $cliente->suscripcion->end_date,
                ],
                'nutricionista' => ($nutricionista != null) ? ['id' => $nutricionista->id, 'nombre' => $nutricionista->name] : 'Sin asignar',
                'consultorio' => [
                    'id' => $cliente->consultorio->id,
                    'nombre' => $cliente->consultorio->name,
                ],
                'cita' => [
                    'fecha' => ($cita != null) ? $cita->date : null,
                ]
            ];

            if ($cliente->files != null && $cliente->files != 'null' && count(json_decode($cliente->files)) > 0) {
                foreach (json_decode($cliente->files) as $file) {
                    $user['archivos'][] = $this->host . Storage::url($file);
                }
            }

            if ($medical_record != null && $medical_record->physical_activity != null) {
                $subcategory = Subcategory::find($medical_record->physical_activity);
                $user['actividad_fisica'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->objective != null) {
                $subcategory = Subcategory::find($medical_record->objective);
                $user['objetivo'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->alcohol_consumption != null) {
                $subcategory = Subcategory::find($medical_record->alcohol_consumption);
                $user['consumo_alcohol'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->smoke != null) {
                $subcategory = Subcategory::find($medical_record->smoke);
                $user['fumador'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->water_consumption != null) {
                $subcategory = Subcategory::find($medical_record->water_consumption);
                $user['consumo_de_agua_diario'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->stress != null) {
                $subcategory = Subcategory::find($medical_record->stress);
                $user['estres_general'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            if ($medical_record != null && $medical_record->hours_of_sleep != null) {
                $subcategory = Subcategory::find($medical_record->hours_of_sleep);
                $user['horas_de_sueno'] =[
                    'descripcion' => $subcategory->description,
                    'id' => $subcategory->id
                ];
            }

            array_push($users, $user);
        }

        $msg = 'Mostrando los clientes.';
        if ($clientes->isEmpty()) {
            $msg = 'No hay clientes registrados.';
        }
        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $users
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/nutricionistas",
     *     summary="Mostrando nutricionistas",
     *     operationId="showNutricionistas",
     *     tags={"Nutricionista"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing list of Nutricionistas"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function showNutricionistas()
    {
        $nutricionistas = User::role(['Admin','Nutricionista'])->get();

        $msg = 'Mostrando los nutricionistas.';
        if ($nutricionistas->isEmpty()) {
            $msg = 'No hay nutricionistas registrados.';
        }
        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $nutricionistas
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/create/user",
     *     summary="Crear cliente",
     *     operationId="createUsers",
     *     tags={"Cliente"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="apellido_paterno",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="apellido_materno",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="sexo",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *               @OA\Property(
     *                  property="telefono",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="fecha_nacimiento",
     *                  type="date"
     *               ),
     *               @OA\Property(
     *                  property="nutricionista_id",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="estatura",
     *                  type="double"
     *               ),
     *               @OA\Property(
     *                  property="consultorio_id",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="registro_consumo",
     *                  type="text"
     *               ),
     *               @OA\Property(
     *                   property="alergias[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               ),
     *               @OA\Property(
     *                   property="condiciones_medicas[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               ),
     *              @OA\Property(
     *                   property="medicinas[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               ),
     *              @OA\Property(
     *                   property="desordenes[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               ),
     *              @OA\Property(
     *                   property="historial",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="horas_dormidas",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="actividad_fisica_id",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="objetivo_id",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="periodo_id",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="num_identificacion",
     *                   type="string"
     *               ),
     *                @OA\Property(
     *                   property="profesion",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="lugar_residencia",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="estado_civil",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="consumo_acohol",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="fumador",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="consumo_agua",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="estres",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="horas_de_sueño",
     *                   type="integer",
     *              )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Cliente created"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function store (Request $request)
    {
        $rules = [
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
        ];

        $messages = [
            'nombre.required' => 'Es necesario ingresar un nombre para el usuario',
            'apellido_paterno.required' => 'Es necesario ingresar un apellido paterno para el usuario',
            'apellido_materno.required' => 'Es necesario ingresar un apellido materno para el usuario',
            'email.required' => 'Es necesario indicar un email para poder contactarlo',
            'email.email' => 'Formato incorrecto de email',
            'telefono.required' => 'Es necesario indicar un telefono para poder contactarlo',
            'telefono.numeric' => 'El telefono debe ser un numero',
        ];

        $this->validate($request, $rules, $messages);

        $password = Str::random(10);

        $user = new User();
        $user->name = $request->nombre;
        $user->first_lastname = $request->apellido_paterno;
        $user->second_lastname = $request->apellido_materno;
        $user->sex = ($request->filled('sexo')) ? $request->sexo : 'F';
        $user->password = Hash::make($password);
        $user->email = $request->email;
        $user->phone = $request->telefono;
        $user->birthday =($request->filled('fecha_nacimiento')) ? $request->fecha_nacimiento : '2023-01-01';
        $user->rol = 'Usuario';
        //We need to check if the register method is comming from paypal
        // Or its being registered for the nutricionist
        $consultorio = null;
        $consultorio_id = null;
        if ($request->filled('metodo_pago')) {
            $consultorio = Room::first();
            $consultorio_id = $consultorio->id; 
        } else if ($request->has('consultorio_id')) {
            $consultorio = Room::find($request->consultorio_id);
            if ($consultorio == null) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Consultorio que estas tratando de asignar no existe.',
                    'data' => null
                ]);
            }
            $consultorio_id = $consultorio->id;
        }
        // same process for the nutricionist, we need to know if its comming from paypal
        // or its coming for thr register system
        $nutricionista = null;
        $nutricionista_id = null;
        if ($request->has('nutricionista_id')) {
            $nutricionista = User::find($request->nutricionista_id);
            if ($nutricionista == null) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Nutricionista que estas tratando de asignar no existe.',
                    'data' => null
                ]);
            }
            $nutricionista_id = $nutricionista->id;
        } else if ($request->filled('metodo_pago')) {
            $nutricionista = User::role('Nutricionista')->first();
            $nutricionista_id = $nutricionista->id;
        }

        $user->nutricionist_id = $nutricionista_id;
        $user->room_id = $consultorio_id;
        $user->dni =($request->has('num_identificacion')) ? $request->num_identificacion : null;
        $user->profesion = ($request->has('profesion'))  ? $request->profesion : null;
        $user->residence = ($request->has('lugar_residencia')) ?  $request->lugar_residencia : null;
        //Check if there is a file on the request
        $paths = array();
        if ($request->has('archivos')) {
            foreach ($request->archivos as $archivo) {
                $path = Storage::disk('public')->put('files', $archivo, 'public');
                array_push($paths, $path);
            }
            
            $user->files = json_encode($paths);
        }

        $user->save();
        // We assign the default rol to the user
        $user->assignRole('Usuario');

        // If thre register is full we will recieve more information in the register
        // This extra information goes on a medical record
        $medical_record = new MedicalRecord();
        $medical_record->alergies = (($request->filled('alergias')) && count($request->alergias) > 0) ? json_encode($request->alergias) : null;
        $medical_record->health_conditions = (($request->filled('condiciones_medicas')) && count($request->condiciones_medicas) > 0) ? json_encode($request->condiciones_medicas) : null;
        $medical_record->medicines = ($request->filled('medicinas') && count($request->medicinas) > 0) ? json_encode($request->medicinas) : null;
        $medical_record->disorders = ($request->filled('desordenes') && count($request->desordenes) > 0) ? json_encode($request->desordenes) : null;
        $medical_record->civil_status = ($request->filled('estado_civil')) ? $request->estado_civil : 'Soltero/a';

        if ($request->filled('actividad_fisica_id')) {
            $subcategory = Subcategory::find($request->actividad_fisica_id);
            $medical_record->physical_activity = $subcategory->id;
        }
        if ($request->filled('objetivo_id')) {
            $subcategory = Subcategory::find($request->objetivo_id);
            $medical_record->objective = $subcategory->id;
        }

        $medical_record->background = ($request->filled('historial')) ? $request->historial : null;
        $medical_record->consumption_record = ($request->filled('registro_consumo')) ? $request->registro_consumo : null;
        $medical_record->height = ($request->filled('estatura')) ? $request->estatura : 0.0;
        $medical_record->user_id = $user->id;

        if ($request->filled('consumo_alcohol_id')) {
            $subcategory = Subcategory::find($request->consumo_alcohol_id);
            $medical_record->alcohol_consumption = $subcategory->id;
        }

        if ($request->filled('tipo_fumador_id')) {
            $subcategory = Subcategory::find($request->tipo_fumador_id);
            $medical_record->smoke = $subcategory->id;
        }

        if ($request->filled('consumo_agua_id')) {
            $subcategory = Subcategory::find($request->consumo_agua_id);
            $medical_record->water_consumption = $subcategory->id;
        }

        if ($request->filled('nivel_estres_id')) {
            $subcategory = Subcategory::find($request->nivel_estres_id);
            $medical_record->stress = $subcategory->id;
        }

        if ($request->filled('horas_dormidas')) {
            $subcategory = Subcategory::find($request->horas_dormidas);
            $medical_record->hours_of_sleep = $subcategory->id;
        }

        $medical_record->save();

        // We check if the register its comming from Paypal
        $periodo = 1;
        if ($request->filled('periodo_id')) {
            $paypal_catalogo = Paypal::find((int) $request->periodo_id);
            if ($paypal_catalogo != null) {
                $periodo = $paypal_catalogo->months;
            }
        }
        $suscripcion = new Suscription();
        $suscripcion->start_date = date('Y-m-d');
        $suscripcion->end_date = date('Y-m-d', strtotime('+' . $periodo . ' month'));
        $suscripcion->user_id = $user->id;
        $suscripcion->save();

        $token = $user->createToken('user')->plainTextToken;

        UserCreatedJob::dispatch($user, $password);

        return response()->json([
            'code' => 200,
            'msg' => 'Usuario creado con éxito.',
            'data' => $user,
            'token' => $token
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/update/user/{user}",
     *     summary="Actualizar informacion de nutricionista/cliente",
     *     operationId="updateUsers",
     *     tags={"Cliente"},
     *     security={ {"sanctum": {} }},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="apellido_paterno",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="apellido_materno",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="sexo",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *              @OA\Property(
     *                  property="telefono",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                  property="estatura",
     *                  type="double"
     *               ),
     *              @OA\Property(
     *                  property="fecha_nacimiento",
     *                  type="date"
     *               ),
     *              @OA\Property(
     *                  property="nutricionista_id",
     *                  type="integer"
     *               ),
     *               @OA\Property(
     *                  property="consultorio_id",
     *                  type="integer"
     *               ),
     *              @OA\Property(
     *                   property="registro_consumo",
     *                   type="text"
     *              ),
     *              @OA\Property(
     *                   property="alergias[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *              ),
     *              @OA\Property(
     *                   property="condiciones_medicas[]",
     *                   type="array",
     *                   @OA\Items(type="string")
     *              ),
     *              @OA\Property(
     *                   property="actividad_fisica_id",
     *                   type="integer"
     *              ),
     *              @OA\Property(
     *                   property="objetivo_id",
     *                   type="integer"
     *              ),
     *              @OA\Property(
     *                   property="periodo_id",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="num_identificacion",
     *                   type="string"
     *               ),
     *                @OA\Property(
     *                   property="profesion",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="lugar_residencia",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="estado_civil",
     *                   type="string"
     *               ),
     *              @OA\Property(
     *                   property="consumo_acohol",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="fumador",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="consumo_agua",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="estres",
     *                   type="integer",
     *              ),
     *              @OA\Property(
     *                   property="horas_de_sueño",
     *                   type="integer",
     *              )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Update data of nutricionista/cliente"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function update (Request $request, User $user)
    {
        $rules = [
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'email' => 'required|email',
            'telefono' => 'required|numeric',
            'fecha_nacimiento' => 'required',
        ];

        $messages = [
            'nombre.required' => 'Es necesario ingresar un nombre para el usuario',
            'apellido_paterno.required' => 'Es necesario ingresar un apellido paterno para el usuario',
            'apellido_materno.required' => 'Es necesario ingresar un apellido materno para el usuario',
            'email.required' => 'Es necesario indicar un email para poder contactarlo',
            'email.email' => 'Formato incorrecto de email',
            'telefono.required' => 'Es necesario indicar un telefono para poder contactarlo',
            'telefono.numeric' => 'El telefono debe ser un numero',
            'fecha_nacimiento.required' => 'Es necesario indicar una fecha de nacimiento',
        ];

        $this->validate($request, $rules, $messages);

        $user->name = $request->nombre;
        $user->first_lastname = $request->apellido_paterno;
        $user->second_lastname = $request->apellido_materno;
        $user->email = $request->email;
        $user->phone = $request->telefono;
        $user->birthday = $request->fecha_nacimiento;
        $user->dni =$request->num_identificacion;
        $user->profesion = $request->profesion;
        $user->residence = $request->lugar_residencia;
        $user->sex = $request->sexo;

        $medical_record = MedicalRecord::where('user_id', $user->id)->first();
        if ($medical_record == null) {
            $medical_record = new MedicalRecord();
        }
        $medical_record->alergies = (($request->filled('alergias')) && count($request->alergias) > 0) ? json_encode($request->alergias) : null;
        $medical_record->health_conditions = (($request->filled('condiciones_medicas')) && count($request->condiciones_medicas) > 0) ? json_encode($request->condiciones_medicas) : null;
        $medical_record->medicines = ($request->filled('medicinas') && count($request->medicinas) > 0) ? json_encode($request->medicinas) : null;
        $medical_record->disorders = ($request->filled('desordenes') && count($request->desordenes) > 0) ? json_encode($request->desordenes) : null;
        $medical_record->civil_status = ($request->filled('estado_civil')) ? $request->estado_civil : 'Soltero/a';
        $medical_record->height = ($request->filled('estatura')) ? $request->estatura : $medical_record->height;
        $medical_record->consumption_record = ($request->filled('registro_consumo')) ? $request->registro_consumo : $medical_record->consumption_record;

        if ($request->filled('actividad_fisica_id')) {
            $subcategory = Subcategory::find($request->actividad_fisica_id);
            $medical_record->physical_activity = $subcategory->id;
        }
        if ($request->filled('objetivo_id')) {
            $subcategory = Subcategory::find($request->objetivo_id);
            $medical_record->objective = $subcategory->id;
        }

        $consultorio = null;
        if ($request->has('consultorio_id')) {
            $consultorio = Room::find($request->consultorio_id);
            if ($consultorio == null) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Consultorio que estas tratando de asignar no existe.',
                    'data' => null
                ]);
            }
        }

        $nutricionista = null;
        if ($request->has('nutricionista_id')) {
            $nutricionista = User::role('Nutricionista')->where('id',$request->nutricionista_id)->first();
            if ($nutricionista == null) {
                return response()->json([
                    'code' => 404,
                    'msg' => 'Nutricionista que estas tratando de asignar no existe.',
                    'data' => null
                ]);
            }
        }

        $user->nutricionist_id = ($request->has('nutricionista_id') ? $nutricionista->id : null);
        $user->room_id = ($request->has('consultorio_id') ? $consultorio->id : null);
        //Check if there is a file on the request
        $paths = array();
        if ($request->has('archivos')) {
            foreach ($request->archivos as $archivo) {
                $path = Storage::disk('public')->put('files', $archivo, 'public');
                array_push($paths, $path);
            }
            //We need to extract preview files in order to not lose them
            if ($user->files != null && $user->files != '' && count(json_decode($user->files)) >= 1) {
                $paths = array_merge(json_decode($user->files), $paths);
            }
            $user->files = json_encode($paths);
        }
        
        $user->update();
        $medical_record->civil_status = ($request->filled('estado_civil')) ? $request->estado_civil : $medical_record->civil_status;

        if ($request->filled('consumo_alcohol_id')) {
            $subcategory = Subcategory::find($request->consumo_alcohol_id);
            $medical_record->alcohol_consumption = $subcategory->id;
        }

        if ($request->filled('tipo_fumador_id')) {
            $subcategory = Subcategory::find($request->tipo_fumador_id);
            $medical_record->smoke = $subcategory->id;
        }

        if ($request->filled('consumo_agua_id')) {
            $subcategory = Subcategory::find($request->consumo_agua_id);
            $medical_record->water_consumption = $subcategory->id;
        }

        if ($request->filled('nivel_estres_id')) {
            $subcategory = Subcategory::find($request->nivel_estres_id);
            $medical_record->stress = $subcategory->id;
        }

        if ($request->filled('horas_dormidas')) {
            $subcategory = Subcategory::find($request->horas_dormidas);
            $medical_record->hours_of_sleep = $subcategory->id;
        }

        $medical_record->save();
        

        $periodo = 1;

        if ($request->filled('periodo_id')) {
            $paypal_catalogo = Paypal::find((int) $request->periodo_id);
            if ($paypal_catalogo != null) {
                $periodo = $paypal_catalogo->months;
            }
        }

        $suscripcion = Suscription::where('user_id', $user->id)->first();
        if ($suscripcion == null) {
            $suscripcion = new Suscription();
            $suscripcion->start_date = date('Y-m-d');
            $suscripcion->end_date = date('Y-m-d', strtotime('+' . $periodo . ' month'));
            $suscripcion->user_id = $user->id;
            $suscripcion->save();
        } else {
            $suscripcion->end_date = date('Y-m-d', strtotime('+' . $periodo . ' month'));
            $suscripcion->user_id = $user->id;
            $suscripcion->save();
        }



        return response()->json([
            'code' => 201,
            'msg' => 'Usuario actualizado correctamente.',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/nutricionista",
     *     summary="Crear nutricionista",
     *     operationId="createNutricionista",
     *     tags={"Nutricionista"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *              @OA\Property(
     *                  property="rol",
     *                  type="integer"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Nutricionista created"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function storeNutricionista (Request $request)
    {
        $rules = [
            'nombre' => 'required',
            'email' => 'required|email',
            'rol' => 'required',
        ];

        $messages = [
            'nombre.required' => 'Es necesario ingresar un nombre para el usuario',
            'email.required' => 'Es necesario indicar un email para poder contactarlo',
            'email.email' => 'Formato incorrecto de email',
            'rol.required' => 'Es necesario indicar un rol',
        ];
        //quitar campos no necesarios

        $this->validate($request, $rules, $messages);

        $password = Str::random(8);

        $user = new User();
        $user->name = $request->nombre;
        $user->first_lastname = ($request->filled('apellido_paterno')) ? $request->apellido_paterno :null;
        $user->second_lastname = ($request->filled('apellido_materno')) ? $request->apellido_materno :null;
        $user->sex = ($request->filled('sexo')) ? $request->sexo : 'F';
        $user->password = Hash::make($password);
        $user->email = $request->email;
        $user->phone = ($request->filled('telefono')) ? $request->telefono :'000000000';
        $user->birthday =($request->filled('fecha_nacimiento')) ? $request->fecha_nacimiento : now();
        $user->rol = $request->rol;
        $user->nutricionist_id = null;
        $user->room_id = null;
        $user->dni = null;
        $user->residence = null;
        $user->profesion = null;
        $user->save();

        $user->assignRole($request->rol);

        $token = $user->createToken('user')->plainTextToken;

        return response()->json([
            'code' => 200,
            'msg' => 'Nutricionista creado con éxito.',
            'data' => $user,
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/nutricionista/{user}",
     *     summary="actualizar nutricionista",
     *     operationId="updateNutricionista",
     *     tags={"Nutricionista"},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of Nutricionista",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="nombre",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *              @OA\Property(
     *                  property="rol",
     *                  type="integer"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Nutricionista updated"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function updateNutricionista (Request $request, User $user)
    {
        $rules = [
            'nombre' => 'required',
            'email' => 'required|email',
            'rol' => 'required',
        ];

        $messages = [
            'nombre.required' => 'Es necesario ingresar un nombre para el usuario',
            'email.required' => 'Es necesario indicar un email para poder contactarlo',
            'email.email' => 'Formato incorrecto de email',
            'rol.required' => 'Es necesario indicar un rol',
        ];
        //quitar campos no necesarios

        $this->validate($request, $rules, $messages);

        $password = Str::random(8);

        $user->name = $request->nombre;
        $user->first_lastname = ($request->has('apellido_paterno')) ? $request->apellido_paterno : $user->first_lastname;
        $user->second_lastname = ($request->has('apellido_materno')) ? $request->apellido_materno : $user->second_lastname;
        $user->sex = ($request->has('sexo')) ? $request->sexo : 'F';
        $user->password = Hash::make($password);
        $user->email = $request->email;
        $user->phone = ($request->has('telefono')) ? $request->telefono : $user->phone;
        $user->birthday = ($request->has('fecha_nacimiento')) ? $request->fecha_nacimiento : $user->birthday;
        $user->rol = $request->rol;
        $user->nutricionist_id = null;
        $user->room_id = null;
        $user->dni = null;
        $user->profesion = null;
        $user->residence = null;
        $user->update();

        $user->assignRole($request->rol);

        $token = $user->createToken('user')->plainTextToken;

        return response()->json([
            'code' => 200,
            'msg' => 'Nutricionista actualizada con éxito.',
            'data' => $user,
            'token' => $token
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/users",
     *     summary="Mostrando clientes, nutricionistas y administradores",
     *     operationId="showUsers",
     *     tags={"Nutricionista"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing personal information of Client"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function showAll()
    {
        $users = User::role(['Nutricionista', 'Admin'])->get();

        if ($users->isEmpty()) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se encontraron usuarios.',
                'data' => null
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg' => 'Usuarios obtenidos con éxito.',
            'data' => $users
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/delete/user/{user}",
     *     summary="Delete user",
     *     operationId="deleteUser",
     *     tags={"User"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of user",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Add Clients to the Consultorio requested"),
     *     @OA\Response(response=403, description="User cant be assign to any Consultorio"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */

    public function delete(User $user) {

        $user_deleted = "Paciente";
        // Checamos si el usuario que se va a eliminar es un nutricionista
        if ($user->hasRole('Nutricionista')) {
            //Antes de borrar al usuario debemos de asignar los pacientes en caso de tenerlos a otro nutricionista
            $pacientes = User::where('nutricionist_id', '=', $user->id)->get();

            if (!$pacientes->isEmpty()) {
                //En caso de tener pacientes asignados buscamos al nutriologo mas proximo para asignarle los pacientes
                $backup = null;
                $nutriologos = User::role('Nutricionista')->get();
                foreach ($nutriologos as $key => $nutriologo) {
                    if ($nutriologo->id != $user->id) {
                        $backup = $nutriologo;
                        break;
                    }
                }

                if ($backup == null) {
                    return response()->json([
                        'code' => 500,
                        'msg' => 'No se puede elimnar al Nutriolog@ por que es el unico registrado y los pacientes necesitan estar asigando a un nutriolog@',
                        'data' => null
                    ]);
                } else {
                    foreach ($pacientes as $key => $paciente) {
                        $paciente->nutricionist_id = $backup->id;
                        $paciente->save();
                    }
                }
            }

            $user_deleted = "Nutriolog@";
  
        } else if ($user->hasRole('Usuario')) {
            $citas = MedicalRecord::where('user_id', $user->id)->get();
            if (!$citas->isEmpty()) {
                foreach ($citas as $key => $cita) {
                    $cita->delete();
                }
            }

            $suscripciones = Suscription::where('user_id',$user->id)->get();
            if (!$suscripciones->isEmpty()) {
                foreach ($suscripciones as $key => $suscripcion) {
                    $suscripcion->delete();
                }
            }
        }

        $user->delete();

        return response()->json([
            'code' => 200,
            'msg' => $user_deleted .' eliminado correctamente',
            'data' => null
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/check/user",
     *     summary="Check user if exist on DB",
     *     operationId="checkUser",
     *     tags={"User"},
     *     security={ {"sanctum": {} }},
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Add Clients to the Consultorio requested"),
     *     @OA\Response(response=403, description="User cant be assign to any Consultorio"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function checkUser(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $messages = [
            'email.required' => 'El mail es un campo requerido',
            'email.email' => "El campo debe de ser formato tipo email"
        ];

        $this->validate($request, $rules, $messages);

        $data = null;
        $msg = 'Usuario no encontrado';

        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            $data = $user;
            $msg = "Usuario encontrado";
        }

        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    public function uploadFiles(Request $request, User $user) {

        $rules = ['archivos' => 'required'];
        $messages = ['archivos.required' => 'Es necesario subir archivos'];

        $this->validate($request, $rules, $messages);

        $data = null;
        $msg = 'Usario no permitido para subir imagenes';

        if ($user->hasRole('Usuario')) {
            //Check if there is a file on the request
            $paths = array();
            if ($request->has('archivos')) {
                foreach ($request->archivos as $archivo) {
                    $filename = $archivo->getClientOriginalName();
                    $path = $archivo->storeAs('files', $filename, 'public');
                    //$path = Storage::disk('public')->put('files', $archivo, 'public');
                    array_push($paths, $path);
                }
                //We need to extract preview files in order to not lose them
                if ($user->files != null && $user->files != '' && count(json_decode($user->files)) >= 1) {
                    $paths = array_merge(json_decode($user->files), $paths);
                }
                $user->files = json_encode($paths);
                $msg = "Se agregaron los archivos exitosamente.";
            }
            
            $user->update();

            if ($user->files != null && $user->files != 'null' && count(json_decode($user->files)) > 0) {
                foreach (json_decode($user->files) as $file) {
                    $data['archivos'][] = $this->host . Storage::url($file);
                }
            }
        }

        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $data
        ]);

    }
}
