<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registrationstatus;
use App\Http\Services\Admin\Master\RegistrationstatusServices;
use Validator;
use Illuminate\Validation\Rule;
class RegistrationstatusController extends Controller
{

   public function __construct()
    {
        $this->service = new RegistrationstatusServices();
    }
    public function index()
    {
        try {
            $registrationstatus_data = $this->service->getAll();
            return view('admin.pages.master.registrationstatus.list-registrationstatus', compact('registrationstatus_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.registrationstatus.add-registrationstatus');
    }

    public function store(Request $request) {
        $rules = [
            'status_name' => 'required|unique:registrationstatus|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:status_name|max:255',
         ];
        $messages = [   
            'status_name'       =>  'Please enter title.',
            'status_name.regex' => 'Please  enter text only.',
            'status_name.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'status_name.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-registrationstatus')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_registrationstatus_data = $this->service->addAll($request);
                if($add_registrationstatus_data)
                {

                    $msg = $add_registrationstatus_data['msg'];
                    $status = $add_registrationstatus_data['status'];
                    if($status=='success') {
                        return redirect('list-registrationstatus')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-registrationstatus')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-registrationstatus')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $registrationstatus_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.registrationstatus.edit-registrationstatus', compact('registrationstatus_data'));
   
    }
    // public function update(Request $request)
    // {
    //     $rules = [
    //         // 'registrationstatus' => 'required|unique:registrationstatus|regex:/^[a-zA-Z\s]+$/u|max:255',
    //         // 'marathi_title' => 'required|unique:registrationstatus|max:255',
    //         'registrationstatus'      => ['required','max:255',Rule::unique('registrationstatus', 'name')->ignore($this->id, 'id')],
    //         'marathi_title'      => ['required','max:255',Rule::unique('registrationstatus', 'name')->ignore($this->id, 'id')]
           
    //      ];
    //     $messages = [   
    //         'registrationstatus'       =>  'Please  enter english title.',
    //         'registrationstatus.regex' => 'Please  enter text only.',
    //         'registrationstatus.unique' => 'Title already exist.',
    //         'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    //         'registrationstatus.max'   => 'Please  enter text length upto 255 character only.',
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
    //             $update_registrationstatus_data = $this->service->updateAll($request);
    //             if ($update_registrationstatus_data) {
    //                 $msg = $update_registrationstatus_data['msg'];
    //                 $status = $update_registrationstatus_data['status'];
    //                 if ($status == 'success') {
    //                     return redirect('list-registrationstatus')->with(compact('msg', 'status'));
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
        'registrationstatus' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('registrationstatus', 'registrationstatus')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('registrationstatus', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'registrationstatus.required' => 'Please enter an title.',
        'registrationstatus.regex' => 'Please  enter text only.',
        'registrationstatus.max' => 'Please enter an  title with a maximum of 255 characters.',
        'registrationstatus.unique' => 'The title already exists.',
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
            $update_registrationstatus_data = $this->service->updateAll($request);

            if ($update_registrationstatus_data) {
                $msg = $update_registrationstatus_data['msg'];
                $status = $update_registrationstatus_data['status'];

                if ($status == 'success') {
                    return redirect('list-registrationstatus')->with(compact('msg', 'status'));
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
            $registrationstatus_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.registrationstatus.show-registrationstatus', compact('registrationstatus_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-registrationstatus')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $registrationstatus_data = $this->service->deleteById($request->delete_id);
            if ($registrationstatus_data) {
                $msg = $registrationstatus_data['msg'];
                $status = $registrationstatus_data['status'];
                if ($status == 'success') {
                    return redirect('list-registrationstatus')->with(compact('msg', 'status'));
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