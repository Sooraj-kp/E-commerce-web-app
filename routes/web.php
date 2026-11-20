    <?php
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\AddressController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\CustomerController;
    use App\Http\Controllers\StaffController;
    use App\Http\Controllers\TaskController;
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\StaffDashboardController;
    use App\Http\Controllers\StaffTaskController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\CustomerAccountController;
    use App\Http\Controllers\AdminOrderController;
    use App\Http\Controllers\StaffOrderController;

    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/shop', [ProductController::class, 'shop'])->name('shop');

    Route::get('/shop/{product}', [ProductController::class, 'show'])->name('shop.show');

    Route::get('/dashboard', function () {
        $user=auth()->user();
        if($user->role=='admin'){
            return redirect('/admin');
        }
        if($user->role=='staff'){
            return redirect('/Staff/dashboard');
        }
    })->middleware(['auth','staff'])->name('dashboard');

    Route::get('/Staff/dashboard',[StaffDashboardController::class,'index'])->middleware('auth','staff')->name('Staff.dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');     
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/my-account', [CustomerAccountController::class, 'index'])
    ->middleware('auth')
    ->name('customer.account');

    Route::get('/admin',[AdminController::class,'dashboard'])
    ->middleware('admin') ;


    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('admin');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('admin');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('admin');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
    ->middleware('admin');

    Route::put('/products/{product}', [ProductController::class, 'update'])
    ->middleware('admin');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->middleware('admin');

    Route::get('/orders',[OrderController::class,'index'])
    ->middleware('admin');

    Route::get('/customers',[CustomerController::class,'index'])
    ->middleware('admin');

    Route::get('staff',[StaffController::class,'index'])
    ->middleware('admin');

    Route::get('/staff/create', [StaffController::class, 'create'])
        ->middleware('admin');

    Route::post('/staff', [StaffController::class, 'store'])
    ->middleware('admin');

    Route::get('/staff/{id}/edit',[StaffController::class,'edit'])->middleware('admin');

    Route::put('/staff/{id}',[StaffController::class,'update'])->middleware('admin');


    Route::get('/admin/tasks',[TaskController::class,'index'])->middleware('admin');

    Route::get('/admin/tasks/create',[TaskController::class,'create'])->middleware('admin');

    Route::post('/admin/tasks',[TaskController::class,'store'])->middleware('admin');

    Route::get('/admin/tasks/{id}',[TaskController::class,'show'])->middleware('admin');

    Route::get('/admin/tasks/{id}/edit',[TaskController::class,'edit'])->middleware('admin');

    Route::put('/admin/tasks/{id}',[TaskController::class,'update'])->middleware('admin');

    Route::delete('/admin/tasks/{id}',[TaskController::class,'destroy'])->middleware('admin')->name('admin.tasks.destroy');


    Route::post('/admin/tasks/{id}/approve', [TaskController::class, 'approve'])
    ->middleware('admin');

    Route::post('/admin/tasks/{id}/request-revision',
    [TaskController::class, 'requestRevision'])
    ->middleware('admin')
    ->name('admin.tasks.requestRevision');

    Route::get('/admin/categories',[CategoryController::class,'index'])->middleware('admin');

    Route::get('/admin/categories/create',[CategoryController::class,'create'])->middleware('admin');

    Route::post('/admin/categories',[CategoryController::class,'store'])->middleware('admin');

    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])
    ->middleware('admin');

    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])
    ->middleware('admin');

    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])
    ->middleware('admin');

    Route::get('/my-addresses/create',[AddressController::class,'create'])->name('customer.address.create');

    Route::post('/my-addresses/',[AddressController::class,'store'])->name('customer.address.store');

    Route::get('/Staff/task',[StaffTaskController::class,'index'])->middleware('auth','staff')->name('staff.task.index');

    Route::get('/Staff/task/{id}',[StaffTaskController::Class,'show'])->middleware('auth','staff')->name('staff.task.show');

    Route::post('/Staff/task/{id}/start',[StaffTaskController::Class,'start'])->middleware('auth','staff')->name('staff.task.start');

    Route::post('Staff/task/{id}/submit',[StaffTaskController::Class,'submit'])->middleware('auth','staff')->name('staff.task.submit');

    Route::post('cart/add/{product}',[CartController::class,'add'])->middleware('auth')->name('cart.add');

    Route::get('/cart',[CartController::class,'index'])->middleware('auth')->name('cart.index');

    Route::patch('/cart/update/{cartItem}',[CartController::class,'update'])->middleware('auth')->name('cart.update');

    Route::delete('/cart/remove/{cartItem}',[CartController::class,'remove'])->middleware('auth')->name('cart.remove');

    Route::get('/checkout',[CheckoutController::class,'index'])->middleware('auth')->name('checkout.index');

    Route::post('/checkout/place-order',[CheckoutController::class,'store'])->middleware('auth')->name('checkout.store');

    Route::get('/my-orders/{order}',[OrderController::class,'show'])->middleware('auth')->name('customer.orders.show');

    Route::delete('/my-addresses/{address}',[AddressController::class,'destroy'])->middleware('auth')->name('customer.address.destroy');

    Route::get('/my-addresses/{id}/edit',[AddressController::class,'edit'])->middleware('auth')->name('customer.address.edit');

    Route::put('/my-addresses/{id}',[AddressController::class,'update'])->middleware('auth')->name('customer.address.update');

    Route::get('/my-orders',[OrderController::class,'myOrders'])->middleware('auth')->name('customer.orders.index');

    Route::get('admin/orders{id}',[AdminOrderController::class,'show'])->middleware('admin')->name('admin.orders.show');

    Route::get('/admin/orders',[AdminOrderController::class,'index'])->middleware('admin')->name('admin.orders.index');

    Route::put('/admin/orders/{id}/assign-staff',[AdminOrderController::class,'assignStaff'])->middleware('admin')->name('admin.orders.assignStaff');

    Route::put('/admin/orders/{id}/status',[AdminOrderController::class,'updateStatus'])->middleware('admin')->name('admin.orders.updateStatus');

    Route::get('/staff/orders',[StaffOrderController::class,'index'])->middleware('auth','staff')->name('staff.orders.index');

    Route::get('/staff/orders/{id}',[StaffOrderController::class,'show'])->middleware('auth','staff')->name('staff.orders.show');

    Route::put('staff/orders/{id}/status',[StaffOrderController::class,'updateStatus'])->middleware('auth','staff')->name('staff.orders.updateStatus');

    Route::post('buy-now/{product}',[CheckoutController::class,'buyNow'])->middleware('auth')->name('checkout.buyNow');

    Route::patch('/orders/{order}/cancel',[OrderController::class,'cancel'])->middleware('auth')->name('customer.orders.cancel');

    require __DIR__.'/auth.php';
