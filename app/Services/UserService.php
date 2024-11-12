<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserService
{
    public function dataTable()
    {
        if (Auth::user()->hasRole('admin')) {
            $data = User::with('roles')->get();
        } else {
            $data = User::where('id', '!=', Auth::user()->id)->with('roles')->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                $roles = $row->roles->pluck('name')->toArray();
                return implode(', ', $roles);
            })
            ->editColumn('saldo', function ($row) {
                return 'Rp ' . number_format($row->saldo, 0, '', '.');
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'aktif') {
                    return '<span class="badge bg-success">Aktif</span>';
                } else if ($row->status == 'ban') {
                    return '<span class="badge bg-danger">Banned</span>';
                } else {
                    return '<span class="badge bg-warning">Suspend</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update users')) {
                    $actionBtn = '<button type="button" id="edit-user" data-username="' . $row->username . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-pencil"></i></button>';
                }
                if (Gate::allows('delete users')) {
                    $actionBtn .= '<button type="button" id="delete-user" data-username="' . $row->username . '" class="deleteUser btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="d-flex">' . $actionBtn . '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            // create user
            $this->createUser($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'shop_name' => $data['shop_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'saldo' => formatRupiahToNumber($data['saldo']),
            'status' => $data['status'],
            'password' => $data['password'] ? bcrypt($data['password']) : bcrypt('password'),
        ]);

        // assign role
        if ($data['role']) {
            $role = Role::find($data['role']);
            $user->assignRole($role);
        } else {
            $user->assignRole('member');
        }

        return $user;
    }

    public function createUserProfile($data, $user)
    {
        $userProfile = UserProfile::create([
            'user_id' => $user->id,
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'alamat' => $data['alamat']
        ]);

        if (isset($data['image'])) {
            $image = $data['image'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/users'), $imageName);

            $userProfile->image = $imageName;
            $userProfile->save();
        }
    }

    public function update($data, $user)
    {
        DB::beginTransaction();

        try {

            // update user
            $this->updateUser($data, $user);

            // update user profile
            $this->updateUserProfile($data, $user);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil diubah.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal merubah data: ' . $e->getMessage()
            ];
        }
    }

    public function updateUser($data, $user)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
        ]);

        $role = Role::find($data['role']);
        $user->syncRoles([$role->id]);

        return $user;
    }

    public function updateUserProfile($data, $user)
    {
        $userProfile = $user->profile;

        $userProfile->update([
            'no_hp' => $data['no_hp'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'alamat' => $data['alamat']
        ]);

        // update image
        if (isset($data['image'])) {
            if ($userProfile && $userProfile->image) {
                $oldImagePath = public_path('assets/images/users/' . $userProfile->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $data['image'];
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/users'), $imageName);

            $userProfile->image = $imageName;
            $userProfile->save();
        }

        return $userProfile;
    }

    public function delete($user)
    {
        DB::beginTransaction();

        try {

            if ($user) {

                // delete user
                $this->deleteUser($user);

                // delete user roles
                $user->roles()->detach();

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Data berhasil dihapus.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteUser($user)
    {
        return $user->delete();
    }

    public function deleteUserProfile($userProfile)
    {
        $imagePath = null;
        if ($userProfile->image) {
            $imagePath = public_path('assets/images/users/' . $userProfile->image);
        }

        $userProfile->delete();

        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    public function userUpdate($data, $user)
    {
        DB::beginTransaction();

        try {

            if ($data['password']) {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'saldo' => formatRupiahToNumber($data['saldo']),
                    'status' => $data['status'],
                    'password' =>  bcrypt($data['password']),
                ]);
            } else {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'saldo' => formatRupiahToNumber($data['saldo']),
                    'status' => $data['status']
                ]);
            }

            $role = Role::find($data['role']);
            $user->syncRoles([$role->id]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil diubah.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal merubah data: ' . $e->getMessage()
            ];
        }
    }
}
