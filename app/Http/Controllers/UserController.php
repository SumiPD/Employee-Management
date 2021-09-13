<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Designation;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\RegisterMail;
use Mail;

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
      
        $name = $request->name;
        $email = $request->email;
        $designation = $request->designation;
        $photo = $request->photo;

        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'photo' => ['mimes:jpg,png,jpeg,gif,svg|max:5120'],
        ]);     

        if(!empty($photo)){

            $img= time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $img);
        }else{
            $img = '';
        }

        $user =  new User();
        $user->name = $name;
        $user->email = $email;
        $user->designation  =$designation;
        $user->photo =$img;
        $user->save();
        $random_password = \Str::random(12);

        $email = $email;
        $mailData = [
            'title' => 'User Registered',
            'url' => 'https://www.remotestack.io',
            'body'  => 'Your account has been created Password is:'.$random_password
        ];
  
        Mail::to($email)->send(new RegisterMail($mailData));

        // dd("Mail sent!");
        return redirect()->back()->with('success','inserted');

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // dd("hi");
        // return view('/view_employee');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,$id)
    {
        // dd($id);
        $designations = DB::table('designations')->get();
        $users = DB::table('users')->where('id',$id)->first();
        // dd($users);
        return view('/edit_employee',['designations'=>$designations,'users'=>$users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updates(Request $request, User $user)
    {
        // dd();
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'photo' => ['mimes:jpg,png,jpeg,gif,svg|max:5120'],
        ]); 
        if($request->photo == ""){

            DB::update('update users set name = ?,email=?,designation=? where id = ?',[$request->name,$request->email,$request->designation,$request->id]);

        }else{

            $img= time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $img);
            DB::update('update users set name = ?,email=?,designation=?,photo=? where id = ?',[$request->name,$request->email,$request->designation,$img, $request->id]);

        }

        $user->update($request->all());
        $message ="User details updated successfully";
        return redirect()->back()->with('success',$message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,User $user)
    {
        
        DB::delete('delete from users WHERE id = ?', [$request->id]);
        return redirect()->back()->with('success','deleted');
        // dd($request->id);
    }
    public function view()
    {
        $employees = DB::table('users')
                    ->join('designations','users.designation','=','designations.id')
                    ->select('users.*', 'designations.name As designation')
                    ->get();
        // dd($employees);
        return view('view_employee',['employees'=>$employees]);
    }
}
