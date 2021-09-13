<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Designation;
use DB;
use Illuminate\Support\Facades\Hash;

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

        $validator  = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'photo' => ['image|mimes:jpg,png,jpeg,gif,svg|max:100'],
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
        // $user->save();
        $random_password = \Str::random(12);

        \Mail::to('sumidevadas@gmail.com')
                ->send("test");
        // $details = [
        //     'to' => $email,
        //     'from' => env('MAIL_USERNAME'),
        //     'subject' => 'Test registration',
        //     'title' => 'Test registration',
        //     'body'  => 'Your account has been created. Password is: '.$random_password
        // ];


        // \Mail::to($details['to'])->send(new \App\Mail\RegisterMail($details));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('/view_employee');
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
