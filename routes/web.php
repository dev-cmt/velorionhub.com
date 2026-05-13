<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PageSeoController;
use App\Http\Controllers\DeveloperApiController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleRequisitionController;
use App\Http\Controllers\ServiceTicketController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\HomeSlideController;
use App\Http\Controllers\PromotionBannerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PageSectionController;
use App\Http\Controllers\SearchController;

Route::get('/search-suggestions', [SearchController::class, 'suggest'])->name('search.suggest');


Route::get('/sync-permissions', [AdminController::class, 'resyncPermissions'])->name('sync.permissions');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/cc', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    // \Illuminate\Support\Facades\Artisan::call('config:cache');
    return 'Cleared!';
});

$controller = config("theme.frontend.controller");
Route::controller($controller)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'productShow')->name('product.show');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/place-order', 'placeOrder')->name('place.order');
    Route::get('/order/confirm/{invoice}', 'orderConfirm')->name('order.confirm');
    Route::get('/cart', 'cart')->name('cart');
    Route::get('/wishlist', 'wishlist')->name('wishlist');
    Route::post('/wishlist/add', 'addWishlist')->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', 'removeWishlist')->name('wishlist.remove');

    Route::get('/compare', 'compare')->name('compare');
    Route::post('/compare/add', 'addCompare')->name('compare.add');
    Route::delete('/compare/remove/{id}', 'removeCompare')->name('compare.remove');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogShow')->name('blog.show');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::post('/review-store/{product}', 'storeReview')->name('review.store');
    
    Route::get('/catalog', 'catalog')->name('catalog');
    Route::get('/catalog/{slug}', 'catalogShow')->name('catalog.show');
    Route::get('/about-us', 'aboutUs')->name('about.us');
    Route::get('/contacts', 'contacts')->name('contacts');
    Route::get('/track-order', 'trackOrder')->name('track.order');
    Route::get('/faq', 'faq')->name('faq');
});

// Frontend Cart
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateQty'])->name('cart.update.qty');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/mini', [CartController::class, 'mini'])->name('cart.mini');

// Dashboard
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('auth');

/**__________________________________________________________________________
 * Inventory
 * __________________________________________________________________________
 */
