<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\OperationTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TipController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Site2Controller;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PromocodeController;
use App\Http\Controllers\RevisionController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;

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

Route::domain(env('DOMAIN_ADMIN', 'admin.dream.aero'))->group(function () {
	Route::get('sitemap.xml', function () {
		abort(404);
	});
	Route::get('robots.txt', function () {
		header('Content-Type: text/plain; charset=UTF-8');
		readfile(dirname(__FILE__) . '/../public/robots-admin.txt');
	});
	
	// Авторизация
	Route::get('/login', [AuthenticatedSessionController::class, 'create'])
		->middleware('guest')
		->name('login');
	
	Route::post('/login', [AuthenticatedSessionController::class, 'store'])
		->middleware('guest');
	
	Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
		->middleware('guest')
		->name('password.request');
	
	Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
		->middleware('guest')
		->name('password.email');
	
	Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
		->middleware('guest')
		->name('password.reset');
	
	Route::post('/reset-password', [NewPasswordController::class, 'store'])
		->middleware('guest')
		->name('password.update');
	
	Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
		->middleware('auth')
		->name('verification.notice');
	
	Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
		->middleware(['auth', 'signed', 'throttle:6,1'])
		->name('verification.verify');
	
	Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
		->middleware(['auth', 'throttle:6,1'])
		->name('verification.send');
	
	Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
		->middleware('auth')
		->name('password.confirm');
	
	Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
		->middleware('auth');
	
	Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
		->middleware('auth')
		->name('logout');
	
	Route::group(['middleware' => ['usercheck']], function () {
		// Контрагенты
		Route::get('contractor/add', [ContractorController::class, 'add']);
		Route::get('contractor/{id}/edit', [ContractorController::class, 'edit']);
		
		Route::get('contractor/search', [ContractorController::class, 'search'])->name('contractorSearch');
		Route::get('contractor/{id?}', [ContractorController::class, 'index'])->name('contractorIndex');
		Route::get('contractor/list/ajax', [ContractorController::class, 'getListAjax'])->name('contractorList');
		Route::post('contractor', [ContractorController::class, 'store']);
		Route::put('contractor/{id}', [ContractorController::class, 'update']);

		Route::get('contractor/{id}/score', [ContractorController::class, 'addScore']);
		Route::post('contractor/{id}/score', [ContractorController::class, 'storeScore']);

		// События
		Route::get('/', [EventController::class, 'index'])->name('eventIndex');
		Route::get('event/list/ajax', [EventController::class, 'getListAjax'])->name('eventList');
		Route::post('event/notified', [EventController::class, 'notified'])->name('notified-event');
		Route::post('event', [EventController::class, 'store'])->name('store-event');
		Route::put('event/drag_drop/{id}', [EventController::class, 'dragDrop'])->name('drag-drop-event');
		Route::put('event/{id}', [EventController::class, 'update'])->name('update-event');
		Route::delete('event/{id}/comment/{comment_id}/remove', [EventController::class, 'deleteComment'])->name('delete-comment');
		Route::delete('event/{id}', [EventController::class, 'delete'])->name('delete-event');

		Route::get('event/{position_id}/add/{event_type?}', [EventController::class, 'add'])->name('add-event');
		Route::get('event/{id}/edit/{is_shift?}', [EventController::class, 'edit'])->name('edit-event');
		Route::get('event/{id}/show', [EventController::class, 'show'])->name('show-event');
		
		Route::get('event/{uuid}/file', [EventController::class, 'getFlightInvitationFile'])->name('getFlightInvitation');
		Route::post('event/send', [EventController::class, 'sendFlightInvitation'])->name('sendFlightInvitation');
		Route::get('event/{uuid}/doc/file', [EventController::class, 'getDocFile'])->name('getDocFile');
		Route::post('event/{id}/doc/file/delete', [EventController::class, 'deleteDocFile'])->name('deleteDocFile');

		// Сделки
		Route::get('deal/{id?}', [DealController::class, 'index'])->name('dealIndex');
		Route::get('deal/list/ajax', [DealController::class, 'getListAjax'])->name('dealList');
		Route::post('deal/booking', [DealController::class, 'storeBooking'])->name('dealBookingStore');
		Route::post('deal/product', [DealController::class, 'storeProduct']);
		Route::post('deal/tax', [DealController::class, 'storeTax']);
		Route::put('deal/{id}', [DealController::class, 'update']);
		/*Route::post('deal/contractor_link', [DealController::class, 'contractorLink'])->name('contractorLink');*/

		Route::get('deal/certificate/add', [DealController::class, 'addCertificate']);
		Route::get('deal/booking/add', [DealController::class, 'addBooking']);
		Route::get('deal/product/add', [DealController::class, 'addProduct']);
		Route::get('deal/tax/add', [DealController::class, 'addTax']);
		Route::get('deal/{id}/edit', [DealController::class, 'edit']);
		
		// Сертификаты
		Route::put('certificate/{id}', [CertificateController::class, 'update']);
		
		Route::get('certificate/search', [CertificateController::class, 'search'])->name('certificateSearch');
		Route::get('certificate/{id}/edit', [CertificateController::class, 'edit']);
		
		Route::get('certificate/{uuid}/file', [CertificateController::class, 'getCertificateFile'])->name('getCertificate');
		Route::post('certificate/send', [CertificateController::class, 'sendCertificate'])->name('sendCertificate');
		
		Route::get('certificate', [CertificateController::class, 'index'])->name('certificatesIndex');
		Route::get('certificate/list/ajax', [CertificateController::class, 'getListAjax'])->name('certificatesGetList');
		
		// Счета
		Route::get('bill/{id}/miles/accrual', [BillController::class, 'accrualAeroflotMilesModal'])->name('accrualAeroflotMilesModal');
		Route::post('bill/miles/accrual', [BillController::class, 'accrualAeroflotMiles'])->name('accrualAeroflotMiles');

		Route::post('bill', [BillController::class, 'store']);
		Route::put('bill/{id}', [BillController::class, 'update']);
		Route::delete('bill/{id}', [BillController::class, 'delete']);

		Route::get('bill/{deal_id}/add', [BillController::class, 'add']);
		Route::get('bill/{id}/edit', [BillController::class, 'edit']);

		Route::post('bill/paylink/send', [BillController::class, 'sendPayLink'])->name('sendPayLink');
		
		Route::get('receipt/{uuid}/file/{print?}', [BillController::class, 'getReceiptFile'])->name('getReceipt');
		Route::post('receipt/send', [BillController::class, 'sendReceipt'])->name('sendReceipt');
		
		// Типы операций
		Route::get('operation_type', [OperationTypeController::class, 'index'])->name('operationTypeIndex');
		Route::get('operation_type/list/ajax', [OperationTypeController::class, 'getListAjax'])->name('operationTypeList');
		
		Route::post('operation_type', [OperationTypeController::class, 'store']);
		Route::put('operation_type/{id}', [OperationTypeController::class, 'update']);
		Route::delete('operation_type/{id}', [OperationTypeController::class, 'delete']);
		
		Route::get('operation_type/add', [OperationTypeController::class, 'add']);
		Route::get('operation_type/{id}/edit', [OperationTypeController::class, 'edit']);
		Route::get('operation_type/{id}/delete', [OperationTypeController::class, 'confirm']);
		Route::get('operation_type/{id}/show', [OperationTypeController::class, 'show']);
		
		// Операции
		Route::get('operation/add', [OperationController::class, 'add']);
		Route::get('operation/{id}/edit', [OperationController::class, 'edit']);
		Route::get('operation/{id}/delete', [OperationController::class, 'confirm']);
		
		Route::get('operation/{id?}', [OperationController::class, 'index'])->name('operationIndex');
		Route::get('operation/list/ajax', [OperationController::class, 'getListAjax'])->name('operationList');
		Route::post('operation', [OperationController::class, 'store']);
		Route::put('operation/{id}', [OperationController::class, 'update']);
		Route::delete('operation/{id}', [OperationController::class, 'delete']);
		
		// Чаевые
		Route::get('tip/add', [TipController::class, 'add']);
		Route::get('tip/{id}/edit', [TipController::class, 'edit']);
		Route::get('tip/{id}/delete', [TipController::class, 'confirm']);

		Route::get('tip/{id?}', [TipController::class, 'index'])->name('tipIndex');
		Route::get('tip/list/ajax', [TipController::class, 'getListAjax'])->name('tipList');
		Route::post('tip', [TipController::class, 'store']);
		Route::put('tip/{id}', [TipController::class, 'update']);
		Route::delete('tip/{id}', [TipController::class, 'delete']);
		
		// Скидки
		Route::get('discount', [DiscountController::class, 'index'])->name('discountIndex');
		Route::get('discount/list/ajax', [DiscountController::class, 'getListAjax'])->name('discountList');

		Route::post('discount', [DiscountController::class, 'store']);
		Route::put('discount/{id}', [DiscountController::class, 'update']);
		Route::delete('discount/{id}', [DiscountController::class, 'delete']);

		Route::get('discount/add', [DiscountController::class, 'add']);
		Route::get('discount/{id}/edit', [DiscountController::class, 'edit']);
		Route::get('discount/{id}/delete', [DiscountController::class, 'confirm']);
		Route::get('discount/{id}/show', [DiscountController::class, 'show']);

		// Промокоды
		Route::get('promocode', [PromocodeController::class, 'index'])->name('promocodeIndex');
		Route::get('promocode/list/ajax', [PromocodeController::class, 'getListAjax'])->name('promocodeList');

		Route::post('promocode', [PromocodeController::class, 'store']);
		Route::put('promocode/{id}', [PromocodeController::class, 'update']);
		Route::delete('promocode/{id}', [PromocodeController::class, 'delete']);

		Route::get('promocode/add', [PromocodeController::class, 'add']);
		Route::get('promocode/{id}/edit', [PromocodeController::class, 'edit']);
		Route::get('promocode/{id}/delete', [PromocodeController::class, 'confirm']);
		Route::get('promocode/{id}/show', [PromocodeController::class, 'show']);

		// Цены
		Route::get('pricing', [PricingController::class, 'index'])->name('pricingIndex');
		Route::get('pricing/list/ajax', [PricingController::class, 'getListAjax'])->name('pricingList');

		Route::put('pricing/{city_id}/{product_id}', [PricingController::class, 'update']);
		Route::delete('pricing/{city_id}/{product_id}/certificate_template/delete', [PricingController::class, 'deleteCertificateTemplate']);
		Route::delete('pricing/{city_id}/{product_id}', [PricingController::class, 'delete']);

		Route::get('pricing/{city_id}/{product_id}/edit', [PricingController::class, 'edit']);
		Route::get('pricing/{city_id}/{product_id}/delete', [PricingController::class, 'confirm']);
		Route::get('pricing/{city_id}/{product_id}/show', [PricingController::class, 'show']);
		
		Route::get('certificate/template/{city_id}/{product_id}/download', [PricingController::class, 'getCertificateTemplateFile'])->name('downloadCertificateTemplateFile');
		Route::post('certificate/template/{city_id}/{product_id}/delete', [PricingController::class, 'deleteCertificateTemplateFile']);
		
		// Города
		Route::get('city', [CityController::class, 'index'])->name('cityIndex');
		Route::get('city/list/ajax', [CityController::class, 'getListAjax'])->name('cityList');

		Route::post('city', [CityController::class, 'store']);
		Route::put('city/{id}', [CityController::class, 'update']);
		Route::delete('city/{id}', [CityController::class, 'delete']);

		Route::get('city/add', [CityController::class, 'add']);
		Route::get('city/{id}/edit', [CityController::class, 'edit']);
		Route::get('city/{id}/delete', [CityController::class, 'confirm']);
		Route::get('city/{id}/show', [CityController::class, 'show']);

		Route::get('city/user', [CityController::class, 'getUserList'])->name('userListByCity');

		// Локации
		Route::get('location', [LocationController::class, 'index'])->name('locationIndex');
		Route::get('location/list/ajax', [LocationController::class, 'getListAjax'])->name('locationList');

		Route::post('location', [LocationController::class, 'store']);
		Route::put('location/{id}', [LocationController::class, 'update']);
		Route::delete('location/{id}', [LocationController::class, 'delete']);

		Route::get('location/add', [LocationController::class, 'add']);
		Route::get('location/{id}/edit', [LocationController::class, 'edit']);
		Route::get('location/{id}/delete', [LocationController::class, 'confirm']);
		Route::get('location/{id}/show', [LocationController::class, 'show']);

		// Типы продуктов
		Route::get('product_type', [ProductTypeController::class, 'index'])->name('productTypeIndex');
		Route::get('product_type/list/ajax', [ProductTypeController::class, 'getListAjax'])->name('productTypeList');

		Route::post('product_type', [ProductTypeController::class, 'store']);
		Route::put('product_type/{id}', [ProductTypeController::class, 'update']);
		Route::delete('product_type/{id}', [ProductTypeController::class, 'delete']);

		Route::get('product_type/add', [ProductTypeController::class, 'add'])->name('productTypeAdd');
		Route::get('product_type/{id}/edit', [ProductTypeController::class, 'edit']);
		Route::get('product_type/{id}/delete', [ProductTypeController::class, 'confirm']);
		Route::get('product_type/{id}/show', [ProductTypeController::class, 'show']);

		// Продукты
		Route::get('product', [ProductController::class, 'index'])->name('productIndex');
		Route::get('product/list/ajax', [ProductController::class, 'getListAjax'])->name('productList');

		Route::post('product', [ProductController::class, 'store']);
		Route::put('product/{id}', [ProductController::class, 'update']);
		Route::delete('product/{id}', [ProductController::class, 'delete']);
		Route::put('product/{id}/icon/delete', [ProductController::class, 'deleteIcon']);

		Route::get('product/add', [ProductController::class, 'add']);
		Route::get('product/{id}/edit', [ProductController::class, 'edit']);
		Route::get('product/{id}/delete', [ProductController::class, 'confirm']);
		Route::get('product/{id}/show', [ProductController::class, 'show']);

		// Способы оплаты
		Route::get('payment_method', [PaymentMethodController::class, 'index'])->name('paymentMethodIndex');
		Route::get('payment_method/list/ajax', [PaymentMethodController::class, 'getListAjax'])->name('paymentMethodList');

		Route::post('payment_method', [PaymentMethodController::class, 'store']);
		Route::put('payment_method/{id}', [PaymentMethodController::class, 'update']);
		Route::delete('payment_method/{id}', [PaymentMethodController::class, 'delete']);

		Route::get('payment_method/add', [PaymentMethodController::class, 'add']);
		Route::get('payment_method/{id}/edit', [PaymentMethodController::class, 'edit']);
		Route::get('payment_method/{id}/delete', [PaymentMethodController::class, 'confirm']);
		Route::get('payment_method/{id}/show', [PaymentMethodController::class, 'show']);

		// Пользователи
		Route::get('user', [UserController::class, 'index'])->name('userIndex');
		Route::get('user/list/ajax', [UserController::class, 'getListAjax'])->name('userList');

		Route::post('user', [UserController::class, 'store']);
		Route::put('user/{id}', [UserController::class, 'update']);
		Route::delete('user/{id}', [UserController::class, 'delete']);

		Route::get('user/add', [UserController::class, 'add']);
		Route::get('user/{id}/edit', [UserController::class, 'edit']);
		Route::get('user/{id}/delete', [UserController::class, 'confirm']);
		Route::get('user/{id}/show', [UserController::class, 'show']);

		Route::post('user/{id}/password/reset/notification', [UserController::class, 'passwordResetNotification'])->name('passwordResetNotification');

		// Акции
		Route::get('promo', [PromoController::class, 'index'])->name('promoIndex');
		Route::get('promo/list/ajax', [PromoController::class, 'getListAjax'])->name('promoList');

		Route::post('promo', [PromoController::class, 'store']);
		Route::put('promo/{id}', [PromoController::class, 'update']);
		Route::delete('promo/{id}', [PromoController::class, 'delete']);

		Route::get('promo/add', [PromoController::class, 'add']);
		Route::get('promo/{id}/edit', [PromoController::class, 'edit']);
		Route::get('promo/{id}/delete', [PromoController::class, 'confirm']);
		Route::get('promo/{id}/show', [PromoController::class, 'show']);
		Route::put('promo/{id}/image/delete', [PromoController::class, 'deleteImage']);
		Route::post('promo/image/upload', [PromoController::class, 'imageUpload']);

		// Лог операций
		Route::get('log/list/ajax', [RevisionController::class, 'getListAjax'])->name('revisionList');
		Route::get('log/{entity?}/{object_id?}', [RevisionController::class, 'index'])->name('revisionIndex');

		// Контент
		Route::get('site/{type}', [ContentController::class, 'index']);
		Route::get('site/{type}/list/ajax', [ContentController::class, 'getListAjax']);
		Route::get('site/{type}/add', [ContentController::class, 'add']);
		Route::get('site/{type}/{id}/edit', [ContentController::class, 'edit']);
		Route::get('site/{type}/{id}/delete', [ContentController::class, 'confirm']);

		Route::post('site/{type}', [ContentController::class, 'store']);
		Route::put('site/{type}/{id}', [ContentController::class, 'update']);
		Route::delete('site/{type}/{id}', [ContentController::class, 'delete']);
		Route::post('site/{type}/image/upload', [ContentController::class, 'imageUpload']);
		
		// Отчеты
		Route::get('report/personal-selling', [ReportController::class, 'personalSellingIndex'])->name('personalSellingIndex');
		Route::get('report/personal-selling/list/ajax', [ReportController::class, 'personalSellingGetListAjax'])->name('personalSellingList');
		
		Route::get('report/unexpected-repeated', [ReportController::class, 'unexpectedRepeatedIndex'])->name('unexpectedRepeatedIndex');
		Route::get('report/unexpected-repeated/list/ajax', [ReportController::class, 'unexpectedRepeatedGetListAjax'])->name('unexpectedRepeatedGetList');
		
		Route::get('report/contractor-self-made-payed-deals', [ReportController::class, 'contractorSelfMadePayedDealsIndex'])->name('contractorSelfMadePayedDealsIndex');
		Route::get('report/contractor-self-made-payed-deals/list/ajax', [ReportController::class, 'contractorSelfMadePayedDealsGetListAjax'])->name('contractorSelfMadePayedDealsGetList');
		
		Route::get('report/cash-flow', [ReportController::class, 'cashFlowIndex'])->name('cashFlowIndex');
		Route::get('report/cash-flow/list/ajax', [ReportController::class, 'cashFlowGetListAjax'])->name('cashFlowList');
		
		Route::get('report/tips', [ReportController::class, 'tipsIndex'])->name('tipsIndex');
		Route::get('report/tips/list/ajax', [ReportController::class, 'tipsGetListAjax'])->name('tipsList');

		Route::get('report/file/{filepath}', [ReportController::class, 'getExportFile'])->name('getExportFile');
	});
});

