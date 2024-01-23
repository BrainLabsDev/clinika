<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Mail\PasswordReset;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login user",
     *     operationId="loginUser",
     *     tags={"auth"},
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
     *               @OA\Property(
     *                  property="password",
     *                  type="string"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="User logged"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->getRoleNames()->isEmpty()) {
                if (Auth::user()->email == 'admin@gmail.com') {
                    Auth::user()->assignRole('SuperAdmin');
                    Auth::user()->rol = 'SuperAdmin';
                    Auth::user()->update();
                }
            }

            Auth::user()->tokens()->delete();
            $token = Auth::user()->createToken('user');
            return response()->json([
                'code' => 200,
                'msg' => 'Usuario logueado exitosamente',
                'data' => [
                    'user' => Auth::user(),
                    'token' => $token->plainTextToken
                ]
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'Usuario no encontrado',
                'data' => null
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Logout user",
     *     operationId="logoutUser",
     *     tags={"auth"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(response=200, description="User logged out"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'code' => 200,
            'msg' => 'Usuario deslogueado exitosamente',
            'data' => null
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/recover/password",
     *     summary="Reset password",
     *     operationId="resetPassword",
     *     tags={"auth"},
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
     *     @OA\Response(response=200, description="User logged"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function recoverPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $messages = [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe ser válido'
        ];

        $request->validate($rules, $messages);

        $user = User::where('email', $request->email)->first();

        if ($user != null) {

            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->update();

            Mail::to($user->email)->send(new PasswordReset($user, $password));
            return response()->json([
                'code' => 200,
                'msg' => 'Se ha enviado un correo para recuperar la contraseña',
                'data' => null
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'Usuario no encontrado',
                'data' => null
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/recover/custom-password",
     *     summary="Reset custom password",
     *     operationId="resetCustomPassword",
     *     tags={"auth"},
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
     *              @OA\Property(
     *                  property="password",
     *                  type="text"
     *               )
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="User logged"),
     *     @OA\Response(response=422, description="Validation rules failed"),
     *     @OA\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function customPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $messages = [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe ser válido',
            'password.required' => 'Es requerida una password'
        ];

        $request->validate($rules, $messages);

        $user = User::where('email', $request->email)->first();

        if ($user != null) {
            $user->password = Hash::make($request->password);
            $user->update();

            Mail::to($user->email)->send(new PasswordReset($user, $request->password));
            return response()->json([
                'code' => 200,
                'msg' => 'Se ha enviado un correo con su nueva contraseña',
                'data' => null
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'msg' => 'Usuario no encontrado',
                'data' => null
            ]);
        }
    }
}
