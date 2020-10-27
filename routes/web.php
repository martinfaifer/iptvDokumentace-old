<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// overeni zda je user zalogovan
Route::get('/api/getUser', 'UserController@getUser');
Route::post('/api/user/login', 'UserController@logIn');
Route::get('/api/user/logout', 'UserController@logOut');
Route::get('/api/users/get', 'UserController@get');
Route::post('/api/user/create', 'UserController@create');
Route::post('/api/user/changePass', 'UserController@changePass');
Route::post('/api/user/get', 'UserController@getUserData');
Route::post('/api/user/edit', 'UserController@edit');
Route::post('/api/user/delete', 'UserController@delete');

// získání náhled
Route::get('/api/nahled/get', 'ApiSystemUrlController@getNahledFromDohled');
Route::post('/api/channelHistory', 'ApiSystemUrlController@getHistoryName');


// testovací fn pro API
Route::get('/api/getAlerts', 'ApiSystemUrlController@getIPTVAlert');

// UserRoles
Route::get('/api/roles/get', 'UserRoleController@getRoles');

// dnesni datum
Route::get('/api/calendar/currDate', 'CalendarController@currDate');

Route::get('/api/channels/get', 'ChannelsController@get');
Route::post('/api/channel/get/multicast', 'ChannelsController@getChannelMulticastInfo');
Route::post('/api/channel/get/h264', 'ChannelsController@getChannelh264Info');
Route::post('/api/channel/get/h265', 'ChannelsController@getChannelH265Info');
Route::post('/api/channel/save/iptvPackage', 'ChannelsController@savePackage');
Route::post('/api/channel/package/delete', 'ChannelsController@removePackage');
Route::post('api/channel/note/edit', 'ChannelsController@editNote');

Route::post('/api/channel/get/tags', 'ChannelsController@getTags');

Route::post('/api/channel/getNahled', 'ChannelsController@getNahled');
Route::get('/api/channels/hls/kvality', 'ApiSystemUrlController@getKvalityFromTranscoder');


Route::get('/api/devices/get', 'DeviceController@get');
Route::post('/api/device/get', 'DeviceController@getDeviceRoute');

// vendors
Route::get('/api/vendors/get', 'DeviceVendorController@all');
// device categorie
Route::get('/api/categories/get', 'DeviceCategorieController@all');

// isp
Route::get('/api/isps/get', 'IspController@get');

// devices
Route::get('/api/device/multiplexers', 'DeviceCategorieController@getMultiplexer');
Route::get('/api/prijem/get', 'DeviceCategorieController@getPrijem');
Route::get('/api/devices', 'DeviceController@getDevices');
Route::get('/api/rf/get', 'BlankomPortController@getPorts');
Route::get('/api/device/transcoder', 'DeviceCategorieController@getTranscoder');
Route::post('/api/device/create', 'DeviceController@create');

Route::post('/api/device/get/tags', 'DeviceController@getTags');


// Prirazení multiplexeru ke kanálu
Route::post('/api/channel/multiplexer/set', 'ChannelsController@setMultiplexer');
// Odebrání multiuplexeru
Route::post('/api/channel/multiplexer/delete', 'ChannelsController@deleteMultiplexer');
// ulození zarizení na prijem multicastu
Route::post('/api/channel/savePrijem', 'DeviceController@savePrijem');
// ulozeni zaloznihop prijimace
Route::post('/api/channel/saveBackupPrijem', 'DeviceController@saveBackupPrijem');
// editace názvu kanálu
Route::post('/api/channel/name/edit', 'ChannelsController@editChannelName');
// editace isp, multicasotvé adresy z prijimace a k stb
Route::post('/api/channel/isp/edit', 'IspChannelController@editIspData');
// odebrání multicastových dat
Route::post('/api/channel/isp/delete', 'IspChannelController@deleteIspData');
// odebrání prijimace od kanálu
Route::post('/api/channel/prijem/delete', 'DeviceController@deletePrijem');
// odebrani backup priujimace
Route::post('/api/channel/backupprijem/delete', 'DeviceController@deleteBackupPrijem');
// pridani noveho multicastu
Route::post('/api/channel/isp/add', 'IspChannelController@addIspData');
// editace jiz stavajiciho transcoderu
Route::post('/api/h264/transcoder/edit', 'H264Controller@editTranscoder');

