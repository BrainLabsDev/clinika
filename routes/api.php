<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Rol\RolController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Appoinment\AppoinmentController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\Room\RoomController;
use App\Http\Controllers\API\Suscription\SuscriptionController;
use App\Http\Controllers\API\Subcategory\SubcategoryController;
use App\Http\Controllers\API\NutritionalEquivalent\NutritionalEquivalentController;
use App\Http\Controllers\API\NutritionPlans\NutritionPlansController;
use App\Http\Controllers\API\PhysicalActivity\PhysicalActivityController;
use App\Http\Controllers\API\Objective\ObjectiveController;
use App\Http\Middleware\EnsureSuscriptionIsValid;
use App\Http\Controllers\API\Paypal\PaypalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware([EnsureSuscriptionIsValid::class])->post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/recover/password', [LoginController::class, 'recoverPassword']);
Route::post('/recover/custom-password', [LoginController::class, 'customPassword']);
Route::post('/create/user/',[UserController::class, 'store']);

Route::get('/paypal/catalogo', [PaypalController::class, 'index']);


Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->get('/show/users', [UserController::class, 'showAll']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->get('/show/clientes', [UserController::class, 'showClientes']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista|Usuario'])->post('/update/user/{user}', [UserController::class, 'update']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->post('/check/user',[UserController::class, 'checkUser']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->post('/create/nutricionista',[UserController::class, 'storeNutricionista']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->post('/update/nutricionista/{user}',[UserController::class, 'updateNutricionista']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista|Usuario'])->get('/show/nutricionistas', [UserController::class, 'showNutricionistas']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista|Usuario'])->get('/show/user/{user}', [UserController::class, 'show']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->delete('/delete/user/{user}', [UserController::class, 'delete']);
Route::middleware(['auth:sanctum'])->get('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/consultorios', [RoomController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/show/consultorio/{consultorio}', [RoomController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin'])->post('/create/consultorio', [RoomController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/consultorio/{consultorio}', [RoomController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/consultorio/{consultorio}/clientes', [RoomController::class, 'getClientes']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/consultorio/{consultorio}/add-cliente', [RoomController::class, 'addCliente']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->delete('/consultorio/{consultorio}', [RoomController::class, 'delete']);

Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin'])->get('/set/nutricionista/{user}', [RolController::class, 'setNutricionista']);
Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin|Nutricionista'])->get('/show/roles', [RolController::class, 'show']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/cita-control/{user}', [AppoinmentController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show-history/cita-control/{user}', [AppoinmentController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/cita-control', [AppoinmentController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/cita-control/{cita}', [AppoinmentController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->delete('/eliminar/cita-control/{cita}', [AppoinmentController::class, 'deleteCita']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/show/suscripcion/{user}', [SuscriptionController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/disable/suscripcion/{user}', [SuscriptionController::class, 'disable']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/enable/suscripcion/{user}', [SuscriptionController::class, 'enable']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/categorias', [CategoryController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/categoria/{categoria}', [CategoryController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/categoria/{categoria}/subcategorias', [CategoryController::class, 'showSubcategorias']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/categoria/{categoria}/productos', [CategoryController::class, 'showProductos']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/categoria', [CategoryController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/categoria/{categoria}', [CategoryController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin', EnsureSuscriptionIsValid::class])->post('/import/categorias', [CategoryController::class, 'import']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/subcategorias', [SubcategoryController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/subcategoria/{subcategoria}', [SubcategoryController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/subcategoria', [SubcategoryController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/subcategoria/{subcategoria}', [SubcategoryController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario'])->get('/show/subcategoria/{subcategoria}/productos', [SubcategoryController::class, 'showProductos']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin'])->post('/import/subcategorias', [SubcategoryController::class, 'import']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/productos', [ProductController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/producto/{producto}', [ProductController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/producto', [ProductController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/producto/{producto}', [ProductController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin'])->post('/import/productos', [ProductController::class, 'import']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->delete('/delete/producto/{producto}', [ProductController::class, 'delete']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/equivalencia-nutricional', [NutritionalEquivalentController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/equivalencia-nutricional/{equivalenciaNutricional}', [NutritionalEquivalentController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/equivalencias-nutricionales/{citaControl}', [NutritionalEquivalentController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show/equivalencia-nutricional/{equivalenciaNutricional}', [NutritionalEquivalentController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario', EnsureSuscriptionIsValid::class])->get('/show-last/equivalencia-nutricional/{user}', [NutritionalEquivalentController::class, 'showLast']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista|Usuario'])->get('/eliminar/equivalencia/{equivalenciaNutricional}', [NutritionalEquivalentController::class, 'deleteEquivalencia']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/plan-nutricional', [NutritionPlansController::class, 'index']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/show/actividades-fisicas', [PhysicalActivityController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/show/actividad-fisica/{$actividadFisica}', [PhysicalActivityController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/actividad-fisica', [PhysicalActivityController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->patch('/actualizar/actividad/{$id}', [PhysicalActivityController::class, 'actualizar']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->delete('/delete/actividad-fisica/{$actividadFisica}', [PhysicalActivityController::class, 'delete']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/show/objetivos', [ObjectiveController::class, 'index']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista', EnsureSuscriptionIsValid::class])->get('/show/objetivo/{$objetivo}', [ObjectiveController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/create/objetivo', [ObjectiveController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/update/objetivo/{$objetivo}', [ObjectiveController::class, 'update']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/delete/objetivo/{$objetivo}', [ObjectiveController::class, 'delete']);

Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->post('/paypal/catalogo', [PaypalController::class, 'store']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->get('/paypal/catalogo/{id}', [PaypalController::class, 'show']);
Route::middleware(['auth:sanctum','role:SuperAdmin|Admin|Nutricionista'])->delete('/delete-paypal/catalogo/{id}', [PaypalController::class, 'delete']);

