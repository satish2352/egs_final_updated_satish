<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usertype;
use App\Http\Services\Admin\Master\UsertypeServices;
use Validator;
use Illuminate\Validation\Rule;
class UsertypeController extends Controller
{

   public function __construct()
    {
        $this->service = new UsertypeServices();
    }
    public function index()
    {
        try {
            $usertype_data = $this->service->getAll();
            return view('admin.pages.master.usertype.list-usertype', compact('usertype_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.usertype.add-usertype');
    }

    public function store(Request $request) {
        $rules = [
            'usertype_name' => 'required|unique:usertype|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:usertype|max:255',
         ];
        $messages = [   
            'usertype_name'       =>  'Please enter title.',
            'usertype_name.regex' => 'Please  enter text only.',
            'usertype_name.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'usertype_name.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-usertype')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_usertype_data = $this->service->addAll($request);
                if($add_usertype_data)
                {

                    $msg = $add_usertype_data['msg'];
                    $status = $add_usertype_data['status'];
                    if($status=='success') {
                        return redirect('list-usertype')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-usertype')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-usertype')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $usertype_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.usertype.edit-usertype', compact('usertype_data'));
   
    }
    // public function update(Request $request)
    // {
    //     $rules = [
    //         // 'usertype_name' => 'required|unique:usertype|regex:/^[a-zA-Z\s]+$/u|max:255',
    //         // 'marathi_title' => 'required|unique:usertype|max:255',
    //         'usertype_name'      => ['required','max:255',Rule::unique('usertype', 'name')->ignore($this->id, 'id')],
    //         'marathi_title'      => ['required','max:255',Rule::unique('usertype', 'name')->ignore($this->id, 'id')]
           
    //      ];
    //     $messages = [   
    //         'usertype_name'       =>  'Please  enter english title.',
    //         'usertype_name.regex' => 'Please  enter text only.',
    //         'usertype_name.unique' => 'Title already exist.',
    //         'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    //         'usertype_name.max'   => 'Please  enter text length upto 255 character only.',
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
    //             $update_usertype_data = $this->service->updateAll($request);
    //             if ($update_usertype_data) {
    //                 $msg = $update_usertype_data['msg'];
    //                 $status = $update_usertype_data['status'];
    //                 if ($status == 'success') {
    //                     return redirect('list-usertype')->with(compact('msg', 'status'));
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
        'usertype_name' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('usertype', 'usertype_name')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('usertype', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'usertype_name.required' => 'Please enter an title.',
        'usertype_name.regex' => 'Please  enter text only.',
        'usertype_name.max' => 'Please enter an  title with a maximum of 255 characters.',
        'usertype_name.unique' => 'The title already exists.',
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
            $update_usertype_data = $this->service->updateAll($request);

            if ($update_usertype_data) {
                $msg = $update_usertype_data['msg'];
                $status = $update_usertype_data['status'];

                if ($status == 'success') {
                    return redirect('list-usertype')->with(compact('msg', 'status'));
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
            $usertype_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.usertype.show-usertype', compact('usertype_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-usertype')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $usertype_data = $this->service->deleteById($request->delete_id);
            if ($usertype_data) {
                $msg = $usertype_data['msg'];
                $status = $usertype_data['status'];
                if ($status == 'success') {
                    return redirect('list-usertype')->with(compact('msg', 'status'));
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