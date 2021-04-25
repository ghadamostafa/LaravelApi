<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.Credentials')->only(['store','resend']);
        $this->middleware('auth:api')->except(['store','verify','resend']);
        $this->middleware('transform.input:'.UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users=User::all();
        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ])->validate();
        // $this->validate($request,$rules);
        $data=$request->all();
        $data['password']=bcrypt($request->password);
        $data['verified']=User::UNVERIFIED_USER;
        $data['verification_token']=User::generateVerificationCode();
        $data['admin']=User::REGULAR_USER;
        $user=User::create($data);
        return $this->showOne($user,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $rules = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,id'.$user->id,
            'password' => 'min:6|confirmed',
            'admin'=>'in:'.User::REGULAR_USER.','.User::ADMIN_USER
        ])->validate();
        // $this->validate($request,$rules);
        if($request->has('name'))
        {
            $user->name=$request->name;
        }
        if($request->has('email')&&($user->email != $request->email))
        {
            $user->verified=User::UNVERIFIED_USER;
            $user->email=$request->email;
            $user->verification_token=User::generateVerificationCode();
        }
        if($request->has('password'))
        {
            $user->password=bcrypt($request->password);
        }
        if($request->has('admin'))
        {
            if(!$user->verified)
            {
                return $this->errorResponse('Only verified users can modify admin field',409);
            }
            $user->admin=$request->admin;
        }
        if(!$user->isDirty())
        {
            return $this->errorResponse('You need to specify a different value to update',422);

        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user=User::where('verification_token',$token)->firstOrFail();
        $user->verified=User::VERIFIED_USER;
        $user->verification_token=null;
        $user->save();
        return $this->showMessage('your email is verified');

    }

    public function resend(User $user)
    {
        if($user->isVerified())
        {
            return $this->errorResponse('The user is already verified',409);
        }
        Mail::to($user->email)->send(new UserCreated($user));
        return $this->showMessage('The verification email has been resent');

    }
}
