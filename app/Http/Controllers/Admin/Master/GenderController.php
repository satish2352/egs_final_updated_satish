<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gender;
use App\Http\Services\Admin\Master\GenderServices;
use Validator;
use Illuminate\Validation\Rule;
class GenderController extends Controller
{

   public function __construct()
    {
        $this->service = new GenderServices();
    }
    public function index()
    {
        try {
            $gender_data = $this->service->getAll();
            return view('admin.pages.master.gender.list-gender', compact('gender_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.gender.add-gender');
    }

    public function store(Request $request) {
        $rules = [
            'gender_name' => 'required|unique:gender|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:gender|max:255',
         ];
        $messages = [   
            'gender_name'       =>  'Please enter title.',
            'gender_name.regex' => 'Please  enter text only.',
            'gender_name.unique' => 'Title already exist.',
            'gender_name.max'   => 'Please  enter text length upto 255 character only.',         
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-gender')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_gender_data = $this->service->addAll($request);
                if($add_gender_data)
                {

                    $msg = $add_gender_data['msg'];
                    $status = $add_gender_data['status'];
                    if($status=='success') {
                        return redirect('list-gender')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-gender')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-gender')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $gender_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.gender.edit-gender', compact('gender_data'));
   
    }
    // public function update(Request $request)
    // {
    //     $rules = [
    //         // 'gender_name' => 'required|unique:gender|regex:/^[a-zA-Z\s]+$/u|max:255',
    //         // 'marathi_title' => 'required|unique:gender|max:255',
    //         'gender_name'      => ['required','max:255',Rule::unique('gender', 'name')->ignore($this->id, 'id')],
    //         'marathi_title'      => ['required','max:255',Rule::unique('gender', 'name')->ignore($this->id, 'id')]
           
    //      ];
    //     $messages = [   
    //         'gender_name'       =>  'Please  enter english title.',
    //         'gender_name.regex' => 'Please  enter text only.',
    //         'gender_name.unique' => 'Title already exist.',
    //         'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    //         'gender_name.max'   => 'Please  enter text length upto 255 character only.',
    //         'marathi_title'       =>'कृपया शीर्षक प्रविष्ट करा.',
    //         'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
    //     ];


    //     try {
    //         $validation = Validator::make($request->all(),$rules, $messages);
    //         if ($validation->fails()) {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->withErrors($validation);
    //         } else {
    //             $update_gender_data = $this->service->updateAll($request);
    //             if ($update_gender_data) {
    //                 $msg = $update_gender_data['msg'];
    //                 $status = $update_gender_data['status'];
    //                 if ($status == 'success') {
    //                     return redirect('list-gender')->with(compact('msg', 'status'));
    //                 } else {
    //                     return redirect()->back()
    //                         ->withInput()
    //                         ->with(compact('msg', 'status'));
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }

    public function update(Request $request)
{
    $id = $request->input('id'); // Assuming the 'id' value is present in the request
    $rules = [
        'gender_name' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('gender', 'gender_name')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('gender', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'gender_name.required' => 'Please enter the title.',
        'gender_name.regex' => 'Please  enter text only.',
        'gender_name.max' => 'Please enter an  title with a maximum of 255 characters.',
        'gender_name.unique' => 'The title already exists.',
        // 'marathi_title.required' => 'कृपया  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.max' => 'कृपया २५५ अक्षरांपर्यंत  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_gender_data = $this->service->updateAll($request);

            if ($update_gender_data) {
                $msg = $update_gender_data['msg'];
                $status = $update_gender_data['status'];

                if ($status == 'success') {
                    return redirect('list-gender')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        }
    } catch (Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    }
}

    public function show(Request $request)
    {
        try {
            $gender_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.gender.show-gender', compact('gender_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-gender')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $gender_data = $this->service->deleteById($request->delete_id);
            if ($gender_data) {
                $msg = $gender_data['msg'];
                $status = $gender_data['status'];
                if ($status == 'success') {
                    return redirect('list-gender')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    } 

}