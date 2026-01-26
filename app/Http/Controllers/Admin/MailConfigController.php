<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MailConfigController extends Controller
{
    public function index()
    {
        $data =  DB::table('settings')
            ->select(
                'smtp_status',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'encryption',
                'sender_mail',
                'sender_name'
            )
            ->first();
        return view('admin.settings.config_mail', compact('data'));
    }


    public function updateConfig(Request $request)
    {
        $rules = [
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'encryption' => 'required',
            'sender_mail' => 'required',
            'sender_name' => 'required',
            'smtp_status' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()->toArray()], 400);
        }

        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            [
                'smtp_host' => $request->smtp_host,
                'smtp_port' => $request->smtp_port,
                'smtp_username' => $request->smtp_username,
                'smtp_password' => $request->smtp_password,
                'encryption' => $request->encryption,
                'sender_mail' => $request->sender_mail,
                'sender_name' => $request->sender_name,
                'smtp_status' => $request->smtp_status
            ]
        );

        session()->flash('success', __('Updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }
}
