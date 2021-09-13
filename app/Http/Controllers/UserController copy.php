<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Designation;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designation = DB::table('designations')->get();
        // dd($designation);
        return view('/add_employee',['designation'=>$designation]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //////////////////////////////////////////////////////////////////////
        if (request()->has('photo')) {    
            $img_name= time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $img_name);
        }

        $user =  new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->photo = "/images/" . $img_name;
        $user->designation = $request->designation;
        $user->save();
        $user_id = $user->id;
// dd($user_id);
        //////////////////////////////////////////////////////////////////////



        $name = $request->name;
        $email = $request->email;
        $designation = $request->designation;
        $photo = $request->photo;
// dd($photo);
        // if(!empty($photo))
        // {
        
        //     // $imageName = time().'.'.$request->photo->extension();
     
        //     $request->photo->move(public_path('images'), 'test');
        // }
        $validator  = \Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:100',
        ]);

        if(!empty($photo)){

            // $destinationPath = '/public/images';
            // $files = $photo; // will get all files
            // $file_name = $files->getClientOriginalName(); 
            // $files->move($destinationPath , $file_name); 

            $img= time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $img);
        }
        // dd($name,$email,$designation, $photo);//5120

        if(!is_null($image)) {
            return back()->with('success','Success! image uploaded');
        }

        else {
            return back()->with("failed", "Alert! image not uploaded");
        }
      

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // dd("here");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
