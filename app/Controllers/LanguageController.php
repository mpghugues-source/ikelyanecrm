<?php
namespace App\Controllers;

class LanguageController extends BaseController
{
    public function switch(string $locale)
    {
        $supported = ['fr', 'en'];
        if (!in_array($locale, $supported)) {
            $locale = 'fr';
        }
        session()->set('locale', $locale);
        return redirect()->back();
    }
}
