<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Http\Helpers\MailConfig;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Requests\Vendor\StoreRequest;
use App\Http\Requests\Vendor\UpdateRequest;
use App\Models\Admin\Language;
use App\Models\Admin\Package;
use App\Models\Admin\PaymentGateway;
use App\Models\Membership;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\VendorInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class VendorController extends Controller
{
    public function setting()
    {
        return view('admin.vendor-management.setting');
    }
    public function settingUpdate(Request $request)
    {
        if ($request->admin_approval) {
            $admin_approval = 1;
        } else {
            $admin_approval = 0;
        }

        if ($request->email_verification_approval) {
            $email_verification_approval = 1;
        } else {
            $email_verification_approval = 0;
        }

        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            [
                'admin_approval' => $admin_approval,
                'email_verification_approval' => $email_verification_approval,
                'admin_approval_notice' => $request->admin_approval_notice,
            ]
        );

        return redirect()->back()->with('success', __('Settings update successfully'));
    }
    public function index()
    {
        $data['packages'] = Package::all();
        $data['vendors'] = DB::table('vendors')->get();
        $data['paymentMethod'] = PaymentGateway::all();
        return view('admin.vendor-management.index', $data);
    }

    public function store(StoreRequest $request)
    {
        //vendor store step
        $languages = Language::all();
        $in = $request->except(['_token', 'password_confirmation', 'package_id', 'gateway']);
        $in['password'] = Hash::make($request->password);
        $in['is_active'] = 1;
        $in['is_verified'] = 1;
        $in['email_verified_at'] = Carbon::now();
        $in['email'] = $request->email;
        $vendor = Vendor::create($in);

        foreach ($languages as $lang) {
            VendorInfo::create(['name' => $vendor->username, 'language_id' => $lang->id, 'vendor_id' => $vendor->id]);
        }

        //membership step
        $package = Package::find($request['package_id']);
        $startDate = Carbon::today()->format('Y-m-d');
        $transaction_id = UserPermissionHelper::uniqidReal(8);
        $bs = Setting::select('currency_text', 'currency_symbol', 'website_title')->first();

        if ($package->term === "monthly") {
            $endDate = Carbon::today()->addMonth()->format('Y-m-d');
        } elseif ($package->term === "yearly") {
            $endDate = Carbon::today()->addYear()->format('Y-m-d');
        } elseif ($package->term === "lifetime") {
            $endDate = Carbon::maxValue()->format('d-m-Y');
        }

        $memb = Membership::create([
            'price' => $package->price,
            'currency' => $bs->currency_text ? $bs->currency_text : "USD",
            'currency_symbol' => $bs->currency_symbol ? $bs->currency_symbol : $bs->currency_text,
            'payment_method' => $request["gateway"],
            'transaction_id' => $transaction_id ? $transaction_id : 0,
            'status' => 1,
            'is_trial' => 0,
            'trial_days' => 0,
            'receipt' => isset($request["name"]) ? $request["name"] : $request["username"],
            'transaction_details' => null,
            'settings' => json_encode($bs),
            'package_id' => $request['package_id'],
            'vendor_id' => $vendor->id,
            'start_date' => Carbon::parse($startDate),
            'expire_date' => Carbon::parse($endDate),
        ]);
        //create invoice
        $invoice = $this->makeInvoie($memb, $vendor);

        //prepared for send mail
        $mailer = new MailConfig();
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $data = [
            'toMail' => $vendor->email,
            'toName' => $vendor->username,
            'username' => $vendor->username,
            'package_title' => $package->title,
            'package_price' => ($bs->currency_text_position == 'left' ? $bs->currency_text . ' ' : '') . $package->price . ($bs->currency_text_position == 'right' ? ' ' . $bs->currency_text : ''),
            'activation_date' => $startDate->toFormattedDateString(),
            'expire_date' => $endDate->toFormattedDateString(),
            'invoice' => public_path('assets/front/invoices/' . $invoice),
            'website_title' => $bs->website_title
        ];
        $mailer->mailFromAdmin($data);

        Session::flash('success', __('Vndor added successfully'));
        return Response::json(['status' => 'success'], 200);
    }

    public function edit($username)
    {
        $data['vendor'] = Vendor::where('username', $username)->firstOrFail();
        return view('admin.vendor-management.edit', $data);
    }
    public function update(UpdateRequest $request)
    {
        $vendor = Vendor::findOrFail($request->id);

        if ($request->hasFile('image')) {
            if (!empty($vendor->image)) {
                $imageName = ImageUpload::update(public_path('assets/img/vendors/'), $request->image, $vendor->image);
            } else {
                $imageName = ImageUpload::store(public_path('assets/img/vendors/'), $request->image);
            }
        }

        $vendor->update([
            'username' => $request->username,
            'email' => $request->username,
            'phone' => $request->username,
            'zip_code' => $request->username,
            'image' => $imageName ?? $vendor->image
        ]);

        foreach (Language::all() as $lang) {
            $code = $lang->code;
            $vendorInfo = VendorInfo::where([['vendor_id', $request->id], ['language_id', $lang->id]])->first();

            if (empty($vendorInfo)) {
                $vendorInfo = new VendorInfo();
                $vendorInfo->vendor_id = $request->id;
                $vendorInfo->language_id = $lang->id;
            }
            if ($request->filled($lang->code . '_name')) {
                $vendorInfo->name = $request[$code . '_name'];
                $vendorInfo->address = $request[$code . '_address'];
                $vendorInfo->city = $request[$code . '_city'];
                $vendorInfo->state = $request[$code . '_state'];
                $vendorInfo->country = $request[$code . '_country'];
                $vendorInfo->save();
            }
        }
        Session::flash('success', __('Vendor update successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $vendor = Vendor::findOrFail($request->vendor_id);

        $this->deleteContent($vendor);

        Session::flash('success', __('Vendor delete successfully'));
        return redirect()->back();
    }

    public function Bulkdelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $vendor = Vendor::findOrFail($id);
            $this->deleteContent($vendor);
        }
        session()->flash('success', 'Vendors deleted successful');
        return response()->json(['status' => 'success'], 200);
    }

    function deleteContent($vendor)
    {
        $languages = Language::all();

        //delete vendor content
        foreach ($languages as $language) {
            VendorInfo::where([['language_id', $language->id], ['vendor_id', $vendor->id]])->delete();
        }

        $membership = Membership::where('vendor_id', $vendor->id)->first();

        //delete all vendor related images and files
        @unlink(public_path('assets/img/vendors/' . $vendor->image));
        @unlink(public_path('assets/front/invoices/' . $membership->invoice));


        $membership->delete();
        $vendor->delete();
    }
    public function changeEmailStatus(Request $request)
    {
        $vendor = Vendor::findOrFail($request->id);
        $status = $request->status == 1 ? Carbon::now() : null;

        $vendor->update([
            'is_verified' => $request->status,
            'email_verified_at' => $status
        ]);
        return 'success';
    }
    public function changeAccountStatus(Request $request)
    {
        Vendor::where('id', $request->id)->update(['is_active' => $request->status]);

        return 'success';
    }
}