Route::domain(env('DOMAIN_SITE2', 'fly-737.com'))->group(function () {
	Route::get('robots.txt', function () {
		header('Content-Type: text/plain; charset=UTF-8');
		readfile(dirname(__FILE__) . '/../public/robots_fly737.txt');
	});
	Route::get('sitemap.xml', [Site2Controller::class, 'sitemap']);
	
	Route::group(['middleware' => ['domaincheck']], function () {
		Route::get('', [Site2Controller::class, 'home'])->name('home');
		Route::get('about-simulator', [Site2Controller::class, 'about'])->name('o-trenazhere');
		Route::get('gift-sertificates', [Site2Controller::class, 'giftFlight'])->name('podarit-polet');
		Route::get('flight-options', [Site2Controller::class, 'flightTypes'])->name('variantyi-poleta');
		Route::get('prices', [Site2Controller::class, 'price']);
		Route::get('contacts', [Site2Controller::class, 'contacts']);
	});
	
	Route::get('modal/callback', [Site2Controller::class, 'getCallbackModal']);
	Route::get('modal/info/{alias}', [Site2Controller::class, 'getInfoModal']);
	
	Route::post('callback', [Site2Controller::class, 'callback'])->name('callbackRequestStore');
	Route::post('question', [Site2Controller::class, 'question'])->name('questionStore');
});

