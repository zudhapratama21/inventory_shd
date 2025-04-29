<?php

namespace App\Http\Controllers\Permissions;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\HRD\Divisi;
use App\Models\Sales;

class UserController extends Controller

{

    use PasswordValidationRules;

    function __construct()
    {
        $this->middleware('permission:user-list');
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $users = User::with('roles')->get();
        $title = "USERS";
        //dd($users);
        if (request()->ajax()) {
            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function (User $row) {
                    return $row->getRoleNames()->toArray();
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('user.edit', ['user' => $row->id]);
                    $id = $row->id;
                    return view('master.user._formAction', compact('editUrl', 'id'));
                })
                ->make(true);
        }


        return view('master.user.index', compact('title'));
    }

    public function create()
    {
        $title = "Users";
        $user = new User;
        $roles = \Spatie\Permission\Models\Role::all();
        $role = "";

        $sales = Sales::get();
        $divisi = Divisi::get();

        $sales = Sales::get();
        return view('master.user.create', compact('title', 'user', 'roles', 'role','sales','divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],            
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now()->format('Y-m-d H:i:s')
        ]);
        $insertedId = $user->id;
        $user->assignRole($request->role_id);

        return redirect()->route('user.index')->with('status', 'User baru berhasil ditambahkan !');
    }

    public function edit(User $user)
    {
        $title = "Users";
        $users = new User;
        $roles = \Spatie\Permission\Models\Role::all();
        $sales = Sales::get();
        $divisi = Divisi::get();
        $role = $user->getRoleNames()->toArray();
        if ($role) {
            $role = $role[0];
        } else {
            $role = "";
        }        
        return view('master.user.edit', compact('title', 'user', 'users', 'roles', 'role','sales','divisi'));
    }

    public function update(Request $request, User $user)
    {
        $sales = $request->sales ? $request->sales : null;        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'sales_id' => $sales,
            'phone' => $request->phone,
            'divisi_id' => $request->divisi
        ]);

        if ($user->roles->first() <> null) {
            $user->removeRole($user->roles->first());
        }
        $user->assignRole($request->role_id);
        return redirect()->route('user.index')->with('status', 'Data User berhasil diubah !');
    }

    public function delete(Request $request)
    {
        $data = User::where('id', '=', $request->id)->get(['name'])->first();
        $id = $request->id;
        $name = $data->name;

        return view('master.user._confirmDelete', compact('name', 'id'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $customer = User::find($id);
        $customer->deleted_by = Auth::user()->id;
        $customer->save();

        User::destroy($request->id);

        return redirect()->route('user.index')->with('status', 'Data User Berhasil Dihapus !');
    }
}
