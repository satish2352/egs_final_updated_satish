<?php
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Models\ {
	MainMenus,
    MainSubMenus,
    DynamicWebPages,
    SocialIcon,
    WebsiteContact,
    PolicyPrivacy,
    User,
    WheatherForecast
};
use Illuminate\Support\Facades\Storage;


function getIPAddress($req)
{
    return $req->ip();
}

function getLanguageSelected() {
    $language = '';
    if (Session::get('language') == 'mar') {
        $language = Session::get('language');
    } else {
        $language = 'en';
    }
    return $language;
}
function getRouteDetailsPresentOrNot($data_for_session) {
    $data =[];
    foreach ($data_for_session as $value_new) {
        array_push($data,$value_new['url']);
    }
    // dd($data);
    Session::put('data_for_url', $data);
    return $data;
}

function getPermissionForCRUDPresentOrNot($url,$data_for_session) {
    $data =[];
    if(session('role_id') =='1') {
        array_push($data,'per_list');
        array_push($data,'per_add');
        array_push($data,'per_update');
        array_push($data,'per_delete');
    } else {
        foreach ($data_for_session as $value_new) {
        
            if($value_new['url'] == $url) {
                info($value_new);
                foreach ($value_new as $key => $value) {
                    info($value);
                    if($value == 1) {
                        array_push($data,$key);
                    }
                }
                return $data;
            }
        }
    }
    return $data;
}

// function getMenuItems() {

//     $menu_data = array();
//     $main_menu_data =  MainMenus::where('is_active', '=',true)
//                         ->select( 
//                             'main_menuses.menu_name_marathi', 
//                             'main_menuses.menu_name_english',
//                             'main_menuses.id',
//                             'main_menuses.url', 
//                             'main_menuses.is_static', 
//                         )
//                         ->get()
//                         ->toArray();
//     foreach($main_menu_data as $key=>$main_menu_data_all) {
//         $subMenus ='';
//         $menu_data_raw = array();
//         array_push($menu_data_raw,$main_menu_data_all);
//         $subMenus  = MainSubMenus::where('main_menu_id', '=',$main_menu_data_all['id'])
//                                     ->where('is_active', '=',true)
//                                     ->select( 
//                                         'main_sub_menuses.main_menu_id',
//                                         'main_sub_menuses.menu_name_marathi',
//                                         'main_sub_menuses.menu_name_english',
//                                         'main_sub_menuses.url', 
//                                         'main_sub_menuses.is_static', 
//                                     )
//                                     ->get()
//                                     ->toArray();
//         array_push($menu_data_raw,$subMenus);
//         array_push($menu_data,$menu_data_raw);
        
//     }
//     return $menu_data ;
    
                
// }

// function getMenuItemsForDynamicPageAdd() {


//     $menu_data = array();
//     $main_menu_data =  MainMenus::where('main_menuses.is_static', '=',false)
//                                 ->where('is_active', '=',true)
//                                 ->select( 
//                                     'main_menuses.menu_name_marathi', 
//                                     'main_menuses.menu_name_english',
//                                     'main_menuses.url as main_menu_url',
//                                     'main_menuses.id as menu_id',
//                                     'main_menuses.is_static as main_menu_static',
//                                     'main_menuses.main_sub'
//                                 )
//                                 ->get()
//                                 ->toArray();
        
                        
//         $subMenus  = MainSubMenus::where('main_sub_menuses.is_static', '=',false)
//                                     ->where('is_active', '=',true)
//                                     ->select( 
//                                         'main_sub_menuses.id as menu_id',
//                                         'main_sub_menuses.menu_name_marathi',
//                                         'main_sub_menuses.menu_name_english',
//                                         'main_sub_menuses.url as sub_menu_url', 
//                                         'main_sub_menuses.is_static as sub_menu_static', 
//                                         'main_sub_menuses.main_sub'
//                                     )
//                                     ->get()
//                                     ->toArray();
      
//                                     $menu_data = array_merge($main_menu_data, $subMenus);
//     return $menu_data ;
    
                
// }

// function savePageNameInMenu($main_sub, $id, $url, $menu_name, $publish_date,$english_title,$marathi_title,$meta_data, $final_content_english, $final_content_marathi) {


//     if($main_sub =='main') {
//         $main_menu_data =  MainMenus::where('id', '=', $id)
//                                     ->update([ 
//                                         'url'=> $url,
//                                         'is_static'=> false
//                                     ]);

        
//         addOrUpdateDynamicWebPages($main_sub, $id, $url, $menu_name, $publish_date,$english_title,$marathi_title,$meta_data, $final_content_english, $final_content_marathi);
            
