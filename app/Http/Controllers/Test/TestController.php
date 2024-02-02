<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index()
    {
        $response = Http::post('https://phplaravel-1203103-4252935.cloudwaysapps.com/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin01',
        ]);

        $result = $response->json();

        dd($result);
    }
}
