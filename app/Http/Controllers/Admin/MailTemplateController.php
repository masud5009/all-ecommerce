<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;

class MailTemplateController extends Controller
{
    public function index()
    {
        $templates = MailTemplate::all();
        return view('admin.settings.mail-template.index', compact('templates'));
    }

    public function edit($type)
    {
        $data = MailTemplate::where('type', $type)->firstOrFail();
        return view('admin.settings.mail-template.edit', compact('data'));
    }

    public function update(Request $request, $type)
    {
        $rules = ['subject' => 'required|max:255', 'body' => 'required'];
        $messages = ['body.required' => 'Please provide the content for the email body'];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        MailTemplate::where('type', $type)->update($request->except('type', 'body','_token') + [
            'body' => Purifier::clean($request->body, 'youtube'),
        ]);
        $request->session()->flash('success', 'Email template updated successfully!');

        return redirect()->back();
    }
}
