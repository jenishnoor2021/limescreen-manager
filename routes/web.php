<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminBranchController;
use App\Http\Controllers\AdminPackageController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('auth.login');
})->name('admin.login');


Route::get('/show/{slug}', [AdminCustomerController::class, 'showData'])->name('show.data');
Route::post('/send-otp', [AdminCustomerController::class, 'sendOtpApiCall']);
Route::post('/verify-otp', [AdminCustomerController::class, 'verifyOtp']);

//  for admin registration below comment uncomment karvi and above auth.login ne comment karvi
// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();

// Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'usersession']], function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin');

    Route::get('/profile/{id}', [AdminController::class, 'profiledit'])->name('profile.edit');
    Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');

    Route::get("admin/branches", [AdminBranchController::class, 'index'])->name('admin.branches.index');
    Route::get('admin/branches/show/{id}', [AdminBranchController::class, 'show'])->name('admin.branches.show');
    Route::get('admin/branches/create', [AdminBranchController::class, 'create'])->name('admin.branches.create');
    Route::post('admin/branches/store', [AdminBranchController::class, 'store'])->name('admin.branches.store');
    Route::get('admin/branches/edit/{id}', [AdminBranchController::class, 'edit'])->name('admin.branches.edit');
    Route::patch('admin/branches/update/{id}', [AdminBranchController::class, 'update'])->name('admin.branches.update');
    Route::get('admin/branches/destroy/{id}', [AdminBranchController::class, 'destroy'])->name('admin.branches.destroy');

    Route::get("admin/users", [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users/store', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/edit/{id}', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::patch('admin/users/update/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::get('admin/users/destroy/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::post("admin/users/active", [AdminController::class, 'accountActive'])->name('admin.users.active');

    Route::get("admin/customers", [AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('admin/customers/show/{id}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
    Route::get('admin/customers/create', [AdminCustomerController::class, 'create'])->name('admin.customers.create');
    Route::post('admin/customers/store', [AdminCustomerController::class, 'store'])->name('admin.customers.store');
    Route::get('admin/customers/edit/{id}', [AdminCustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::patch('admin/customers/update/{id}', [AdminCustomerController::class, 'update'])->name('admin.customers.update');
    Route::get('admin/customers/destroy/{id}', [AdminCustomerController::class, 'destroy'])->name('admin.customers.destroy');
    Route::post('admin/customers/bulk-delete', [AdminCustomerController::class, 'bulkDelete'])->name('admin.customers.bulkDelete');

    Route::get('/get-users-by-branch/{branch_id}', [AdminCustomerController::class, 'getUsersByBranch']);
    Route::get('/admin/report', [AdminCustomerController::class, 'report'])->name('admin.report');
    Route::get('/admin/export/show', [AdminCustomerController::class, 'exportShow'])->name('admin.export.show');

    Route::get('/payments/{customerId}', [AdminPaymentController::class, 'getByCustomer']);
    Route::get('/payments/edit/{id}', [AdminPaymentController::class, 'edit']);
    Route::post('/payments/save', [AdminPaymentController::class, 'save']);
    Route::delete('/payments/delete/{id}', [AdminPaymentController::class, 'delete']);

    Route::get("admin/package", [AdminPackageController::class, 'index'])->name('admin.package.index');
    Route::get('admin/package/show/{id}', [AdminPackageController::class, 'show'])->name('admin.package.show');
    Route::get('admin/package/create', [AdminPackageController::class, 'create'])->name('admin.package.create');
    Route::post('admin/package/store', [AdminPackageController::class, 'store'])->name('admin.package.store');
    Route::get('admin/package/edit/{id}', [AdminPackageController::class, 'edit'])->name('admin.package.edit');
    Route::patch('admin/package/update/{id}', [AdminPackageController::class, 'update'])->name('admin.package.update');
    Route::get('admin/package/destroy/{id}', [AdminPackageController::class, 'destroy'])->name('admin.package.destroy');

    Route::get('/admin/payment-report', [AdminCustomerController::class, 'paymentReport'])->name('admin.payment.report');
    Route::get('/admin/payment-export/show', [AdminCustomerController::class, 'paymentExportShow'])->name('admin.payment.export.show');

    Route::get('/admin/client-payment-report', [AdminCustomerController::class, 'clientPaymentReport'])->name('admin.client-payment.report');
    Route::get('/admin/client-payment-export/show', [AdminCustomerController::class, 'clientPaymentExportShow'])->name('admin.client-payment.export.show');

    Route::get('/admin/customer-detail/{id}', [AdminCustomerController::class, 'showCustomerDetail']);
});

//Clear Cache facade value:
Route::get('/admin/clear-cache', function () {
    Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/admin/optimize', function () {
    Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/admin/route-cache', function () {
    Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/admin/route-clear', function () {
    Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/admin/view-clear', function () {
    Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/admin/config-cache', function () {
    Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
