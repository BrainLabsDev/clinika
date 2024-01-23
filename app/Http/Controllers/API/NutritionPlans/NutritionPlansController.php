<?php

namespace App\Http\Controllers\API\NutritionPlans;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appoinment;

class NutritionPlansController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
        ];

        $messages = [
            'user_id.required' => 'El campo user_id es obligatorio',
            'user_id.integer' => 'El campo user_id debe ser un número entero',
        ];

        $this->validate($request, $rules, $messages);

        $user = User::find($request->user_id);

        $cita = Appoinment::where('cliente_id', $user->id)->latest()->first();
        if ($cita == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay citas de control registradas para este usuario',
                'data' => null
            ], 404);
        }

        if ($cita->equivalenciaNutricional == null) {
            return response()->json([
                'code' => 404,
                'msg' => 'No hay equivalencias nutricionales registradas para este usuario',
                'data' => null
            ], 404);
        }

        $plan_desayuno = (array) json_decode($cita->equivalenciaNutricional->desayuno);
        $media_mañana = (array) json_decode($cita->equivalenciaNutricional->media_mañana);
        $almuerzo = (array) json_decode($cita->equivalenciaNutricional->almuerzo);
        $media_tarde = (array) json_decode($cita->equivalenciaNutricional->media_tarde);
        $cena = (array) json_decode($cita->equivalenciaNutricional->cena);
        $merienda = (array) json_decode($cita->equivalenciaNutricional->merienda_noche);
        $cont = 0;
        $test = ', y excluyendo aquellos alimentos que puedan perjudicar al paciente debido a sus alergias {alergias} o condiciones médicas {condiciones_medicas}';
        $prompt = 'Generar 3 opciones diferentes de desayuno, almuerzo y cena  utilizando las equivalencias nutricionales: '.PHP_EOL.'Desayuno: ';
        foreach ($plan_desayuno as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($plan_desayuno)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }
        $cont = 0;
        $prompt = $prompt . PHP_EOL . 'Media mañana: ';
        foreach ($media_mañana as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($media_mañana)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }

        $cont = 0;
        $prompt = $prompt . PHP_EOL . 'Almuerzo: ';
        foreach ($almuerzo as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($almuerzo)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }

        $cont = 0;
        $prompt = $prompt . PHP_EOL . 'Media tarde: ';
        foreach ($media_tarde as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($media_tarde)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }

        $cont = 0;
        $prompt = $prompt . PHP_EOL . 'Cena: ';
        foreach ($cena as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($cena)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }

        $cont = 0;
        $prompt = $prompt . PHP_EOL . 'Merienda: ';
        foreach ($merienda as $key => $item) {
            if ((int) $item != 0){
                $alimento = str_replace("'", "", $key);
                $prompt = $prompt . $item .' '. $alimento;
                if (($cont + 1) == count($merienda)) {
                    $prompt = $prompt . '. ';
                } else {
                    $prompt = $prompt . ', ';
                }
            }
            $cont++;
        }

        $prompt = $prompt . PHP_EOL .'Para garantizar que cada opción cumpla exactamente con la distribución de nutrientes establecida en la equivalencia de alimentos para cada tiempo de comida';
        if ($user->alergias != null || json_decode($user->alergias) > 0) {

            $prompt = $prompt . ' y excluyendo aquellos alimentos que puedan perjudicar al paciente debido a sus alergias como lo son ';
            $count = 0;

            foreach (json_decode($user->alergias) as $key => $alergia) {
                if ($count == 0) {
                    $prompt = $prompt . $alergia;
                } else {
                    $prompt = $prompt . ', ' . $alergia;
                }
                $count++;
            }
        }
        if ($user->condiciones_medicas != null || json_decode($user->condiciones_medicas) > 0) {
            if ($user->alergias != null) {
                $prompt = $prompt . ' o condiciones médicas como lo son ';
            } else {
                $prompt = $prompt . ' y excluyendo condiciones médicas como lo son ';
            }

            $count = 0;

            foreach (json_decode($user->condiciones_medicas) as $key => $condicion_medica) {
                if ($count == 0) {
                    $prompt = $prompt . $condicion_medica;
                } else {
                    $prompt = $prompt . ', ' . $condicion_medica;
                }
                $count++;
            }
        }

        //$url = 'https://api.openai.com/v1/completions';
        $url = 'https://api.openai.com/v1/chat/completions';
        $token = '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'; // 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 , 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
        $data = [
            'model'=> 'gpt-3.5-turbo-0301',
            'messages'=> [[
                    'role' => 'user',
                    'content' => $prompt
            ]]
        ];

        /*$data = [
            'model' => 'gpt-3.5-turbo',
            'prompt' => $prompt,
            "temperature" => 0.3,
            "max_tokens" => 3700,
            "top_p" => 1.0,
            "frequency_penalty" => 0.0,
            "presence_penalty" => 0.0
        ];*/

        $response = Http::withToken($token)->accept('application/json')->post($url, $data);

        $result = $response->json();

        if (isset($result['error'])) {
            return response()->json([
                'code' => 500,
                'msg' => 'error',
                'data' => $result['error']
            ], 500);
        }
        if (isset($result['choices']['text'])) {
            $text = $result['choices'][0]['text'];
            return response()->json([
                'code' => 200,
                'msg' => 'success',
                'data' => [
                    'prompt' => $prompt,
                    'result' => $text
                ]
            ], 200);
        }

        if (isset($result['choices'][0]['message']['content'])) {
            $text = $result['choices'][0]['message']['content'];
            return response()->json([
                'code' => 200,
                'msg' => 'success',
                'data' => [
                    'prompt' => $prompt,
                    'result' => $text
                ]
            ], 200);
        }


    }
}