Route::middleware('auth')->group(function () {
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{products}/delete', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/attributes/items', [ProductController::class, 'getItems'])->name('attributes.getItems');
    Route::get('/products/variants', [ProductController::class, 'getVariantCombinations'])->name('products.getItemsCombo');

    // Expired
    Route::get('/expired-products', [ProductController::class, 'expiredIndex'])->name('expired-products.index');
    Route::post('/products/handle-expired/{id}', [ProductController::class, 'handleExpired'])->name('products.handleExpired');
    Route::post('/products/restore-expired/{id}', [ProductController::class, 'restoreExpired'])->name('products.restoreExpired');

    // Low Stocks
    Route::get('/low-stocks', [ProductController::class, 'lowStocksIndex'])->name('low-stocks.index');
    Route::post('/low-stocks/notify', [ProductController::class, 'notifyLowStock'])->name('low-stocks.notify');

    // Store
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::post('/stores/update', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{stores}/delete', [StoreController::class, 'destroy'])->name('stores.destroy');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::post('/brands/update', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}/delete', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags/store', [TagController::class, 'store'])->name('tags.store');
    Route::post('tags/update', [TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tags}/delete', [TagController::class, 'destroy'])->name('tags.destroy');

    // Warranties
    Route::get('/warranties', [UnitController::class, 'index'])->name('warranties.index');
    Route::post('/warranties', [UnitController::class, 'store'])->name('warranties.store');
    Route::post('/warranties/update', [UnitController::class, 'update'])->name('warranties.update');
    Route::delete('/warranties/{warranty}/delete', [UnitController::class, 'destroy'])->name('warranties.destroy');

    // Units
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::post('/units/update', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{unit}/delete', [UnitController::class, 'destroy'])->name('units.destroy');

    // Attributes
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes/store', [AttributeController::class, 'store'])->name('attributes.store');
    Route::post('attributes/update', [AttributeController::class, 'update'])->name('attributes.update');
    Route::post('attributes/destroy', [AttributeController::class, 'destroy'])->name('attributes.destroy');

    Route::post('attribute-items/store', [AttributeController::class, 'storeItem'])->name('attribute-items.store');
    Route::post('attribute-items/update', [AttributeController::class, 'updateItem'])->name('attribute-items.update');
    Route::post('attribute-items/destroy', [AttributeController::class, 'destroyItem'])->name('attribute-items.destroy');

    // Warranties
    Route::get('/warranties', [WarrantyController::class, 'index'])->name('warranties.index');
    Route::post('/warranties', [WarrantyController::class, 'store'])->name('warranties.store');
    Route::post('/warranties/update', [WarrantyController::class, 'update'])->name('warranties.update');
    Route::delete('/warranties/{warranty}/delete', [WarrantyController::class, 'destroy'])->name('warranties.destroy');

    // Label Print
    Route::get('/label-print', [ProductController::class, 'labelPrintIndex'])->name('label-print.index');
    Route::get('/label-print/search', [ProductController::class, 'labelPrintSearch'])->name('label-print.search');
    Route::post('/label-print/generate', [ProductController::class, 'labelPrintGenerate'])->name('label-print.generate');
    Route::post('/label-print/generate-qr', [ProductController::class, 'labelPrintGenerateQR'])->name('label-print.generate.qr');

    // Media Upload (AJAX)
    Route::post('/media/upload', [ProductController::class, 'mediaUpload'])->name('media.upload');
    Route::post('/media/delete', [ProductController::class, 'mediaDelete'])->name('media.delete');

});

/**__________________________________________________________________________
 * Stock
 * __________________________________________________________________________
 */
Route::middleware('auth')->group(function () {
    // Stock Manage
    Route::get('/stock-manage', [StockController::class, 'indexManage'])->name('stock-manage.index');
    Route::post('/update-stock/{id}', [StockController::class, 'updateManage'])->name('stock.update');

    // Adjustment
    Route::get('/stock-adjustment', [StockController::class, 'indexAdjustment'])->name('stock-adjustment.index');
    Route::post('/update-adjustment/{id}', [StockController::class, 'updateAdjustment'])->name('adjustment.update');

    // Transfers
    Route::get('/stock-transfer', [StockController::class, 'indexTransfer'])->name('stock-transfer.index');
    Route::post('/stock-transfer/store', [StockController::class, 'storeTransfer'])->name('stock-transfer.store');
    Route::post('/update-transfer/{id}', [StockController::class, 'updateTransfer'])->name('transfer.update');
});


Route::middleware('auth')->group(function () {

    // Order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/edit/{orders}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/update/{orders}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{orders}/delete', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Sale Requisition
    Route::get('/sale-requisitions', [SaleRequisitionController::class, 'index'])->name('sale-requisitions.index');
    Route::get('/sale-requisitions/create', [SaleRequisitionController::class, 'create'])->name('sale-requisitions.create');
    Route::post('/sale-requisitions', [SaleRequisitionController::class, 'store'])->name('sale-requisitions.store');
    Route::get('/sale-requisitions/edit/{orders}', [SaleRequisitionController::class, 'edit'])->name('sale-requisitions.edit');
    Route::put('/sale-requisitions/update/{orders}', [SaleRequisitionController::class, 'update'])->name('sale-requisitions.update');
    Route::delete('/sale-requisitions/{orders}/delete', [SaleRequisitionController::class, 'destroy'])->name('sale-requisitions.destroy');

    Route::get('/sale-approve', [SaleRequisitionController::class, 'indexApprove'])->name('sale-approve.index');
    Route::get('/sale-approve/{id}', [SaleRequisitionController::class, 'saleApproved'])->name('sale-approve.approved');
    Route::get('/sale-canceled/{id}', [SaleRequisitionController::class, 'saleCanceled'])->name('sale-approve.canceled');

    // Customer Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Client Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
    Route::post('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Blog Routes
    Route::resource('blogs', BlogController::class);
});


// Service tickets
Route::prefix('service-tickets')->group(function () {
    Route::get('/', [ServiceTicketController::class, 'index'])->name('service-tickets.index');
    Route::get('/create', [ServiceTicketController::class, 'create'])->name('service-tickets.create');
    Route::post('/', [ServiceTicketController::class, 'store'])->name('service-tickets.store');
    Route::get('/{serviceTicket}', [ServiceTicketController::class, 'show'])->name('service-tickets.show');
    Route::get('/{serviceTicket}/edit', [ServiceTicketController::class, 'edit'])->name('service-tickets.edit');
    Route::put('/{serviceTicket}', [ServiceTicketController::class, 'update'])->name('service-tickets.update');
    Route::delete('/{serviceTicket}', [ServiceTicketController::class, 'destroy'])->name('service-tickets.destroy');

    // Inspection Assignment
    Route::get('/{serviceTicket}/assign-inspection', [ServiceTicketController::class, 'assignInspectionForm'])->name('service-tickets.assign-inspection');
    Route::post('/{serviceTicket}/assign-inspection', [ServiceTicketController::class, 'assignInspection'])->name('service-tickets.assign-inspection.store');

    // Inspection Report
    Route::get('/{serviceTicket}/inspection-report', [ServiceTicketController::class, 'inspectionReportForm'])->name('service-tickets.inspection-report');
    Route::post('/{serviceTicket}/inspection-report', [ServiceTicketController::class, 'saveInspectionReport'])->name('service-tickets.inspection-report.store');

    // Admin Approval
    Route::get('/{serviceTicket}/approval', [ServiceTicketController::class, 'approvalForm'])->name('service-tickets.approval');
    Route::post('/{serviceTicket}/approval', [ServiceTicketController::class, 'processApproval'])->name('service-tickets.approval.process');

    // Status Update
    Route::post('/{serviceTicket}/update-status', [ServiceTicketController::class, 'updateStatus'])->name('service-tickets.update-status');
});

Route::middleware('auth')->group(function () {
    // Developer API
    Route::get('/developer-api', [DeveloperApiController::class, 'index'])->name('developer-api.index');
    Route::post('/developer-api/generate-token', [DeveloperApiController::class, 'generateToken'])->name('developer-api.generate-token');

    /**----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     * BACKEND TEMPLATE
     * ----------------------------------------------------------------------------------------------
     * ----------------------------------------------------------------------------------------------
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('password.change');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Role Management
    Route::resource('roles', RoleController::class);

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/settings-update', [SettingController::class, 'update'])->name('setting.update');

    // SEO settings
    Route::get('/seo-pages',[PageSeoController::class,'index'])->name('settings.seo.index');
    Route::post('/seo-pages/{page}',[PageSeoController::class,'update'])->name('settings.seo.update');

    // Home Slides
    Route::resource('home-slides', HomeSlideController::class)->except(['create', 'edit']);
    
    // Promotion Banners
    Route::resource('promotion-banners', PromotionBannerController::class)->except(['create', 'edit']);

    // Page Builder
    Route::group(['prefix' => 'page-builder', 'as' => 'page-builder.'], function () {
        Route::get('/pages', [PageController::class, 'index'])->name('admin.pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('admin.pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('admin.pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('admin.pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('admin.pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('admin.pages.destroy');
        Route::get('/pages/{page}/builder', [PageController::class, 'builder'])->name('admin.pages.builder');
        Route::post('/pages/{page}/publish', [PageController::class, 'publish'])->name('admin.pages.publish');
        Route::post('/pages/{page}/unpublish', [PageController::class, 'unpublish'])->name('admin.pages.unpublish');

        // Page Sections
        Route::post('/sections', [PageSectionController::class, 'store'])->name('admin.sections.store');
        Route::get('/sections/{section}/edit', [PageSectionController::class, 'edit'])->name('admin.sections.edit');
        Route::put('/sections/{section}', [PageSectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('/sections/{section}', [PageSectionController::class, 'destroy'])->name('admin.sections.destroy');
        Route::post('/sections/reorder', [PageSectionController::class, 'reorder'])->name('admin.sections.reorder');
        Route::post('/sections/{section}/toggle', [PageSectionController::class, 'toggleActive'])->name('admin.sections.toggle');
        Route::post('/sections/{section}/duplicate', [PageSectionController::class, 'duplicate'])->name('admin.sections.duplicate');
    });
});


require __DIR__.'/auth.php';
