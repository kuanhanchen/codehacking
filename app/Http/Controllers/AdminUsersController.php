<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersEditRequest;
use App\Http\Requests\UsersRequest;
use App\Photo;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // pull out data as an array
        $roles = Role::lists('name', 'id')->all();
        return view('admin.users.create', compact('roles'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //if password exists
        if(trim($request->password) == ''){

            $input = $request->except('password');

        } else{

            $input = $request->all();

            $input['password'] = bcrypt($request->password);

        }


        //if photo exists
        if($file = $request->file('photo_id')) {

            $name = time() . $file->getClientOriginalName();

            // move file to images folder
            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;

        }


        User::create($input);

        return redirect('/admin/users');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);

        $roles = Role::lists('name', 'id')->all();

        return view('admin.users.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {
        //
        $user = User::findOrFail($id);

        if(trim($request->password) == ''){

            $input = $request->except('password');

        } else{

            $input = $request->all();

            $input['password'] = bcrypt($request->password);

        }


        if($file = $request->file('photo_id')){

            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;

        }


        $user->update($input);

        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);

        // in order to delete the image in public/images folder

        // The public_path() function returns the fully qualified path to the public directory:
        // public_path => /Users/KuanHanChen/Sites/laravel/codehacking/public

        // cuz of getFileAttribute($photo) in Photo.php,
        // $user->photo->file => http://localhost/~kuanhanchen/laravel/codehacking/public/images/file_name.jpg;
        $splitFileName = explode('/', $user->photo->file);
        $fileName = $splitFileName[sizeof($splitFileName)-1];

        //fileName => file_name.jpg
        //unlink(/Users/KuanHanChen/Sites/laravel/codehacking/public/images/file_name.jpg)
        unlink(public_path() . '/images/' . $fileName);

        $user->delete();

        Session::flash('deleted_user', 'The user has been deleted');

        return redirect('/admin/users');
    }
}
