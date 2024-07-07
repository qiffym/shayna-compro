<?php

namespace App\Http\Controllers;

use App\Models\CompanyAbout;
use App\Models\CompanyStatistic;
use App\Models\HeroSection;
use App\Models\OurPrinciple;
use App\Models\OurTeam;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{
    public function index(): View
    {
        $hero_section = HeroSection::query()->latest()->first();
        $testimonials = Testimonial::query()->with('client:id,name,avatar,logo,occupation')->take(4)->get();
        $statistics = CompanyStatistic::query()->take(4)->get();
        $principles = OurPrinciple::query()->take(4)->get();
        $products = Product::query()->take(4)->get();
        $teams = OurTeam::query()->take(7)->get();

        return view('front.index', [
            'hero_section' => $hero_section,
            'statistics' => $statistics,
            'principles' => $principles,
            'products' => $products,
            'teams' => $teams,
            'testimonials' => $testimonials,
        ]);
    }

    public function team(): View
    {
        $teams = OurTeam::all(['id', 'name', 'occupation', 'location', 'avatar']);
        $statistics = CompanyStatistic::query()->take(4)->get();
        return view('front.team', [
            'teams' => $teams,
            'statistics' => $statistics,
        ]);
    }

    public function about(): View
    {
        $statistics = CompanyStatistic::query()->take(4)->get();
        $abouts = CompanyAbout::query()->with('keypoints:id,company_about_id,keypoint')->take(2)->get();
        return view('front.about', [
            'abouts' => $abouts,
            'statistics' => $statistics,
        ]);
    }
}
