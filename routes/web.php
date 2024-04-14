<?php

use Illuminate\Support\Facades\Route;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['permissions.policy'])->group(function () {
    Route::middleware('referrer-policy')->group(function () {

Route::get('/login', function () {
    return view('admin.login');
});

Route::get('/4BMkMvsUzt', ['as' => '/4BMkMvsUzt', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@index']);
Route::post('/show-error', ['as' => '/show-error', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@show']);
Route::post('/resolve-error', ['as' => '/resolve-error', 'uses' => 'App\Http\Controllers\Admin\ErrorLogsController@resolve']);

Route::get('/error-handling', ['as' => 'error-handling', 'uses' => 'App\Http\Controllers\ErrorHandlingController@errorHandling']);

Route::get('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@index']);
Route::post('/submitLogin', ['as' => 'submitLogin', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@submitLogin']);

// ================================================
Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', ['as' => '/dashboard', 'uses' => 'App\Http\Controllers\Admin\Dashboard\DashboardController@index']);
    Route::get('/list-users', ['as' => 'list-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@index']);
    Route::get('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@addUsers']);
    Route::post('/add-users', ['as' => 'add-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@register']);
    Route::get('/edit-users/{edit_id}', ['as' => 'edit-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@editUsers']);
    Route::post('/update-users', ['as' => 'update-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@update']);
    Route::post('/delete-users', ['as' => 'delete-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@delete']);
    Route::post('/show-users', ['as' => 'show-users', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@show']);
    Route::get('/cities', ['as' => 'cities', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getCities']);
    Route::get('/states', ['as' => 'states', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getState']);
    Route::get('/check-email-exists', ['as' => 'check-email-exists', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@checkEmailExists']);
    Route::get('/check-aadhar-exists', ['as' => 'check-aadhar-exists', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@checkAadharExists']);

    Route::get('/district', ['as' => 'district', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getDistrict']);
    Route::get('/taluka', ['as' => 'taluka', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getTaluka']);
    Route::get('/village', ['as' => 'village', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getVillage']);


    Route::get('/list-projects', ['as' => 'list-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@index']);
    Route::get('/projects', ['as' => 'projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@ProjectsForMap']);
    Route::post('/update-active-projects', ['as' => 'update-active-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@updateOne']);
    Route::get('/add-projects', ['as' => 'add-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@addProjects']);
    Route::post('/add-projects', ['as' => 'add-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@store']);
    Route::get('/edit-projects/{edit_id}', ['as' => 'edit-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@editProjects']);
    Route::post('/update-projects', ['as' => 'update-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@update']);
    Route::post('/show-projects', ['as' => 'show-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@show']);
    Route::post('/delete-projects', ['as' => 'delete-projects', 'uses' => 'App\Http\Controllers\Admin\Project\ProjectController@delete']);


    Route::get('/list-labours', ['as' => 'list-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@index']);
    Route::post('/update-active-labours', ['as' => 'update-active-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@updateOne']);
    Route::post('/show-labours', ['as' => 'show-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@show']);
    Route::get('/list-labour-attendance', ['as' => 'list-labour-attendance', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@getLabourAttendanceList']);

    Route::get('/list-approved-labours', ['as' => 'list-approved-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@listApprovedLabours']);
    Route::get('/list-disapproved-labours', ['as' => 'list-disapproved-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@listDisapprovedLabours']);
    Route::get('/list-resubmitted-labours', ['as' => 'list-resubmitted-labours', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@listResubmitedLabours']);
    Route::post('/update-labour-status', ['as' => 'update-labour-status', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@updateLabourStatus']);
    Route::get('/list-labours-filter', ['as' => 'list-labours-filter', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@getFilterLabours']);
    Route::get('/list-labours-attendance-filter', ['as' => 'list-labours-attendance-filter', 'uses' => 'App\Http\Controllers\Admin\Menu\LaboursController@getFilterLaboursAttendance']);

//==================================================================================================================================

    Route::get('/list-gramsevak', ['as' => 'list-gramsevak', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@index']);
    Route::post('/show-gramsevak-doc', ['as' => 'show-gramsevak-doc', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@showGramsevakDocuments']);
    Route::get('/update-gram-document-status', ['as' => 'update-gram-document-status', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@updateGramDocumentStatus']);
    Route::get('/list-grampanchayat-doc', ['as' => 'list-grampanchayat-doc', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListGrampanchayatDocuments']);

    Route::get('/list-grampanchayt-doc-new', ['as' => 'list-grampanchayt-doc-new', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListGrampanchayatDocumentsNew']);
    Route::post('/show-gramsevak-doc-new', ['as' => 'show-gramsevak-doc-new', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@showGramsevakDocumentsNew']);
    Route::get('/list-sended-for-approval', ['as' => 'list-sended-for-approval', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListSendedForApproval']);

    Route::get('/list-grampanchayt-doc-approved', ['as' => 'list-grampanchayt-doc-approved', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListGrampanchayatDocumentsApproved']);
    Route::post('/show-gramsevak-doc-approved', ['as' => 'show-gramsevak-doc-approved', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@showGramsevakDocumentsApproved']);
    Route::get('/list-approved-documents', ['as' => 'list-approved-documents', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListApprovedDocuments']);

    Route::get('/list-grampanchayt-doc-not-approved', ['as' => 'list-grampanchayt-doc-not-approved', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListGrampanchayatDocumentsNotApproved']);
    Route::post('/show-gramsevak-doc-not-approved', ['as' => 'show-gramsevak-doc-not-approved', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@showGramsevakDocumentsNotApproved']);
    Route::get('/list-not-approved-documents', ['as' => 'list-not-approved-documents', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListNotApprovedDocuments']);

    Route::get('/list-grampanchayt-doc-resubmitted', ['as' => 'list-grampanchayt-doc-resubmitted', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListGrampanchayatDocumentsResubmitted']);
    Route::post('/show-gramsevak-doc-resubmitted', ['as' => 'show-gramsevak-doc-resubmitted', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@showGramsevakDocumentsResubmitted']);
    Route::get('/list-resubmitted-documents', ['as' => 'list-resubmitted-documents', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListResubmittedDocuments']);



    // Route::post('/list-sended-for-approval', ['as' => 'list-sended-for-approval', 'uses' => 'App\Http\Controllers\Admin\Gramsevak\GramsevakController@ListSendedForApproval']);

//==========================================================================================================================================================

    Route::get('/get-location-wise-project', ['as' => 'get-location-wise-project', 'uses' => 'App\Http\Controllers\Admin\Reports\LaboursController@getLocationWiseProjects']);




    Route::get('/list-project-wise-users', ['as' => 'list-project-wise-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@index']);
    Route::get('/add-project-wise-users', ['as' => 'add-project-wise-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@addProjectusers']);
    Route::post('/add-project-wise-users', ['as' => 'add-project-wise-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@store']);
    Route::get('/usertype-users', ['as' => 'usertype-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@getUserTypeUsers']);
    Route::post('/update-active-project_users', ['as' => 'update-active-project_users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@updateOne']);
    Route::get('/edit-project-wise-users/{edit_id}', ['as' => 'edit-project-wise-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@editProjectUsers']);
    Route::post('/update-project-wise-users', ['as' => 'update-project-wise-users', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@update']);
    Route::get('/list-project-wise-user-edit', ['as' => 'list-project-wise-user-edit', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@getProjectWiseUsers']);
    Route::get('/list-user-type-wise-user-edit', ['as' => 'list-user-type-wise-user-edit', 'uses' => 'App\Http\Controllers\Admin\ProjectUser\ProjectUserController@getUserTypetWiseUsers']);





    Route::post('/update-active-user', ['as' => 'update-active-user', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateOne']);
    // Route::get('/prof', ['as' => 'prof', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@getProf']);

    Route::get('/edit-user-profile', ['as' => 'edit-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@editUsersProfile']);

    Route::post('/update-user-profile', ['as' => 'update-user-profile', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateProfile']);

    Route::post('/otp-verification', ['as' => 'otp-verification', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\RegisterController@updateEmailOtp']);
    

    // Landing Start================  
    Route::get('/list-slide', ['as' => 'list-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@index']);
    Route::get('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@add']);
    Route::post('/add-slide', ['as' => 'add-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@store']);
    Route::get('/edit-slide/{edit_id}', ['as' => 'edit-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@edit']);
    Route::post('/update-slide', ['as' => 'update-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@update']);
    Route::post('/show-slide', ['as' => 'show-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@show']);
    Route::post('/delete-slide', ['as' => 'delete-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@destroy']);
    Route::post('/update-active-slide', ['as' => 'update-active-slide', 'uses' => 'App\Http\Controllers\Admin\Home\SliderController@updateOne']);

   
//=====Roles Route======
Route::get('/list-role', ['as' => 'list-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@index']);
Route::get('/add-role', ['as' => 'add-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@add']);
Route::post('/add-role', ['as' => 'add-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@store']);
Route::get('/edit-role/{edit_id}', ['as' => 'edit-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@edit']);
Route::post('/update-role', ['as' => 'update-role','uses' => 'App\Http\Controllers\Admin\Menu\RoleController@update']);
Route::post('/show-role', ['as' => 'show-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@show']);
Route::post('/delete-role', ['as' => 'delete-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@destroy']);
Route::post('/update-one-role', ['as' => 'update-one-role', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@updateOneRole']);
Route::post('/list-role-wise-permission', ['as' => 'list-role-wise-permission', 'uses' => 'App\Http\Controllers\Admin\Menu\RoleController@listRoleWisePermission']);

Route::get('/list-incident-type', ['as' => 'list-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@index']);
Route::get('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@add']);
Route::post('/add-incident-type', ['as' => 'add-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@store']);
Route::get('/edit-incident-type/{edit_id}', ['as' => 'edit-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@edit']);
Route::post('/update-incident-type', ['as' => 'update-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@update']);
Route::post('/show-incident-type', ['as' => 'show-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@show']);
Route::post('/delete-incident-type', ['as' => 'delete-incident-type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@destroy']);
Route::post('/update-one-incident_type', ['as' => 'update-one-incident_type', 'uses' => 'App\Http\Controllers\Admin\Master\IncidentTypeController@updateOne']);


Route::get('/list-gender', ['as' => 'list-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@index']);
Route::get('/add-gender', ['as' => 'add-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@add']);
Route::post('/add-gender', ['as' => 'add-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@store']);
Route::get('/edit-gender/{edit_id}', ['as' => 'edit-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@edit']);
Route::post('/update-gender', ['as' => 'update-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@update']);
Route::post('/show-gender', ['as' => 'show-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@show']);
Route::post('/delete-gender', ['as' => 'delete-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@destroy']);
Route::post('/update-one-gender', ['as' => 'update-one-gender', 'uses' => 'App\Http\Controllers\Admin\Master\GenderController@updateOne']);

Route::get('/list-maritalstatus', ['as' => 'list-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@index']);
Route::get('/add-maritalstatus', ['as' => 'add-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@add']);
Route::post('/add-maritalstatus', ['as' => 'add-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@store']);
Route::get('/edit-maritalstatus/{edit_id}', ['as' => 'edit-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@edit']);
Route::post('/update-maritalstatus', ['as' => 'update-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@update']);
Route::post('/show-maritalstatus', ['as' => 'show-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@show']);
Route::post('/delete-maritalstatus', ['as' => 'delete-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@destroy']);
Route::post('/update-one-maritalstatus', ['as' => 'update-one-maritalstatus', 'uses' => 'App\Http\Controllers\Admin\Master\MaritalstatusController@updateOne']);

Route::get('/list-relation', ['as' => 'list-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@index']);
Route::get('/add-relation', ['as' => 'add-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@add']);
Route::post('/add-relation', ['as' => 'add-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@store']);
Route::get('/edit-relation/{edit_id}', ['as' => 'edit-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@edit']);
Route::post('/update-relation', ['as' => 'update-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@update']);
Route::post('/show-relation', ['as' => 'show-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@show']);
Route::post('/delete-relation', ['as' => 'delete-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@destroy']);
Route::post('/update-one-relation', ['as' => 'update-one-relation', 'uses' => 'App\Http\Controllers\Admin\Master\RelationController@updateOne']);

Route::get('/list-skills', ['as' => 'list-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@index']);
Route::get('/add-skills', ['as' => 'add-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@add']);
Route::post('/add-skills', ['as' => 'add-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@store']);
Route::get('/edit-skills/{edit_id}', ['as' => 'edit-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@edit']);
Route::post('/update-skills', ['as' => 'update-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@update']);
Route::post('/show-skills', ['as' => 'show-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@show']);
Route::post('/delete-skills', ['as' => 'delete-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@destroy']);
Route::post('/update-one-skills', ['as' => 'update-one-skills', 'uses' => 'App\Http\Controllers\Admin\Master\SkillsController@updateOne']);

Route::get('/list-registrationstatus', ['as' => 'list-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@index']);
Route::get('/add-registrationstatus', ['as' => 'add-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@add']);
Route::post('/add-registrationstatus', ['as' => 'add-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@store']);
Route::get('/edit-registrationstatus/{edit_id}', ['as' => 'edit-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@edit']);
Route::post('/update-registrationstatus', ['as' => 'update-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@update']);
Route::post('/show-registrationstatus', ['as' => 'show-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@show']);
Route::post('/delete-registrationstatus', ['as' => 'delete-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@destroy']);
Route::post('/update-one-registrationstatus', ['as' => 'update-one-registrationstatus', 'uses' => 'App\Http\Controllers\Admin\Master\RegistrationstatusController@updateOne']);

Route::get('/list-documenttype', ['as' => 'list-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@index']);
Route::get('/add-documenttype', ['as' => 'add-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@add']);
Route::post('/add-documenttype', ['as' => 'add-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@store']);
Route::get('/edit-documenttype/{edit_id}', ['as' => 'edit-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@edit']);
Route::post('/update-documenttype', ['as' => 'update-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@update']);
Route::post('/show-documenttype', ['as' => 'show-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@show']);
Route::post('/delete-documenttype', ['as' => 'delete-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@destroy']);
Route::post('/update-one-documenttype', ['as' => 'update-one-documenttype', 'uses' => 'App\Http\Controllers\Admin\Master\DocumenttypeController@updateOne']);

Route::get('/list-usertype', ['as' => 'list-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@index']);
Route::get('/add-usertype', ['as' => 'add-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@add']);
Route::post('/add-usertype', ['as' => 'add-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@store']);
Route::get('/edit-usertype/{edit_id}', ['as' => 'edit-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@edit']);
Route::post('/update-usertype', ['as' => 'update-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@update']);
Route::post('/show-usertype', ['as' => 'show-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@show']);
Route::post('/delete-usertype', ['as' => 'delete-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@destroy']);
Route::post('/update-one-usertype', ['as' => 'update-one-usertype', 'uses' => 'App\Http\Controllers\Admin\Master\UsertypeController@updateOne']);


// Reports======================
Route::get('/list-location-report', ['as' => 'list-location-report', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getAllLabourLocation']);
Route::get('/list-labour-duration-report', ['as' => 'list-labour-duration-report', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getAllLabourDuration']);
Route::get('/list-project-report', ['as' => 'list-project-report', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getAllProjects']);
Route::get('/list-project-and-location-report', ['as' => 'list-project-and-location-report', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getAllProjectLocation']);

Route::get('/list-labours-filter-reports', ['as' => 'list-labours-filter-reports', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getFilterLaboursReport']);
Route::get('/list-project-wise-labour-reports', ['as' => 'list-project-wise-labour-reports', 'uses' => 'App\Http\Controllers\Admin\Reports\ReportsController@getFilterProjectsReport']);



// Route::get('/db-backup', ['as' => 'db-backup', 'uses' => 'App\Http\Controllers\DBBackup\DBBackupController@downloadBackup']);

Route::get('/log-out', ['as' => 'log-out', 'uses' => 'App\Http\Controllers\Admin\LoginRegister\LoginController@logout']);

Route::get('/list-district', ['as' => 'list-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@index']);
Route::get('/add-district', ['as' => 'add-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addDistrict']);
Route::post('/add-district', ['as' => 'add-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addDistrictInsert']);
Route::post('/update-active-dist', ['as' => 'update-active-dist', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateOneDistrict']);
Route::get('/edit-district/{edit_id}', ['as' => 'edit-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@editDistrict']);
Route::post('/update-district', ['as' => 'update-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateDistrict']);
Route::post('/delete-district', ['as' => 'delete-district', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@deleteDistrict']);




Route::get('/list-taluka', ['as' => 'list-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@getTalukaList']);
Route::get('/add-taluka', ['as' => 'add-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addTaluka']);
Route::post('/add-taluka', ['as' => 'add-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addTalukaInsert']);
Route::post('/update-active-taluka', ['as' => 'update-active-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateOneTaluka']);
Route::get('/edit-taluka/{edit_id}', ['as' => 'edit-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@editTaluka']);
Route::post('/update-taluka', ['as' => 'update-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateTaluka']);
Route::post('/delete-taluka', ['as' => 'delete-taluka', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@deleteTaluka']);



Route::get('/list-village', ['as' => 'list-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@getVillageList']);
Route::get('/add-village', ['as' => 'add-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addVillage']);
Route::post('/add-village', ['as' => 'add-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@addVillageInsert']);
Route::post('/update-active-village', ['as' => 'update-active-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateOneVillage']);
Route::get('/edit-village/{edit_id}', ['as' => 'edit-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@editVillage']);
Route::post('/update-village', ['as' => 'update-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@updateVillage']);
Route::post('/delete-village', ['as' => 'delete-village', 'uses' => 'App\Http\Controllers\Admin\Area\AreaController@deleteVillage']);


});

});
});