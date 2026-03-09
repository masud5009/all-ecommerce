<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Admin\Package;
use App\Models\Admin\Language;
use App\Models\User\UserLanguage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;



    public function requestLang($code)
    {
        $language =  UserLanguage::where('code', $code)->where('user_id', Auth::guard('web')->user()->id)->first();
        return $language;
    }

    /**
     * invoice load
     */
    public function makeInvoie($data, $vendor)
    {
        $file_name = uniqid($data->transaction_id) . ".pdf";
        $data = [
            'membership' => $data->toArray(),
            'vendor' => $vendor->toArray(),
            'package' => Package::where('id', $data->package_id)->firstOrFail()->toArray(),
        ];

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('admin.invoices.membership', $data);
        $output = $pdf->output();
        @mkdir(public_path('assets/front/invoices/'), 0775, true);
        file_put_contents(public_path('assets/front/invoices/' . $file_name), $output);
        return $file_name;
    }
}
