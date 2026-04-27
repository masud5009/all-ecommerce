<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;

class LandingPageController extends Controller
{
    private array $viewMap = [
        'theme_one' => 'landing-page.theme-one',
        'theme_two' => 'landing-page.theme-two',
    ];

    public function show(string $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();

        $view = $this->viewMap[$landingPage->template] ?? null;
        if (empty($view)) {
            abort(404);
        }

        return view($view, [
            'landingPage' => $landingPage,
            'pageData' => $landingPage->content ?? [],
        ]);
    }
}
