<?php

use App\K9Site;
use App\K9Employee;
use App\Mail\SendTest;
use App\Services\SmsServices;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use AfricasTalking\SDK\AfricasTalking;


route::get('/model', function () {
    // dd(K9Site::first());
     dd(auth()->user());
});


route::get('users/editmodal', function () {
    return view('users.editmodal');
});

Route::get('/tarriff/ecommerce', [App\Http\Controllers\EcommerceTariffController::class, 'index']  )->name('tarriff.ecommerce');
Route::get('/tarriffs/ecommerce/list', [App\Http\Controllers\EcommerceTariffController::class, 'getEcommerceTarriffs'])->name('getEcommerceTarriffs');
Route::get('/tarriffs/ecommerce/listV2', [App\Http\Controllers\EcommerceTariffController::class, 'getEcommerceTarriffsV2'])->name('getEcommerceTarriffsV2');


Route::get('/documentation',function(){return view('api.docs.index_alternative');});
Route::get('/places', [App\Http\Controllers\AccountExtensionController::class, 'index']);
Route::get('api-docs', [App\Http\Controllers\ApiUserController::class, 'apiDocumentationIndex'] )->name('api-docs.index');
Route::get('api-users-index', [App\Http\Controllers\ApiUserController::class, 'index'] )->name('api-users.index');
Route::get('api-users-register', function(){return view('api.users.register');} )->name('api-users.register');
Route::post('api-users-store', [App\Http\Controllers\ApiUserController::class, 'store'] )->name('api-users.store');
Route::get('api-users-edit/{id}/edit', [App\Http\Controllers\ApiUserController::class, 'edit'] )->name('api-users.edit');
Route::get('api-users-getApiUser', [App\Http\Controllers\ApiUserController::class, 'getApiUser'] )->name('api-users.getApiUser');
Route::put('/api-users-update/{id}', [App\Http\Controllers\ApiUserController::class, 'update'] )->name('api-users.edit');
Route::get('api-users-key-edit/{id}/edit', [App\Http\Controllers\ApiUserController::class, 'editApiKey'] )->name('api-users-key.edit');
Route::put('/api-users-key-update/{id}', [App\Http\Controllers\ApiUserController::class, 'updateApiKey'] )->name('api-users-key.edit');



Route::middleware('auth')->group(function () {
    //You auth routes here
});

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
// Route::get('/scchecker', function(){
//     return view('scan-compliance-checker');
// })->name("scanChecker");

//23-11-2021
Route::get('/departure/arrival-status', [App\Http\Controllers\WaybillsController::class, 'getWaybillsArrivalStatus'])->name('getWaybillsArrivalStatus');
Route::get('/incoming/arrival-status', [App\Http\Controllers\WaybillsController::class, 'getIncomingWaybillsArrivalStatus'])->name('getIncomingWaybillsArrivalStatus');
Route::get('/scans/departure', [App\Http\Controllers\WaybillsController::class, 'getK9DepartureScanSummary'])->name('getK9DepartureScanSummary');
Route::get('/scans/incoming', [App\Http\Controllers\WaybillsController::class, 'getK9IncomingScanSummary'])->name('getK9IncomingScanSummary');



Route::get('/escalator/notifications', [App\Http\Controllers\EscalatorController::class, 'notifications'])->name('escalator.notifications');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/demo', [App\Http\Controllers\DemoController::class, 'index']);



Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'updateDashBoard'])->name('updateDashBoard');

Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/manifest', [App\Http\Controllers\ManifestsController::class, 'index'])->name('manifest.index');
Route::get('/manifest/create', [App\Http\Controllers\ManifestsController::class, 'create'])->name('createManifest');
Route::match(array('GET','POST'), '/manifest/importDispatchedWaybills', [App\Http\Controllers\ManifestsController::class, 'importDispatchedWaybills'])->name('importDispatchedWaybills');

