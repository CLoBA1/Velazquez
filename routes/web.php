<?php

use Illuminate\Support\Facades\Route;

use App\Models\Product;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCodeController;

use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\MachineController;

Route::get('/admin/ping', fn() => response('PING', 200));

// Public Store Routes
Route::get('/', [\App\Http\Controllers\PageController::class, 'home'])->name('home');
Route::get('/ferreteria', [\App\Http\Controllers\StoreController::class, 'index'])->name('store.index');
Route::get('/marcas', [\App\Http\Controllers\Store\BrandController::class, 'index'])->name('store.brands.index');
Route::get('/marcas/{id}', [\App\Http\Controllers\Store\BrandController::class, 'show'])->name('store.brands.show');
Route::get('/ofertas', [\App\Http\Controllers\Store\OfferController::class, 'index'])->name('store.offers.index');
Route::get('/carrito', [\App\Http\Controllers\StoreController::class, 'cart'])->name('store.cart');

// Expansion Routes
Route::get('/construccion', [\App\Http\Controllers\ConstructionStoreController::class, 'index'])->name('construction.index');
Route::get('/maquinaria', [\App\Http\Controllers\MachineStoreController::class, 'index'])->name('machinery.index');
Route::get('/maquinaria/{machine}', [\App\Http\Controllers\MachineStoreController::class, 'show'])->name('machinery.show');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\Store\CheckoutController::class, 'index'])->name('store.checkout');
    Route::post('/checkout', [\App\Http\Controllers\Store\CheckoutController::class, 'store'])->name('store.checkout.process');
    Route::get('/checkout/success/{sale}', [\App\Http\Controllers\Store\CheckoutController::class, 'success'])->name('store.checkout.success');

    // Customer History
    Route::get('/mis-compras', [\App\Http\Controllers\SalesController::class, 'myPurchases'])->name('sales.my-purchases');
    Route::get('/mis-compras/{sale}', [\App\Http\Controllers\SalesController::class, 'showPurchase'])->name('sales.show');

    // Rentals
    Route::get('/mis-rentas', [\App\Http\Controllers\RentalsController::class, 'index'])->name('rentals.index');
    Route::get('/mis-rentas/{rental}', [\App\Http\Controllers\RentalsController::class, 'show'])->name('rentals.show');
    Route::post('/rentas', [\App\Http\Controllers\RentalsController::class, 'store'])->name('rentals.store');
});

Route::get('/producto/{product}', [\App\Http\Controllers\StoreController::class, 'show'])->name('store.show');

// Static Pages
Route::controller(\App\Http\Controllers\PageController::class)->group(function () {
    Route::get('/envios', 'shipping')->name('pages.shipping');
    Route::get('/devoluciones', 'returns')->name('pages.returns');
    Route::get('/contacto', 'contact')->name('pages.contact');
    Route::get('/privacidad', 'privacy')->name('pages.privacy');
    Route::get('/terminos', 'terms')->name('pages.terms');
    Route::get('/cookies', 'cookies')->name('pages.cookies');
});

// Newsletter
Route::post('/newsletter', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::prefix('admin')->group(function () {

    Route::post('productos/generar-codigo', [ProductCodeController::class, 'generate'])
        ->name('admin.products.generate_code');

    Route::get('productos/importar', [ProductImportController::class, 'create'])
        ->name('admin.products.import.create');

    Route::post('productos/importar', [ProductImportController::class, 'store'])
        ->name('admin.products.import.store');

    Route::get('productos/importar/plantilla', [ProductImportController::class, 'downloadTemplate'])
        ->name('admin.products.import.template');

    Route::get('productos/importar/{history}/reporte', [ProductImportController::class, 'downloadReport'])
        ->name('admin.products.import.report');

    // Login (no protegido)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('admin.login.submit');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Panel protegido
    Route::middleware(['auth', 'role:staff'])->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/dashboard', function () {
            return redirect()->route('admin.dashboard');
        })->name('dashboard');

        Route::resource('familias', FamilyController::class)
            ->except(['show'])
            ->parameters(['familias' => 'family'])
            ->names('admin.families');

        Route::resource('categorias', CategoryController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'category'])
            ->names('admin.categories');

        Route::resource('marcas', BrandController::class)
            ->except(['show'])
            ->parameters(['marcas' => 'brand'])
            ->names('admin.brands');

        Route::resource('unidades', UnitController::class)
            ->except(['show'])
            ->parameters(['unidades' => 'unit'])
            ->names('admin.units');

        Route::resource('productos', ProductController::class)
            ->except(['show'])
            ->parameters(['productos' => 'product'])
            ->names('admin.products');

        Route::resource('maquinas', MachineController::class)
            ->parameters(['maquinas' => 'machine'])
            ->names('admin.machines');

        Route::resource('materiales', \App\Http\Controllers\Admin\ConstructionController::class)
            ->parameters(['materiales' => 'product'])
            ->names('admin.construction');

        Route::resource('ofertas', \App\Http\Controllers\Admin\OfferController::class)
            ->only(['index', 'update', 'destroy'])
            ->names('admin.offers');

        // Rutas exclusivas de Admin (Gestión de Usuarios)
        Route::middleware('role:admin')->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
                ->names('admin.users');
        });

        Route::get('/inventario/movimientos', \App\Livewire\Admin\Inventory\MovementIndex::class)
            ->name('admin.inventory.movements');

        Route::get('/inventario/nuevo-movimiento', \App\Livewire\Admin\Inventory\MovementCreate::class)
            ->name('admin.inventory.create');

        Route::get('/pos', \App\Livewire\Admin\Pos\PosSystem::class)
            ->name('admin.pos');

        Route::get('/ventas/{sale}/pdf', [\App\Http\Controllers\Admin\SaleController::class, 'downloadPdf'])
            ->name('admin.sales.pdf');

        Route::get('/ventas', \App\Livewire\Admin\Sales\SaleIndex::class)
            ->name('admin.sales.index');

        // Clientes
        Route::get('/clientes', \App\Livewire\Admin\Client\ClientIndex::class)
            ->name('admin.clients.index');

        Route::get('/reportes', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('admin.reports.index');

        Route::get('/reportes/inventario', [\App\Http\Controllers\Admin\ReportController::class, 'inventory'])
            ->name('admin.reports.inventory');

        Route::get('/reportes/movimientos', [\App\Http\Controllers\Admin\ReportController::class, 'movements'])
            ->name('admin.reports.movements');

        Route::get('/importacion', fn() => 'Importación (en construcción)')
            ->name('admin.import.index');

        // Helper Deployment Route (Hostinger)
        Route::get('/migrate-db', function () {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return 'Database Migrated Successfully! <a href="/admin/dashboard">Go to Dashboard</a>';
        })->name('admin.migrate');
    });
});

// Breeze / auth routes (fuera de /admin para no chocar con /admin/login)
require __DIR__ . '/auth.php';

// Route for clearing cache on shared hosting
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cache cleared! <br> <a href="/">Go Back</a>';
});