<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Admin\Package;
use App\Models\Admin\PaymentGateway;
use App\Models\User;
use App\Services\Membership\MembershipService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * user managment page
     */
    public function index()
    {
        $data['users'] = User::latest()->paginate(10);
        $data['packages'] = Package::select('id', 'title', 'term')
            ->where('status', 1)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->title . ' (' . $item->term . ')'];
            })
            ->toArray();

        $data['gateways'] = PaymentGateway::select('keyword', 'name')
            ->where('status', 1)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->keyword => $item->name];
            });

        return view('admin.user-managment.index', $data);
    }

    /**
     * user store
     */
    public function store(UserStoreRequest $request, MembershipService $membershipService)
    {
        $package = Package::findOrFail($request->package_id);
        //send data for payment and store all nessary database data
        $data = [
            'added_by' => 'admin',
            'package' => $package ?? [],
            'purpose' => 'membership',
            'email' => $request->email,
            'name' => $request->fullname,
            'username' => $request->username,
            'password' => $request->password,
            'payment_method' => $request->payment_method,
            'company_name' => $request->company_name
        ];
        $dataObject = (object) $data;
        $membershipService->createMembership($dataObject);

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
    public function delete(Request $request, UserService $userService)
    {
        $userService->deleteUser($request->user_id);
        session()->flash('success', __('User delete successfully'));
        return back();
    }

    /**
     * bulk delete user
     */
    public function bulkDelete(Request $request, UserService $userService)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $userService->deleteUser($id);
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