Route::post('/manifest', [App\Http\Controllers\ManifestsController::class, 'store'])->name('storeManifest');
Route::get('/manifest/{manifest_id}/edit', [App\Http\Controllers\ManifestsController::class, 'edit']);
Route::post('/manifest/{manifest_id}/cancel', [App\Http\Controllers\ManifestsController::class, 'cancelManifest']);
Route::post('/manifest/{manifest_id}/flag-overdue', [App\Http\Controllers\ManifestsController::class, 'flagOverdue']);
Route::get('/manifest/incoming', [App\Http\Controllers\ManifestsController::class, 'viewIncomingManifests'])->name('viewIncomingManifests');
Route::get('/manifest/dispatched/summary', [App\Http\Controllers\ManifestsController::class, 'viewDispatchedManifestsSummary'])->name('viewDispatchedManifestsSummary');
Route::get('/manifest/incoming/summary', [App\Http\Controllers\ManifestsController::class, 'viewIncomingManifestsSummary'])->name('viewIncomingManifestsSummary');


Route::get('/getIncomingManifests', [App\Http\Controllers\ManifestsController::class, 'getIncomingManifests'])->name('getIncomingManifests');
Route::get('/getDispatchedManifests', [App\Http\Controllers\ManifestsController::class, 'getDispatchedManifests'])->name('getDispatchedManifests');
Route::get('getAcknowledgedManifests', [App\Http\Controllers\ManifestsController::class, 'getAcknowledgedManifests'])->name('getAcknowledgedManifests');
Route::get('/getPartiallyAcknowledgedManifests', [App\Http\Controllers\ManifestsController::class, 'getPartiallyAcknowledgedManifests'])->name('getPartiallyAcknowledgedManifests');
Route::get('manifests/partiallyAcknowledged', [App\Http\Controllers\ManifestsController::class, 'viewPartiallyAcknowledgedManifests'])->name('viewPartiallyAcknowledgedManifests');



Route::get('/manifest/incoming', [App\Http\Controllers\ManifestsController::class, 'viewIncomingManifests'])->name('viewIncomingManifests');
Route::get('/manifest/{manifest_id}/confirm-parcels', [App\Http\Controllers\ManifestsController::class, 'confirmParcels'])->name('confirmParcels');
// Route::get('/manifest/{manifest_id}/unlock', [App\Http\Controllers\ManifestsController::class, 'unlock'])->name('unlockManifest');
Route::post('/manifest/acknowledge',  [App\Http\Controllers\ManifestsController::class, 'acknowledgeManifest'])->name('acknowledgeManifest');
Route::get('/manifest/dispatched', [App\Http\Controllers\ManifestsController::class, 'viewDispatchedManifests'])->name('viewDispatchedManifests');
Route::get('/manifest/departure-scans', [App\Http\Controllers\ManifestsController::class, 'getDepartureScansToDispatch'])->name('getDepartureScansToDispatch');

