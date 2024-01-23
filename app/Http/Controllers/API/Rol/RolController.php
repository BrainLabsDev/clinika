<?php

namespace App\Http\Controllers\API\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/set/nutricionista/{user}",
     *     summary="Asignando el rol de nutricionista a un usuario",
     *     operationId="setNutricionista",
     *     tags={"roles"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="Id of User",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Setting user as nutricionista rol"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function setNutricionista(User $user)
    {
        $user->removeRole('Usuario');
        $user->assignRole('Nutricionista');
        $user->rol = 'Nutricionista';
        $user->nutricionista_id = null;
        $user->update();
        return response()->json([
            'code' => 200,
            'msg' => 'El usuario se ha asignado como nutricionista',
            'data' => $user,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show/roles",
     *     summary="Mostrando los roles disponibles",
     *     operationId="showRoles",
     *     tags={"roles"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="Setting user as nutricionista rol"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function show()
    {
        $roles = Role::all();

        return response()->json([
            'code' => 200,
            'msg' => 'Mostrando todos los roles',
            'data' => $roles,
        ]);
    }
}
