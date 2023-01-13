<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gender;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
// use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.user_profile_list')->with('users', $users);
    }

    public function create()
    {
        return view('admin.user_profile_create')
            ->with('genders', Gender::all())
            ->with('departments', Department::all())
            ->with('roles', Role::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'integer', 'exists:genders,id'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'file' => ['file', 'image', 'max:5120'],
            'role' => [
                'array',
                'max:3'
            ],
            'role.*' => [
                'nullable',
                'integer',
                'distinct',
                'exists:roles,id'
            ]
        ]);

        $file_path = null;
        $file_name = null;

        $create_user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender_id' => $request->gender,
            'department_id' => $request->department,
            'avatar_file_path' => $file_path,
            'avatar_file_name' => $file_name,
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $this->createFolder($this->getAvatarPath());
            $original_file_name = $request->file('file')->getClientOriginalName();
            $file_name = $this->createUniqueFileName($create_user->id, $original_file_name);
            $request->file('file')->move($this->getAvatarPath(), $file_name);

            // $path = 'documents';
            // if (!File::exists($path)) {
            //     File::makeDirectory($path, 0777, true, true);
            // }
            // $path .= '/avatar';
            // if (!File::exists($path)) {
            //     File::makeDirectory($path, 0777, true, true);
            // }

            // $random_str = Str::random(32);
            // $time_now = Carbon::now();
            // $write_time = $time_now->format('YmdHis');
            // $original_file_name = $request->file('file')->getClientOriginalName();
            // $file_name = $random_str.'_'.$write_time.'_'.$create_user->id.'_'.$original_file_name;

            // $request->file('file')->move($path, $file_name);

            $create_user->avatar_file_path = $this->getAvatarPath();
            $create_user->avatar_file_name = $file_name;
            $create_user->save();
        }

        if (is_array($request->role) && count($request->role) > 0) {
            foreach ($request->role as $r) {
                if (!empty($r) && $r > 0) {
                    $create_user->user_roles()->create([
                        'role_id' => $r
                    ]);
                }
            }
        }
        return redirect()->route('users.show', ['user' => $create_user->id]);
    }

    public function show(User $user)
    {
        return view('admin.user_profile_show')
            ->with('user', $user)
            ->with('genders', Gender::all())
            ->with('departments', Department::all());
    }

    public function edit(User $user)
    {
        return view('admin.user_profile_edit')
            ->with('user', $user)
            ->with('genders', Gender::all())
            ->with('departments', Department::all())
            ->with('roles', Role::all());
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'integer', 'exists:genders,id'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'file' => ['file', 'image', 'max:5120'],
            'role' => [
                'array',
                'max:3'
            ],
            'role.*' => [
                'nullable',
                'integer',
                'distinct',
                'exists:roles,id'
            ]
        ]);

        $update_user = $user;
        $update_user->email = $request->email;
        $update_user->first_name = $request->first_name;
        $update_user->last_name = $request->last_name;
        $update_user->gender_id = $request->gender;
        $update_user->department_id = $request->department;

        if (!empty($request->password)) {
            $update_user->password = Hash::make($request->password);
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            if ($update_user->avatar_file_path != null && $update_user->avatar_file_name != null) {
                $this->deleteFile($update_user->avatar_file_path.$update_user->avatar_file_name);
            }

            $this->createFolder($this->getAvatarPath());

            $original_file_name = $request->file('file')->getClientOriginalName();
            $file_name = $this->createUniqueFileName($update_user->id, $original_file_name);

            $request->file('file')->move($this->getAvatarPath(), $file_name);
            $update_user->avatar_file_path = $this->getAvatarPath();
            $update_user->avatar_file_name = $file_name;
        }

        $update_user->save();

        $update_user->user_roles()->delete();

        if (is_array($request->role) && count($request->role) > 0) {
            foreach ($request->role as $r) {
                if (!empty($r) && $r > 0) {
                    $update_user->user_roles()->create([
                        'role_id' => $r
                    ]);
                }
            }
        }
        return redirect()->route('users.show', ['user' => $update_user->id]);
    }

    public function destroy(User $user)
    {
        $delete_user = $user;
        $name = $delete_user->email;
        $delete_user->user_roles()->delete();
        $delete_user->complaints()->update(['user_id' => null]);
        $delete_user->complaint_loggings()->update(['user_id' => null]);

        if ($delete_user->avatar_file_path != null && $delete_user->avatar_file_name != null) {
            $this->deleteFile($delete_user->avatar_file_path.$delete_user->avatar_file_name);
        }

        $delete_user->delete();

        return redirect()->route('users.index')->with('action_message', 'Successfully to delete user: '.$name.' !');
    }
}