Route::get('/manifest/Acknowledged', [App\Http\Controllers\ManifestsController::class, 'viewAcknowledgedManifests'])->name('viewAcknowledgedManifests');
//Route Constriant can also help
Route::get('/manifest/{manifest_id}', [App\Http\Controllers\ManifestsController::class, 'getManifest']);
Route::get('/mps/{manifest_id}/details', [App\Http\Controllers\ManifestsController::class, 'getMPSManifest'])->name('getMPSManifest');
Route::get('/nipost/{manifest_id}/details', [App\Http\Controllers\ManifestsController::class, 'getNipostManifest'])->name('getNipostManifest');
Route::get('/nipost/{manifest_id}/summary', [App\Http\Controllers\ManifestsController::class, 'getNipostManifestSummary'])->name('getNipostManifestSummary');
Route::get('/manifest/{manifest_id}/details', [App\Http\Controllers\ManifestsController::class, 'getManifestDetails'])->name('getManifestDetails');
Route::get('/manifest-waybills', [App\Http\Controllers\ManifestsController::class, 'getManifestWaybills'])->name('getManifestWaybills');
Route::get('/manifest/virtual-sealnumber/new', [App\Http\Controllers\ManifestsController::class, 'getNewVirtualSealNumber'])->name('getNewVirtualSealNumber');
Route::get('/scan-timestamps', [App\Http\Controllers\ScanTimestampController::class, 'index'])->name('timestamp.index');
Route::get('/scan-timestamps/getTimestamps', [App\Http\Controllers\ScanTimestampController::class, 'getTimestamps'])->name('getScanTimestamps');
Route::post('/scan-timestamps', [App\Http\Controllers\ScanTimestampController::class, 'store'])->name('saveScanTimestamps');
Route::get('/scan-timestamps/list', [App\Http\Controllers\ScanTimestampController::class, 'getTimestampsAsList'])->name('getTimestampsAsList');
Route::get('/scan-timestamps/getWatbillsForScanGroup', [App\Http\Controllers\ScanTimestampController::class, 'getDepartureScansToDispatch'])->name('getWatbillsForScanGroup');
Route::post('/scan-timestamps/{group_id}/end-scan', [App\Http\Controllers\ScanTimestampController::class, 'endScan'])->name('end-scan');
Route::post('/scan-timestamps/{group_id}/cancel', [App\Http\Controllers\ScanTimestampController::class, 'cancelScan'])->name('cancel');
Route::get('/manifests/dispatch/getWaybills-in', [App\Http\Controllers\ManifestsController::class, 'getWaybillsInForDispatch'])->name('getWaybillsIn');
Route::get('/manifest-compliance', [App\Http\Controllers\ManifestsController::class, 'manifestCompliance'])->name('manifestCompliance');
Route::get('/get-manifest-compliance', [App\Http\Controllers\ManifestsController::class, 'getSiteManifestCompliance'])->name('getSiteManifestCompliance');


Route::get('/manifest/get/all', [App\Http\Controllers\ManifestsController::class, 'getManifests'])->name('manifest.getManifests');

Route::get('/waybills', [App\Http\Controllers\WaybillsController::class, 'index'])->name('waybills.index');
Route::get('/getIncomingWaybills', [App\Http\Controllers\WaybillsController::class, 'getIncomingWaybills'])->name('getIncomingWaybills');
Route::get('/getDispatchedWaybills', [App\Http\Controllers\WaybillsController::class, 'getDispatchedWaybills'])->name('getDispatchedWaybills');
Route::get('/getDispatchedWaybillsSummary', [App\Http\Controllers\WaybillsController::class, 'getDispatchedWaybillsSummary'])->name('getDispatchedWaybillsSummary');
Route::get('/getIncomingWaybillsSummary', [App\Http\Controllers\WaybillsController::class, 'getIncomingWaybillsSummary'])->name('getIncomingWaybillsSummary');
Route::get('/getAcknowledgedWaybills', [App\Http\Controllers\WaybillsController::class, 'getAcknowledgedWaybills'])->name('getAcknowledgedWaybills');
Route::get('/getPendingWaybills', [App\Http\Controllers\WaybillsController::class, 'getPendingWaybills'])->name('getPendingWaybills');


Route::get('/getWaybillsArrivalStatusSummary', [App\Http\Controllers\WaybillsController::class, 'getWaybillsArrivalStatusSummary'])->name('getWaybillsArrivalStatusSummary');


Route::get('/waybill/Acknowledged', [App\Http\Controllers\WaybillsController::class, 'viewAcknowledgedWaybills'])->name('viewAcknowledgedWaybills');
Route::get('/waybill/pending', [App\Http\Controllers\WaybillsController::class, 'viewPendingWaybills'])->name('viewPendingWaybills');
Route::get('/waybill/incoming', [App\Http\Controllers\WaybillsController::class, 'viewincomingWaybills'])->name('viewIncomingWaybills');
Route::get('/waybill/dispatched', [App\Http\Controllers\WaybillsController::class, 'viewdispatchedWaybills'])->name('viewDispatchedWaybills');
Route::get('/waybill/incoming/summary', [App\Http\Controllers\WaybillsController::class, 'viewIncomingWaybillsSummary'])->name('viewIncomingWaybillsSummary');
Route::get('/waybill/dispatched/summary', [App\Http\Controllers\WaybillsController::class, 'viewDispatchedWaybillsSummary'])->name('viewDispatchedWaybillsSummary');

