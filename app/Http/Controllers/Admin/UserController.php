<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserStoreRequest;

class UserController extends Controller
{
    /**
     * user managment page
     */
    public function index()
    {
        $data['users'] = User::latest()->paginate(10);
        return view('admin.user-managment.index', $data);
    }

    /**
     * user store
     */
    public function store(UserStoreRequest $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session()->flash('success', __('Added successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * user edit page
     */
    public function edit(Request $request, $id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.user-managment.edit', $data);
    }

    /**
     * update account status
     */
    public function statusUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->status == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }
        return redirect()->back()->with('success', __('Update account status successfully'));
    }
    /**
     * update email verified status
     */
    public function emailStatusUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->email_status == 1) {
            $user->update(['email_verified_at' => Carbon::now(), 'remember_token' => NULL]);
        } else {
            $user->update(['email_verified_at' => NULL, 'remember_token' => NULL]);
        }
        return redirect()->back()->with('success', __('Email verified successfully'));
    }
    /**
     * delete user
     */
    public function delete(Request $request)
    {
        Order::where('user_id', $request->user_id)->delete();
        User::findOrFail($request->user_id)->delete();
        session()->flash('success', __('User delete successfully'));
        return back();
    }

    /**
     * bulk delete user
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            Order::where('user_id', $id)->delete();
            User::findOrFail($id)->delete();
        }
        session()->flash('success', __('Users delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * display password change form
     */
    public function passwordChange($id)
    {
        $user_id = $id;
        return view('admin.user-managment.change-password', compact('user_id'));
    }

    /**
     * update user password
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success', __('Password updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * secret admin login as user
     */
    public function secretLogin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        // Logout current admin if any
        Auth::guard('web')->logout();

        // Login as the target user
        Auth::guard('web')->login($user, true);

        session()->flash('success', __('You have successfully logged in as user'));
        return redirect()->route('user.dashboard');
    }
}