//     } else {
//         $subMenus  = MainSubMenus::where('id', '=', $id)
//                                     ->update([ 
//                                         'url'=> $url,
//                                         'is_static'=> false
//                                     ]);

//         addOrUpdateDynamicWebPages($main_sub, $id, $url, $menu_name, $publish_date,$english_title,$marathi_title,$meta_data, $final_content_english, $final_content_marathi);
                                   
//     }
//     return 'ok';
// }

// function addOrUpdateDynamicWebPages($main_sub,$id,$url,$menu_name, $publish_date,$english_title,$marathi_title,$meta_data, $final_content_english, $final_content_marathi) {

//         $dynamic_web_page_name = DynamicWebPages::where('is_active',true)
//                                                 ->where('menu_id',$id)
//                                                 ->where('menu_type',$main_sub)
//                                                 ->first();
//         if($dynamic_web_page_name) {
//             $dynamic_web_page_name = DynamicWebPages::where('is_active',true)
//                                                     ->where('menu_id',$id)
//                                                     ->where('menu_type',$main_sub)

//                                                     ->update([
//                                                                 'slug'=> $url,
//                                                                 'page_content_english'=> $final_content_english,
//                                                                 'page_content_marathi'=> $final_content_marathi,
//                                                                 'menu_name' =>$menu_name,
//                                                                 'publish_date' =>$publish_date,
//                                                                 'english_title' =>$english_title,
//                                                                 'marathi_title' =>$marathi_title,
//                                                                 'meta_data' =>$meta_data,
//                                                             ]);
//         } else {
//             $data_for_insert = [
//                 'slug'=> $url,
//                 'page_content_english'=> $final_content_english,
//                 'page_content_marathi'=> $final_content_marathi,
//                 'menu_name' => $menu_name,
//                 'menu_id' => $id,
//                 'publish_date' => $publish_date,
//                 'english_title' =>$english_title,
//                 'marathi_title' =>$marathi_title,
//                 'meta_data' =>$meta_data,
//             ];

//             if($main_sub =='main') { 
//                 $data_for_insert['menu_type']= 'main';     
//             } else {
//                 $data_for_insert['menu_type']= 'sub';   
//             }

//             $dynamic_web_page_name = DynamicWebPages::insert($data_for_insert);
//         }
// }

// function getMenuItemsDynamicPageDetailsById($id) {


//     return  DynamicWebPages::where('is_active',true)
//                                 ->where('id',$id)
//                                 ->select( 
//                                     'id',
//                                     'menu_type',
//                                     'menu_id',
//                                     'menu_name',
//                                     'slug',
//                                     'publish_date',
//                                 )
//                                 ->first();
        
                        
       
                
// }


// function uploadImage($request, $image_name, $path, $name) {

//     // if (!file_exists(storage_path().$path)) {
//     //     File::makeDirectory(storage_path().'/'.$path,0777,true);
//     // }
//     // if($request->$image_name !== null) {
//     //     $base64_encoded = base64_encode(file_get_contents($request->$image_name)); 
//     //     $base64_decoded_content = base64_decode($base64_encoded);
//     //     $path2 = storage_path().$path.$name;
//     //     file_put_contents($path2, $base64_decoded_content);
//     // }


//     $filePath = $path.$name;
//     $path = Storage::disk('s3')->put($filePath, file_get_contents($request->$image_name));
//     $path = Storage::disk('s3')->url($path);

// }

// function removeImage($path) {
//     return Storage::disk('s3')->delete($path);
// }

// function file_exists_s3($path) {
//     return Storage::disk('s3')->exists($path);
// }



function uploadImage($request, $image_name, $path, $name) {
    // Check if the directory exists, create it if not
    if (!file_exists(storage_path($path))) {
        mkdir(storage_path($path), 0777, true);
    }

    if ($request->hasFile($image_name)) {
        // Save the file locally
        $request->file($image_name)->move(storage_path($path), $name);
    }
}

function removeImage($path) {
    // Delete the file locally
    if (file_exists(storage_path($path))) {
        unlink(storage_path($path));
    }
}