//waybills.getWaybills
Route::get('/waybills/all', [App\Http\Controllers\WaybillsController::class, 'getWaybills'])->name('waybills.getWaybills');

Route::get('/tools/waybills-insight', [App\Http\Controllers\ManifestsController::class, 'waybillsInsight'])->name('waybills.insights');
//K9
Route::get('/k9/scans/departure', [App\Http\Controllers\ManifestsController::class, 'k9_getDepartureScans'])->name('waybills.k9_getDepartureScans');
Route::get('/k9/departure-scans/{departure_site_id}/{destination_site_id}', [App\Http\Controllers\ManifestsController::class, 'k9_getDepartureList'])->name('manifest.k9_getDepartureList');
Route::get('/k9/waybills/departed', [App\Http\Controllers\ManifestsController::class, 'k9_getDepartedWaybills'])->name('waybills.k9_getDepartedWaybills');
Route::get('/k9/waybills/groups/create', [App\Http\Controllers\ManifestsController::class, 'k9_createWaybillGroup'])->name('waybills.k9_createWaybillGroup');
Route::get('k9/departure-list', [App\Http\Controllers\ManifestsController::class, 'getCurrentDayDepartureListForSite'])->name('k9_getCurrentDayDepartureListForSite');


//Scan Compliance
Route::get('/scan-compliance', [App\Http\Controllers\ScanComplianceController::class, 'index'])->name('scan-complaince');
Route::post('/scan-compliance', [App\Http\Controllers\ScanComplianceController::class, 'runChecks']);


// Sites
Route::get('/sites', [App\Http\Controllers\SitesController::class, 'index'])->name('sites.index');
Route::get('/sites/get-sites', [App\Http\Controllers\SitesController::class, 'getSites'])->name('sites.getSites');
Route::get('/sites/import', [App\Http\Controllers\SitesController::class, 'import'])->name('sites.import');
// Route::get('/sites/{manifest_id}', [App\Http\Controllers\SitesController::class, 'getSite'])->name('sites.getSiteById');
Route::post('/sites/import-process', [App\Http\Controllers\SitesController::class, 'processImport'])->name('sites.processImport');
Route::post('/sites/import-preview', [App\Http\Controllers\SitesController::class, 'previewImport'])->name('sites.previewImport');
Route::get('/sites/import-result', [App\Http\Controllers\SitesController::class, 'viewImportResult'])->name('sites.viewImportResults');
Route::get('/sites/{site_id}', [App\Http\Controllers\SitesController::class, 'getSite'])->name('sites.getSite');
Route::get('/sites/{site_id}/users', [App\Http\Controllers\SitesController::class, 'getSiteUsers'])->name('sites.getUsers');
Route::get('/sites/{site_id}/edit', [App\Http\Controllers\SitesController::class, 'edit'])->name('sites.edit');
Route::post('/sites', [App\Http\Controllers\SitesController::class, 'store'])->name('sites.store');

//Personal Messages
Route::get('/personal-messages', [App\Http\Controllers\PersonalMessageController::class, 'index'])->name('personal-messages.index');
Route::get('/personal-messages/{id}/read', [App\Http\Controllers\PersonalMessageController::class, 'read'])->name('personal-messages.read');

//Message
Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');

//Users
Route::get('/users/import', [App\Http\Controllers\UsersController::class, 'import'])->name('importUsers');
Route::post('/users/import-process', [App\Http\Controllers\UsersController::class, 'processImport'])->name('processUserImport');
Route::post('/users/import-preview', [App\Http\Controllers\UsersController::class, 'previewImport'])->name('previewUserImport');
Route::get('/users/import-result', [App\Http\Controllers\UsersController::class, 'viewImportResult'])->name('viewUserImportResults');

Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.manage');
Route::get('/users/get-users', [App\Http\Controllers\UsersController::class, 'getUsers'])->name('users.getUsers');
Route::get('/reset-password/{user_id}', [App\Http\Controllers\UsersController::class, 'resetUserPassword'])->name('users.resetUserPassword');
Route::get('/users/{user_id}/roles', [App\Http\Controllers\UsersController::class, 'getUserRoles'])->name('users.getUserRoles');


