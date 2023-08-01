<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User as AdminUsers;
use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Support\Arr;
use DataTables;


class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::pluck('name', 'name')->all();
        $data = AdminUsers::orderBy('id', 'DESC')->get();
        if ($request->ajax()) {
            $dataTable = DataTables::of($data)
                ->addColumn('roles', function ($user) {
                    $roles = $user->getRoleNames();
                    return !empty($roles) ? implode(', ', $roles->all()) : '-';
                })
                ->addColumn('user_image', function ($user) {
                    if ($user->image) {
                        return '<img src="' . asset('/img/user/' . $user->image) . '" alt="' . $user->name . '" width="50" height="50">';
                    } else {
                        return '<img src="https://avatars.dicebear.com/api/adventurer/' . $user->name . '.svg" alt="' . $user->name . '" width="50" height="50">';
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<button class="btn btn-sm btn-icon edit-admin-user" type="button" data-id="' . $data->id . '"><i class="text-secondary ti ti-edit"></i></button>
                      <button class="btn btn-sm btn-icon delete-admin-user" type="button" data-id="' . $data->id . '" data-name="' . $data->name . '"><i class="text-secondary ti ti-trash"></i></button>';
                })
                ->rawColumns(['user_image', 'action'])

                ->make(true);
            return $dataTable;
        }
        return view('cms.users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $userId = $request->user_id;
        $hasPwd = $request->password;
        $rules = [
            'name' => 'required',
            'roles' => 'required',
        ];
        if (!$userId) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|same:confirm_password';
        } else {
            $rules['email'] = 'required|email';
            if (!$hasPwd) {
                $rules['password'] = '';
            } else {
                $rules['password'] = 'same:confirm_password';
            }
        }
        $this->validate($request, $rules);
        if ($request->hasFile('image')) {
            $imageName = date('YmdHis') . "." . $request->image->getClientOriginalExtension();
            $destinationPath = 'img/user/';
            $request->file('image')->move($destinationPath, $imageName);
            $input = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'image' => date('YmdHis') . "." . $request->image->getClientOriginalExtension()
            ];
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $user = AdminUsers::updateOrCreate(['id' => $userId], $input);
            $user->assignRole($request->input('roles'));
        } else {
            $input = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ];
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $user = AdminUsers::updateOrCreate(['id' => $userId], $input);
            $user->assignRole($request->input('roles'));
        }

        return response()->json(['data' => 'Sukses']);
    }

    public function edit($id)
    {
        $data = AdminUsers::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $data->roles->pluck('name', 'name')->all();
        return response()->json([
            'data' => $data,
            'roles' => $roles,
            'userRole' => $userRole
        ]);
    }

    public function destroy($id)
    {
        $data = AdminUsers::where('id', $id)->first(['image']);
        \File::delete('img/user/' . $data->image);
        AdminUsers::find($id)->delete();
        return response()->json(array(
            'status' => true
        ));
    }
}
