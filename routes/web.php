<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;




use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::get('/checkout',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class,'processCheckout'])->name('front.processCheckout');


Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem.cart');

Route::get('/register',[AuthController::class,'register'])->name('account.register');
Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
Route::get('/login',[AuthController::class,'login'])->name('account.login');
Route::post('/login',[AuthController::class,'authenticate'])->name('account.authenticate');
Route::get('/profile',[AuthController::class,'dashboard'])->name('account.profile');
Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');




Route::group(['prefix'=>'account'],function(){
 Route::group(['middleware' => 'guest'],function(){
        // Route::get('/login',[AuthController::class,'login'])->name('account.login');
        // Route::get('/register',[AuthController::class,'register'])->name('account.register');
        // Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');

    });
    Route::group(['middleware' => 'auth'],function(){
        Route::get('/profile',[AuthController::class,'dashboard'])->name('account.profile');

    });
   



 });
Route::group(['prefix'=>'admin'],function(){

    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');


    });
    Route::group(['middleware'=>'admin.auth'],function(){
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // category Routes
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories/create',[CategoryController::class,'store'])->name('categories.store');

        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');


        // sub Category Routes
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');

        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subcategory}/edit',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subcategory}',[CategoryController::class,'destroy'])->name('sub-categories.delete');


        //temp-images.create
        Route::post('/upload-temp-image',[TempImagesController ::class,'create'])->name('temp-images.create');

        // Brand Routes
        Route::get('/brands/create',[BrandsController::class,'create'])->name('brands.create');
        Route::post('/brands',[BrandsController::class,'store'])->name('brands.store');
        Route::get('/brands',[BrandsController::class,'index'])->name('brands.index');
        Route::get('/brands/{brands}/edit',[BrandsController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brands}/edit',[BrandsController::class,'update'])->name('brands.update');
        Route::delete('/brands/{brands}',[BrandsController::class,'destroy'])->name('brands.delete');

        // products routes
        Route::get('/product/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/products',[ProductController::class,'store'])->name('products.store');
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/{products}/edit',[productController::class,'edit'])->name('products.edit');



        Route::get('/products-subcategories',[ProductSubCategoryController::class,'index'])->name('products-subcategories.index');




        
        Route::get('/getSlug',function(Request $request){
            $slug ='';
            if(!empty($request->title)){
                $slug=Str::slug($request->title);

            }
            return response()->json([
                'status' =>true,
                'slug'=>$slug
            ]);

        })->name('getSlug');

        
    });

});