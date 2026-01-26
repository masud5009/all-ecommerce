<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Models\Admin;
use App\Models\Admin\Role;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\TranslateService;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    //admin dashboard
    public function dashboard(TranslateService $translateService)
    {
        $data['total_earning'] = Transaction::sum('actual_total');
        $data['total_daily_earning'] = Transaction::whereDate('created_at', today())->sum('actual_total');
        $data['order_count'] = Order::selectRaw('count(*) as total_orders,
                          sum(order_status = "completed") as total_order_completed,
                          sum(order_status = "pending") as total_order_pending,
                          sum(order_status = "rejected") as total_order_rejected,
                          sum(DATE(created_at) = CURDATE()) as daily_total_orders,
                          sum(DATE(created_at) = CURDATE() && order_status = "pending") as today_pending_order
                          ')
            ->first();

        //monthly sales for chart
        $monthWiseTotaSales = DB::table('orders')
            ->select(
                DB::raw('month(created_at) as month'),
                DB::raw('COUNT(id) as total'),
            )
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        //monthly earning for chart
        $monthWiseTotalIncomes = DB::table('transactions')
            ->select(
                DB::raw('month(created_at) as month'),
                DB::raw('SUM(actual_total) as total'),
            )
            ->where('payment_status', 'completed')
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $months = [];
        $monthlyIncome = [];
        $monthlyOrder = [];

        for ($i = 1; $i <= 12; $i++) {
            // get all 12 months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            // get all 12 months's income
            $incomeFound = false;
            foreach ($monthWiseTotalIncomes as $order) {
                if ($order->month == $i) {
                    $incomeFound = true;
                    array_push($monthlyIncome, $order->total);
                    break;
                }
            }
            if ($incomeFound == false) {
                array_push($monthlyIncome, 0);
            }
            // get all 12 months's orders
            $incomeFound = false;
            foreach ($monthWiseTotaSales as $sales) {
                if ($sales->month == $i) {
                    $incomeFound = true;
                    array_push($monthlyOrder, $sales->total);
                    break;
                }
            }
            if ($incomeFound == false) {
                array_push($monthlyOrder, 0);
            }
        }

        $data['months'] = $months;
        $data['monthlyIncome'] = $monthlyIncome;
        $data['maxMonth'] = max($monthlyIncome);
        $data['minMonth'] = min($monthlyIncome);

        $data['monthlyOrder'] = $monthlyOrder;
        $data['maxOrder'] = max($monthlyOrder);
        $data['minOrder'] = min($monthlyOrder);
        $data['data'] = DB::table('settings')->select('website_logo')->first();

  $data['apiStats'] = $translateService->getApiStatistics();


        return view('admin.dashboard', $data);
    }

    //search by earing
    public function searchEarning(Request $request)
    {
        $totalEarnings = Transaction::when($request->date, function ($query, $date) {
            return $query->whereDate('created_at', $date);
        })
            ->sum('actual_total');

        return response()->json(
            [
                'total_earnings' => $totalEarnings,
                'date' => $request->date
            ]
        );
    }
    //search by total orders
    public function searchOrders(Request $request)
    {
        $totalOrders = Transaction::when($request->date, function ($query, $date) {
            return $query->whereDate('created_at', $date);
        })->count();

        return response()->json(
            [
                'total_orders' => $totalOrders,
                'date' => $request->date
            ]
        );
    }

    //admin login page
    public function login()
    {
        return view('admin.login');
    }

    //admin forget page
    public function forgetPassword()
    {
        return view('admin.forget-password');
    }

    //login submit
    public function authentication(Request $request)
    {
        $rules = [
            'username' => 'required|max:255',
            'password' => 'required|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // get the username and password which has provided by the admin
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $authAdmin = Auth::guard('admin')->user();

            // check whether the admin's account is active or not

            if ($authAdmin->status == 0) {
                // logout auth admin as condition not satisfied
                Auth::guard('admin')->logout();

                return redirect()->back();
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            return redirect()->back()->with('alert', 'Oops, username or password does not match!');
        }
    }

    //logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // invalidate the admin's session
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }

    // edit profile

    public function editProfile()
    {
        $adminInfo = Auth::guard('admin')->user();

        return view('admin.edit-profile', compact('adminInfo'));
    }

    //update profile
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [];
        if (is_null($admin->image)) {
            $rules['image'] = 'required';
        }
        $rules['username'] = [
            'required',
            Rule::unique('admins')->ignore($admin->id)
        ];
        $rules['email'] = [
            'required',
            Rule::unique('admins')->ignore($admin->id)
        ];
        $rules['name'] = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }


        if ($request->hasFile('image')) {
            $newImg = $request->file('image');
            $oldImg = $admin->image;
            $imageName = ImageUpload::update(public_path('assets/admin/img/'), $newImg, $oldImg);
        }

        $admin->update([
            'name' => $request->name,
            'username' => $request->username,
            'image' => $request->hasFile('image') ? $imageName : $admin->image,
            'email' => $request->email,
            'address' => $request->address,
            'details' => $request->details,
        ]);
        session()->flash('success', 'Profile update successfull !');
        return redirect()->back();
    }

    //chnage passowrd
    public function chnagePassword()
    {
        return view('admin.chnage-password');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $admin = Auth::guard('admin')->user();

        $currentPassword = $request->input('current_password');
        if (!Hash::check($currentPassword, $admin->password)) {
            return response()->json(['errors' => ['current_password' => ['The current password is incorrect.']]], 400);
        }
        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);
        session()->flash('success', 'Password change successfull !');
        return response()->json(['status' => 'success'], 200);
    }

    //role managment
    public function roleManagment()
    {
        $data = Role::query()->get();
        return view('admin.admin-managment.role-managment.index', compact('data'));
    }
    //create role managment
    public function createRole(Request $request)
    {
        $rules = ['name' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        Role::create(request()->only('name'));

        session()->flash('success', 'Role create successfully !');
        return response()->json(['status' => 'success'], 200);
    }
    //update role
    public function updateRole(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->update(['name' => $request->name]);

        session()->flash('success', 'Role update successfully');
        return response()->json(['status' => 'success'], 200);
    }

    //delete role
    public function deleteRole(Request $request)
    {
        $role = Role::findOrFail($request->role_id);

        if ($role) {
            $role->delete();
        }

        session()->flash('success', 'Role delete successfully !');
        return redirect()->back();
    }

    //role permission
    public function permission()
    {
        return view('admin.admin-managment.role-managment.permission');
    }

    //show admins
    public function allAdmin()
    {
        $information['roles'] = Role::query()->get();
        $information['admins'] = Admin::whereNotNull('role')->get();
        return view('admin.admin-managment.add-admins.index', $information);
    }

    //create admin
    public function createAdmin(Request $request)
    {
        $rules = [
            'role' => 'required',
            'username' => 'required|unique:admins',
            'email' => 'required|email:rfc,dns|unique:admins',
            'first_name' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $imageName = ImageUpload::store(public_path('assets/img/admins/'), $request->file('image'));

        Admin::query()->create($request->except('image', 'password') + [
            'password' => Hash::make($request->password),
            'image' => $imageName
        ]);

        $request->session()->flash('success', 'New admin added successfully!');

        return response()->json(['status' => 'success'], 200);
    }


    //create admin
    public function updateAdmin(Request $request)
    {
        $admin = Admin::query()->find($request->id);
        $rules = [
            'role' => 'required',
            'username' => [
                'required',
                'max:255',
                Rule::unique('admins')->ignore($admin->id)
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('admins')->ignore($admin->id)
            ],
            'first_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }


        if ($request->hasFile('image')) {
            $imageName = ImageUpload::update(public_path('assets/img/admins/'), $request->file('image'), $admin->image);
        }
        $admin = Admin::findOrFail($request->id);
        $admin->update($request->except('image', '_token') +
            [
                'image' => $request->hasFile('image') ? $imageName : $admin->image,
            ]);
        $request->session()->flash('success', 'Admin update successfully!');

        return response()->json(['status' => 'success'], 200);
    }


    /**
     * delete admin
     */
    public function deleteAdmin(Request $request)
    {
        $admin = Admin::findOrFail($request->admin_id);

        if ($admin) {
            // delete admin profile picture
            @unlink(public_path('assets/img/admins/') . $admin->image);
            $admin->delete();
        }

        session()->flash('success', 'Admin delete successfully !');
        return redirect()->back();
    }

    /**
     * admin bulk delete
     */
    public function bulkDeleteAdmin(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $admin = Admin::findOrFail($id);
            $admin->delete();
        }
        session()->flash('success', 'Categories delete successfully!');
        return response()->json(['status' => 'success'], 200);
    }
}
