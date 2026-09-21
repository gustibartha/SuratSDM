<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\User;
use File;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = 25;
        $user = User::latest()->paginate($perPage);
        $data['user'] = $user;
        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);
        $requestData = $request->all();
        $requestData['password'] = bcrypt($request->password);
        User::create($requestData);
        Alert::success('New ' . 'User' . ' Created!');

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $data['user'] = $user;
        return view('admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($request->password != null) {
            $request['password'] = bcrypt($request->password);
        } else {
            $request['password'] = $user->password;
        }
        $requestData = $request->all();
        if($request->hasFile('file_ttd'))
        {
            $this->validate($request, [
                'file_ttd' => 'image|mimes:png|max:4096'
            ]);
            $file= $request->file('file_ttd');
            $image_name = $file->getClientOriginalName();
            if($user->file_ttd){
                unlink(public_path('ttd_file/'.$user->file_ttd));
                $file->move(public_path('ttd_file/'),$image_name);
            }else{
                $file->move(public_path('ttd_file/'),$image_name);
            }
            $requestData['file_ttd'] = $image_name;
        }
        // dd($requestData);
        Alert::success('Record Updated!');
        $user->update($requestData);

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Alert::success('Record Deleted!');
        User::destroy($id);

        return redirect('admin/users');
    }
}