function file_exists_view($path) {
    // Check if the file exists locally
    return file_exists(storage_path($path));
}
// function getTempratureFromAPI() {
//     $return_data = array();
//     $url = env('TEMPRATURE_API_URL');
//     $result  =file_get_contents($url);
//     $data_for_WheatherForecast = json_decode($result, true);
//     $return_data['temprature'] = $data_for_WheatherForecast['currentConditions']['temp'];
//     $return_data['forecast'] = $data_for_WheatherForecast['days'];
//     return $return_data;
// }

// function getTempratureData() {

//     $data = WheatherForecast::where('id','1')
//                     ->get()->first();
//     $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

//     $current_date = $date->format('Y-m-d H:i:s');
//     if($data) {
//         $last_update = $data->date_time;   
//         $diff = (strtotime($current_date) - strtotime($last_update))/60;

//         if ($diff < 60) {
//             $return_forecast_data = WheatherForecast::where('id','1')
//                     ->get()->first();
//         } else {
//             $data = getTempratureFromAPI();
//             $db_date['date_time'] = $current_date;
//             $db_date['temprature'] = $data['temprature'];
//             $db_date['forecast'] = serialize(processForecastData($data['forecast']));
//             WheatherForecast::where('id','1')->update($db_date);
//             $return_forecast_data = WheatherForecast::where('id','1')
//                     ->get()->first();
//         }

//     } else {
       
//         $data = getTempratureFromAPI();
//         $db_date['date_time'] = $current_date;
//         $db_date['temprature'] = $data['temprature'];
//         $db_date['forecast'] = serialize(processForecastData($data['forecast']));
//         WheatherForecast::insert($db_date);
//         $return_forecast_data = WheatherForecast::where('id','1')
//                     ->get()->first();
                   
//     }

//     return $return_forecast_data;
   
// }


// ==============
// function getMenuForSearch(Request $request) {
  
//     $query = $request->input('query');
//     $menu_data_search = array();
//     $main_menu_data = MainMenus::where('is_active', true)
//         ->where(function ($queryBuilder) use ($query) {
//             if ($query) {
//                 $queryBuilder->where('menu_name_marathi', 'like', '%' . $query . '%')
//                     ->orWhere('menu_name_english', 'like', '%' . $query . '%');
//             }
//         })
//         ->select(
//             'main_menuses.menu_name_marathi',
//             'main_menuses.menu_name_english',
//             'main_menuses.id',
//             'main_menuses.url',
//             'main_menuses.is_static',
//         )
//         ->get()
//         ->toArray();
  
//     // Rest of the code remains the same...
    
//     return $menu_data_search;
// }

// function getProfileImage()
// {
//     $user_detail = User::where('is_active', true)
//         ->where('id', session()->get('user_id'))
//         ->select('id', 'f_name', 'm_name', 'l_name', 'email', 'password', 'number', 'designation','user_profile')
//         ->first();
//     // echo $user_detail;
//     // die();
//     return $user_detail;
// }

// function processForecastData($forecast_data) {
//         $return_array = array();
        
//         foreach ($forecast_data as $key => $forecast) {
//             $data_array = array();
//             $data_array_hours = array();
//             $data_array['datetime'] = $forecast['datetime'];
//             $data_array['conditions'] = $forecast['conditions'];
//             $data_array['description'] = $forecast['description'];
//             $data_array['sunrise'] = $forecast['sunrise'];
//             $data_array['sunset'] = $forecast['sunset'];
//             $data_array_hourwise = array();
//             $min_temp = '';
//             $max_temp = '';
//             foreach ($forecast['hours'] as $key=>$dataforecast_day_wise) {
//                 $data_array_hours['datetime'] = $dataforecast_day_wise['datetime'];
//                 $data_array_hours['temp'] = $dataforecast_day_wise['temp'];
//                 if($min_temp == '') {
//                     $min_temp = $dataforecast_day_wise['temp'];
//                 } else if($min_temp > $dataforecast_day_wise['temp']) {
//                     $min_temp = $dataforecast_day_wise['temp'];
//                 }

//                 if($max_temp == '') {
//                     $max_temp = $dataforecast_day_wise['temp'];
//                 } else if($max_temp < $dataforecast_day_wise['temp']) {
//                     $max_temp = $dataforecast_day_wise['temp'];
//                 }
//                 $data_array_hourwise[$key] = $data_array_hours;
//             }
//             $data_array['min_temp'] = $min_temp;
//             $data_array['max_temp'] = $max_temp;
//             $data_array['hour_wise'] = $data_array_hourwise;
//             array_push($return_array,$data_array);
//         }
//         return $return_array;
// }




















