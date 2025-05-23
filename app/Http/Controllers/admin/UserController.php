<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        if (!empty($request->get('keyword'))) {
            $users = $users->where('name' , 'like','%'.$request->get('keyword').'%');
            $users = $users->orWhere('email' , 'like','%'.$request->get('keyword').'%');  
        }

        $users = $users->paginate(10);  

        return view('admin.users.list',[
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->password = hash::make($request->password);
            $user->save();

            $message = 'User added successfully!';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit(Request $request , $id)
    {   
        $user = User::find($id);
        if($user == null)
        {   
            $message = 'User Not Found!';
            session()->flash('error' , $message);
            return redirect()->route('users.index');
        }

        return view('admin.users.edit' ,[
            'user' => $user,
        ]);
    }

    public function update(Request $request , $id)
    {   
        $user = User::find($id);

        if($user == null)
        {   
            $message = 'User Not Found!';
            session()->flash('error' , $message);
            return redirect()->route('users.index');

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;

            if($request->password != '')
            {
                $user->password = hash::make($request->password);
            }
            $user->save();

            $message = 'User updated successfully!';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if($user == null)
        {   
            $message = 'User Not Found!';
            session()->flash('error' , $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $user->delete();
        $message = 'User Delete successfully!';
            session()->flash('success' , $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
    }
}