// h265 editace jiz zalozeného transcoderu
Route::post('/api/h265/transcoder/edit', 'H265Controller@editTranscoder');
// editace jiz stavajicich vstupu
Route::post('/api/h265/output/edit', 'H265Controller@editOutput');
// editace jiz stavajicich vystupu do h264
Route::post('/api/h264/output/edit', 'H264Controller@editOutput');
// smazani h265
Route::post('/api/channel/h265/delete', 'H265Controller@delete');
// ulpzeni novych h265
Route::post('/api/h265/output/add', 'H265Controller@addOutput');
// ulozeni novehých dat na H264
Route::post('/api/h264/output/add', 'H264Controller@addOutput');
// smazaní hls dat
Route::post('/api/channel/h264/delete', 'H264Controller@delete');
// editace multiplexeru
Route::post('/api/device/multiplexer/edit', 'DeviceController@editMultiplexer');
// zakladni editace blankomu
Route::post('/api/device/blankom/edit', 'DeviceController@basicEditBlankom');
// editace jednotlivych rf
Route::post('/api/device/blankom/rf/edit', 'DeviceController@editBlankomRf');
// zakladni editace FTE
Route::post('/api/device/fte/edit', 'DeviceController@basicEditFte');
// editace satelitni casti fte
Route::post('/api/device/fte/sat/edit', 'DeviceController@editFteSat');
// editace nsp
Route::post('/api/device/nsp/edit', 'DeviceController@editNsp');
// editace grape transcoderu
Route::post('/api/device/grapeTranscoder/edit', 'DeviceController@editTranscoder');
// editace linuxu
Route::post('/api/device/linux/edit', 'DeviceController@editLinux');
// editace po  IP
Route::post('/api/device/PoIp/edit', 'DeviceController@editPoIp');
// editace powerVu
Route::post('/api/device/powerVu/edit', 'DeviceController@editPowerVu');
// disable device
Route::post('/api/device/remove', 'DeviceController@removeDevice');
// vytvoreni noveho kanalu
Route::post('/api/channel/create', 'ChannelsController@create');
// smazání kanálu
Route::post('/api/channel/delete', 'ChannelsController@delete');


// Notifikace
Route::post('/api/notification/submit', 'NotificationAlertNewController@submitNotification');

Route::get('/api/tree', 'TopicCategorieController@get');
Route::post('/api/topic/get', 'TopicsController@getTopic');
Route::post('/api/topic/edit', 'TopicsController@edit');
Route::post('/api/topic/create', 'TopicsController@create');
Route::post('/api/topic/remove', 'TopicsController@remove');



// Routa pro vytvoreni pdf vsech kanalu
Route::get('/api/channels/pdf', 'ChannelsController@channelsPDF')->name('channelPDF.pdf');


// IPTV balicky
Route::post('/api/iptvpackage/create', 'IPTVpackageController@create');
Route::get('/api/iptvpackage/getAll', 'IPTVpackageController@getAll');
Route::post('/api/iptvpackage', 'IPTVpackageController@getChannelByPackage');


// CA modul
Route::get('/api/ca_modul/get', 'CAModulController@get');
// Pocet kanaálu na CA modulu
Route::get('/api/ca_modul/channels/get', 'CAModulChannelController@get');



// API
Route::get('/api/communacation/channel', 'ApiController@getChannelDataForExternalSystem');
Route::get('/api/communication/channels/monitor', 'ApiController@getChannelDataForDohled');
// overení ze se dohled pripojil do dokumentace
Route::get('/api/connectionTest', 'ApiController@connectionTest_dohled');

Route::get('/api/get_img_from_dohled', 'ApiSystemUrlController@getImgFromDohled');
Route::post('/api/getChannelDataFromDohled', 'ApiSystemUrlController@checkIfChannelExistAndReturnChannelDataOrFalseStatus');
Route::post('/api/getVolumeDataFromDohledForChart', 'ApiSystemUrlController@checkIfChannelExistAndReturnChartVolumeDataOrFalseStatus');
Route::post('/api/getBitrateDataFromDohledForChart', 'ApiSystemUrlController@checkIfChannelExistAndReturnChartBitrateDataOrFalseStatus');
Route::post('/api/getChannelHistoryFromDohled', 'ApiSystemUrlController@checkIfChannelExistAndReturnChannelHistory');
Route::post('/api/storeChannelToDohled', 'ApiSystemUrlController@storeChannelToDohled');
// Výpis informací o streamu
Route::get('/api/channel/search', 'ApiController@get_information_about_channel');
// Výpis všech kanálu pro založení do dohledu
Route::get('/api/channel/return', 'ApiController@return_channels_for_adding_to_dohled');


/**
 * TAGS
 */
Route::get('/api/tags/get', 'TagController@show');
// ulozeni tagu ke kanálu
Route::post('/api/channel/tag/add', 'ChannelsController@addTag');
// ulozeni tagu k zarizeni
Route::post('/api/device/tag/add', 'DeviceController@addTag');
// odebrani tagu od kanalu
Route::post('/api/channel/delete/tag', 'ChannelsController@removeTag');
// odebrání tagu od zarizeni
Route::post('/api/device/delete/tag', 'DeviceController@removeTag');

// vytvoreni noveho stitku do systemu
Route::post('/api/settings/tag/create', 'TagController@createTagForChannel');
// odebrání stítku ze systému
Route::post('/api/settings/tag/remove', 'TagController@remove');
//AUTOMATICKE RESTARTOVÁNÍ KANÁLŮ
Route::post('/api/channel/tryToRestartChannel', 'ChannelsController@checkIfChannelHaveTagForActions');


// http://iptvdokumentace.test/api/channel/tryToRestartChannel?dohledUrl=http://93.91.154.54:10224/udp/239.251.21.17:1234


Route::get('/api/search', 'SearchController@search');