//Get all roles
//Regular request and Ajax
Route::get('/roles', [App\Http\Controllers\RolesController::class, 'index'])->name('roles.index');
Route::post('/roles', [App\Http\Controllers\RolesController::class, 'store'])->name('roles.store');
Route::post('/roles/{role_id}/disable', [App\Http\Controllers\RolesController::class, 'disable'])->name('roles.disable');
Route::post('/roles/{role_id}/enable', [App\Http\Controllers\RolesController::class, 'enable'])->name('roles.enable');
Route::post('/roles/{role_id}/users', [App\Http\Controllers\RolesController::class, 'getRoleUsers'])->name('roles.getRoleUsers');

Route::post('/sites/{site_id}/roles', [App\Http\Controllers\RolesController::class, 'getSiteUsersByRole'])->name('sites.getSiteUsersByRole');


Route::get('/test', function(){

    $username = 'sandbox'; // use 'sandbox' for development in the test environment
    $apiKey   = 'f62f213431c5a64677a6584d20b89b0fe659c7e0153a1ea1fd52fce91d4f4d57'; // use your sandbox app API key for development in the test environment
    $AT       = new AfricasTalking($username, $apiKey);

    // Get one of the services
    $sms      = $AT->sms();

    // Use the service
    $result   = $sms->send([
        'to'      => '+2347067011296',
        'message' => 'Testing sandbox services'
    ]);

    print_r($result);
});

Route::get('/sendmail', function(){
    Mail::to('emacy_245@yahoo.com')->send(new SendTest);
});


Route::get('/sendmail2', function(){
    Mail::to('emmanuel.imafidon@speedaf.com')
    ->send(new SendTest);
});


//Tarrifs
Route::get('/tarriff-quotation', [App\Http\Controllers\TarriffQuotationController::class, 'index']);
Route::get('/tarriff-quotation/{departure_location}/{destination_location}/{forwarding_location}/{percentage_discount}/{weight}', [App\Http\Controllers\TarriffQuotationController::class, 'getTarriffQuote']);
Route::post('/tarriff-quotation', [App\Http\Controllers\TarriffQuotationController::class, 'getTarriff'])->name('getTarriff');
Route::get('tarriff-forwarding-locations/{location_id}', [App\Http\Controllers\TarriffQuotationController::class, 'getForwardingLocations'])->name('getForwardingLocations');
Route::get('/tarriff/express', [App\Http\Controllers\TarriffQuotationController::class, 'tarriff'])->name('tarriff');
Route::get('/tarriffs/express/list', [App\Http\Controllers\TarriffQuotationController::class, 'getExpressTarriffs'])->name('getExpressTarriffs');
Route::get('/tarriff/zonnings/list', [App\Http\Controllers\TarriffQuotationController::class, 'getZonnings'])->name('getZonnings');
Route::get('/tarriff/zonnings', [App\Http\Controllers\TarriffQuotationController::class, 'zonnings'])->name('zonnings');


///
//tarriff-quotation/locations ---
//tarriff-quotation/locations/{1}
//tarriff-quotation/forward/{location_id}


Route::match(array('POST','GET'),'/track', 'WaybillsController@track');
Route::match(array('POST','GET'),'/trackOnK9', 'WaybillsController@trackOnK9');


//Scan Record Board
Route::match(array('GET'),'/waybills/arrived/scans', 'WaybillsController@getArrivedWaybillsScans')->name('StationArrivedWaybillsScanRecord');

