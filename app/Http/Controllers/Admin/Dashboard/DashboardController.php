<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use App\Models\ {
    Permissions,
    User,
    RTI,
    VacanciesHeader,
    DepartmentInformation,
    DisasterForcast,
    Gallery,
    Video,
    Event,
    Project
};
use Validator;

class DashboardController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        // $this->service = new DashboardServices();
    }

    public function index()
    {
        $return_data = array();
        $dashboard_data = Permissions::where("is_active",'=',true)->get()->toArray();
        // dd($dashboard_data);
        foreach ($dashboard_data as $value) {

            if($value['url'] == 'list-users') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $users = User::all();
                $data_dashboard['count'] = $users->count();
                array_push($return_data, $data_dashboard);
            }

            if($value['url'] == 'list-projects') {
                $data_dashboard['url'] =  $value['url'];
                $data_dashboard['permission_name'] =  $value['permission_name'];
                $projects = Project::all();
                $data_dashboard['count'] = $projects->count();
                array_push($return_data, $data_dashboard);
            }

           
        }

        return view('admin.pages.dashboard',compact('return_data'));
    }



}