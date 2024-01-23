<?php

namespace App\Http\Controllers\API\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\User;

class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/consultorios",
     *     summary="Obtener los consultorios",
     *     operationId="showConsultorios",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Showing consultorios"),
     *     @OA\Response(response=404, description="No consultorios found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function index() {
        $consultorios = Room::all();
        if ($consultorios != null) {
            return response()->json([
                'code' => 200,
                'msg' => 'Consultorios encontrados.',
                'data' => $consultorios
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'No se encontraron consultorios.',
                'data' => null
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/show/consultorio/{consultorio}",
     *     summary="Obtener el consultorio solicitado",
     *     operationId="showConsultorio",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="consultorio",
     *         in="path",
     *         description="Id of consultorio",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing consultorio requested"),
     *     @OA\Response(response=404, description="No consultorio found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show(Room $consultorio) {
        return response()->json([
            'code' => 200,
            'msg' => 'Consultorio encontrado.',
            'data' => $consultorio
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/create/consultorio",
     *     summary="Crear consultorio",
     *     operationId="createConsultorio",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
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
     *                  property="direccion",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="telefono",
     *                  type="string"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Consultorio created"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required',
            'telefono' => 'required',
            'direccion' => 'required'
        ];

        $messages = [
            'nombre.required' => 'Es necesario agregar un nombre al consultorio',
            'telefono.required' => 'Es necesario agregar un telefono al consultorio',
            'direccion.required' => 'Es necesario agregar una direccion al consultorio',
        ];

        $this->validate($request, $rules, $messages);

        $consultorio = new Room();
        $consultorio->name = $request->nombre;
        $consultorio->address = $request->direccion;
        $consultorio->phone = $request->telefono;
        $consultorio->lat = ($request->filled('lat')) ? $request->lat : null;
        $consultorio->lng = ($request->filled('lng')) ? $request->lng : null;
        $consultorio->cp = ($request->filled('cp')) ? $request->cp : null;
        $consultorio->state = ($request->filled('state')) ? $request->state : null;
        $consultorio->country = ($request->filled('country')) ? $request->country : null;
        $consultorio->save();

        return response()->json([
            'code' => 200,
            'msg' => 'Consultorio creado.',
            'data' => $consultorio
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/update/consultorio/{consultorio}",
     *     summary="Actualizar informacion del consultorio",
     *     operationId="updateConsultorio",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="consultorio",
     *         in="path",
     *         description="Id of consultorio",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     *                  property="direccion",
     *                  type="string"
     *               ),
     *               @OA\Property(
     *                  property="telefono",
     *                  type="string"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Consultorio data updated"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
    */
    public function update(Request $request, Room $consultorio)
    {
        $rules = [
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required'
        ];

        $messages = [
            'nombre.required' => 'Es necesario indicar el nombre del consultorio',
            'direccion.required' => 'Es necesario indicar la direccion del consultorio',
            'telefono.required' => 'Es necesario indicar el telefono del consultorio'
        ];

        $this->validate($request, $rules, $messages);

        $consultorio->name = $request->nombre;
        $consultorio->address = $request->direccion;
        $consultorio->phone = $request->telefono;
        $consultorio->lat = ($request->filled('lat')) ? $request->lat : null;
        $consultorio->lng = ($request->filled('lng')) ? $request->lng : null;
        $consultorio->cp = ($request->filled('cp')) ? $request->cp : null;
        $consultorio->state = ($request->filled('state')) ? $request->state : null;
        $consultorio->country = ($request->filled('country')) ? $request->country : null;
        $consultorio->update();

        return response()->json([
            'code' => 200,
            'msg' => 'El consultorio se actualizo correctamente',
            'data' => $consultorio
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/consultorio/{consultorio}/clientes",
     *     summary="Obtener clientes del consultorio",
     *     operationId="getClientesByConsultorioId",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="consultorio",
     *         in="path",
     *         description="Id of consultorio",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Showing Clients of the Consultorio requested"),
     *     @OA\Response(response=404, description="No clients found on the Consultorio"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function getClientes(Room $consultorio)
    {
        $msg = null;
        $data = null;
        $code = 500;
        $clientes = User::role('Usuario')->where('consultorio_id', $consultorio->id)->get();
        if (!$clientes->isEmpty()) {
            $code = 200;
            $msg = 'Obteniendo ' . count($clientes) . ' clientes que pertenecen al consultorio: ' . $consultorio->name;
            $data = $clientes;
        } else {
            $code = 404;
            $msg = 'No se encontraron clientes suscritos a este consultorio';
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/consultorio/{consultorio}/add-cliente/{user}",
     *     summary="Agregar cliente al consultorio",
     *     operationId="addClienteByConsultorioId",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="consultorio",
     *         in="path",
     *         description="Id of consultorio",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\MediaType(mediaType="multipart/form-data"),
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="email",
     *                  type="email"
     *               )
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
    public function addCliente(Request $request, Room $consultorio, User $encargado)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $messages = [
            'email.required' => 'Es necesario indicar el email del usuario',
            'email.email' => 'El email no es valido'
        ];

        $this->validate($request, $rules, $messages);

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No se encontro ningun usuario con el email: ' . $request->email,
                'data' => null
            ]);
        }

        $code = 500;
        $msg = null;
        $data = null;

        if ($encargado->hasRole(['Admin', 'Nutricionista']) && $user->hasRole('Usuario')) {
            //In case  the patient already exist in another room, we need to remove it first
            $user->room_id = $consultorio->id;
            $user->save();
            $code = 200;
            $msg = 'Ahora el cliente pertenece al consultorio ' . $consultorio->name;
            $data = [
                'consultorio' => $consultorio,
                'cliente' => $user
            ];

        } else {
            $code = 403;
            $msg = 'Este usuario no se puede asignar a un consultorio';
        }

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/consultorio/{consultorio}",
     *     summary="Delete consultorio",
     *     operationId="deleteConsultorioId",
     *     tags={"Consultorio"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="consultorio",
     *         in="path",
     *         description="Id of consultorio",
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
    public function delete(Room $consultorio) {
        $data = null;
        // necesitamos verificar antes de borrar el consultorio si tiene pacientes asignados
        $clientes = User::role('Usuario')->where('consultorio_id', $consultorio->id)->get();
        if (!$clientes->isEmpty()){
            // Asignamos los pacientes a otro consultorio
            $backup = Room::where('id', '!=', $consultorio->id)->first();
            foreach ($clientes as $cliente) {
                $cliente->consultorio_id = $backup->id;
                $cliente->save();
            }

            $data = 'Hubo ' . count($clientes) . ' que se asignaron al siguiente consultorio: ' . $backup->name . ' para poder eliminarlo';
        }

        $consultorio->delete();

        return response()->json([
            'code' => 200,
            'msg' => 'El consultorio se elimino correctamente',
            'data' => $data
        ]);
    }
}