// Route::post('/track', [App\Http\Controllers\WaybillsController::class, 'track']);
// Route::post('/trackOnK9', [App\Http\Controllers\WaybillsController::class, 'trackOnK9']);
// Route::get('/watch', [App\Http\Controllers\WatchWaybillController::class, 'index']);



//Email
// Route::get('send-mail', function () {

//     $details = "Test ID";

//     Mail::to('codemarshal08@gmail.com')->send(new \App\Mail\manifestCreated($details));

//     dd("Email is Sent.");
// });


// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

//POST IS MORE PREFFERED, JUST Use get first
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('password/change', [App\Http\Controllers\UsersController::class, 'showChangePasswordView'])->name('changePassword');
Route::post('password/change', [App\Http\Controllers\UsersController::class, 'changePassword'])->name('password.change');
Route::get('profile', [App\Http\Controllers\UsersController::class, 'ShowProfileView'])->name('profile');
Route::get('profile/{user_id}/edit', [App\Http\Controllers\UsersController::class, 'EditProfile'])->name('editProfile');
Route::post('profile', [App\Http\Controllers\UsersController::class, 'UpdateProfile'])->name('users.updateProfile');


//synchronization
Route::get('syncronize/{table_name}', [App\Http\Controllers\SynchronizationController::class, 'synchronizeDB'])->name('DB.synchronize');


// Route::get('synchronize/sites', [App\Http\Controllers\SynchronizationController::class, 'synchronizeDBsites'])->name('synchronize.sites');
// Route::get('synchronize/employees', [App\Http\Controllers\SynchronizationController::class, 'synchronizeDBemployees'])->name('synchronize.employees');
// Route::get('synchronize/index', [App\Http\Controllers\SynchronizationController::class, 'index'])->name('synchronize.index');

Route::get('test/view', [App\Http\Controllers\TestViewController::class, 'index']);


Route::get('/bags/register', [App\Http\Controllers\BagsController::class, 'register'])->name('bags.register');
Route::post('/bags/register', [App\Http\Controllers\BagsController::class, 'registerBags'])->name('bags.registerBags');
Route::get('/bags', [App\Http\Controllers\BagsController::class, 'index'])->name('bags.index');
Route::get('/bags/Onsite', [App\Http\Controllers\BagsController::class, 'getOnSiteBags'])->name('bags.onsite');
Route::get('/bags/transfer', [App\Http\Controllers\BagsController::class, 'transfer_view'])->name('bags.transfer');
Route::post('/bags/transfer', [App\Http\Controllers\BagsController::class, 'transferBags'])->name('bags.transferBags');


Route::get('/bags/transfer/{transfer_id}/acknowledge', [App\Http\Controllers\BagsController::class, 'acknowledgeView'])->name('transfers.acknowledgeView');
Route::post('/bags/transfer/acknowledge', [App\Http\Controllers\BagsController::class, 'acknowledgeTransfer'])->name('transfer.acknowledge');
Route::get('/bags/transfers/incoming', [App\Http\Controllers\BagsController::class, 'getIncomingTransfers'])->name('transfers.incoming');
Route::get('/bags/transfers/outgoing', [App\Http\Controllers\BagsController::class, 'getOutgoingTransfers'])->name('transfers.outgoing');
Route::get('/transfer/{transfer_id}/details', [App\Http\Controllers\BagsController::class, 'getTransferDetails'])->name('transfer.details');
Route::match(array('POST','GET'),'bags/track', 'BagsController@track')->name("bags.track");


//2022
Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
Route::get('/commission/pickup', [App\Http\Controllers\AccountController::class, 'pickUpParcelsCommission'])->name('commission.pickup');


///post payment or upload proof of payment route
Route::get('crs/postpayment', function (){ return view('crs.postpayment'); })->name('account.postpayment');

Route::get('crs/approvedpayment', function (){ return view('crs.approvedpayment'); })->name('account.approvedpayment');
Route::get('crs/pendingpayment', function (){ return view('crs.pendingpayment'); })->name('account.pendingpayment');
Route::get('crs/paymentview', function (){ return view('crs.paymentview'); })->name('account.paymentview');