Route::domain(env('DOMAIN_SITE', 'dream.aero'))->group(function () {
	Route::get('robots.txt', function () {
		header('Content-Type: text/plain; charset=UTF-8');
		readfile(dirname(__FILE__) . '/../public/robots_dreamaero.txt');
	});
	Route::get('sitemap.xml', [SiteController::class, 'sitemap']);
	
	Route::group(['middleware' => ['domaincheck', 'citycheck']], function () {
		Route::get('{alias?}', [SiteController::class, 'home'])->name('home');
		Route::get('{alias?}/about-simulator', [SiteController::class, 'about'])->name('o-trenazhere');
		Route::get('{alias?}/gift-sertificates', [SiteController::class, 'giftFlight'])->name('podarit-polet');
		Route::get('{alias?}/flight-options', [SiteController::class, 'flightTypes'])->name('variantyi-poleta');
		Route::get('{alias?}/news/{newsAlias?}', [SiteController::class, 'getNews'])->name('news');
		Route::get('{alias?}/private-events', [SiteController::class, 'privateEvents'])->name('private-events');
		Route::get('{alias?}/prices', [SiteController::class, 'price']);
		Route::get('{alias?}/gallery', [SiteController::class, 'getGallery'])->name('galereya');
		Route::get('{alias?}/reviews', [SiteController::class, 'getReviews'])->name('reviews');
		Route::get('{alias?}/contacts', [SiteController::class, 'contacts']);
		Route::get('{alias?}/privacy-policy', [SiteController::class, 'privacyPolicy'])->name('privacy-policy');
		Route::get('{alias?}/flight-briefing', [SiteController::class, 'flightBriefing'])->name('flight-briefing');
		Route::get('{alias?}/impressions', [SiteController::class, 'impressions'])->name('impressions');
		Route::get('{alias?}/prof-assistance', [SiteController::class, 'profAssistance'])->name('prof-assistance');
		Route::get('{alias?}/the-world-of-aviation', [SiteController::class, 'worldAviation'])->name('world-aviation');
		Route::get('{alias?}/treating-aerophobia', [SiteController::class, 'flyNoFear'])->name('lechenie-aerofobii');
		Route::get('{alias?}/rules', [SiteController::class, 'rules'])->name('rules');
	});
	
	Route::get('sertbuy', [SiteController::class, 'certificateForm'])->name('certificate-form');
	
	Route::post('promocode/verify', [SiteController::class, 'promocodeVerify']);
	
	Route::post('review/create', [SiteController::class, 'reviewCreate']);
	
	Route::get('city/list/ajax', [SiteController::class, 'getCityListAjax']);
	Route::get('city/change', [SiteController::class, 'changeCity']);
	
	Route::post('payment', [PaymentController::class, 'paymentProceed'])->name('paymentProceed');
	Route::get('payment/{uuid}', [PaymentController::class, 'payment'])->name('payment');
	
	Route::post('rating', [SiteController::class, 'setRating'])->name('set-rating');
	
	Route::post('modal/certificate', [SiteController::class, 'getCertificateModal']);
	Route::get('modal/review', [SiteController::class, 'getReviewModal']);
	Route::get('modal/scheme/{location_id}', [SiteController::class, 'getSchemeModal']);
	Route::get('modal/callback', [SiteController::class, 'getCallbackModal']);
	Route::get('modal/vip', [SiteController::class, 'getVipFlightModal']);
	Route::get('modal/info/{alias}', [SiteController::class, 'getInfoModal']);
	
	Route::post('callback', [SiteController::class, 'callback'])->name('callbackRequestStore');
	Route::post('question', [SiteController::class, 'question'])->name('questionStore');
});

Route::get('deal/product/calc', [DealController::class, 'calcProductAmount'])->name('calcProductAmount');
Route::post('deal/certificate', [DealController::class, 'storeCertificate'])->name('dealCertificateStore');

Route::fallback(function () {
	abort(404);
});
