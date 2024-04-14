<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skills;
use App\Http\Services\Admin\Master\SkillsServices;
use Validator;
use Illuminate\Validation\Rule;
class SkillsController extends Controller
{

   public function __construct()
    {
        $this->service = new SkillsServices();
    }
    public function index()
    {
        try {
            $skills_data = $this->service->getAll();
            return view('admin.pages.master.skills.list-skills', compact('skills_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.skills.add-skills');
    }

    public function store(Request $request) {
        $rules = [
            'skills' => 'required|unique:skills|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:skills|max:255',
         ];
        $messages = [   
            'skills'       =>  'Please enter title.',
            'skills.regex' => 'Please  enter text only.',
            'skills.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'skills.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-skills')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_skills_data = $this->service->addAll($request);
                if($add_skills_data)
                {

                    $msg = $add_skills_data['msg'];
                    $status = $add_skills_data['status'];
                    if($status=='success') {
                        return redirect('list-skills')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-skills')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-skills')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $skills_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.skills.edit-skills', compact('skills_data'));
   
    }
    // public function update(Request $request)
    // {
    //     $rules = [
    //         // 'skills' => 'required|unique:skills|regex:/^[a-zA-Z\s]+$/u|max:255',
    //         // 'marathi_title' => 'required|unique:skills|max:255',
    //         'skills'      => ['required','max:255',Rule::unique('skills', 'name')->ignore($this->id, 'id')],
    //         'marathi_title'      => ['required','max:255',Rule::unique('skills', 'name')->ignore($this->id, 'id')]
           
    //      ];
    //     $messages = [   
    //         'skills'       =>  'Please  enter english title.',
    //         'skills.regex' => 'Please  enter text only.',
    //         'skills.unique' => 'Title already exist.',
    //         'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    //         'skills.max'   => 'Please  enter text length upto 255 character only.',
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
    //             $update_skills_data = $this->service->updateAll($request);
    //             if ($update_skills_data) {
    //                 $msg = $update_skills_data['msg'];
    //                 $status = $update_skills_data['status'];
    //                 if ($status == 'success') {
    //                     return redirect('list-skills')->with(compact('msg', 'status'));
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
        'skills' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('skills', 'skills')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('skills', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'skills.required' => 'Please enter an title.',
        'skills.regex' => 'Please  enter text only.',
        'skills.max' => 'Please enter an  title with a maximum of 255 characters.',
        'skills.unique' => 'The title already exists.',
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
            $update_skills_data = $this->service->updateAll($request);

            if ($update_skills_data) {
                $msg = $update_skills_data['msg'];
                $status = $update_skills_data['status'];

                if ($status == 'success') {
                    return redirect('list-skills')->with(compact('msg', 'status'));
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
            $skills_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.skills.show-skills', compact('skills_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-skills')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $skills_data = $this->service->deleteById($request->delete_id);
            if ($skills_data) {
                $msg = $skills_data['msg'];
                $status = $skills_data['status'];
                if ($status == 'success') {
                    return redirect('list-skills')->with(compact('msg', 'status'));
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