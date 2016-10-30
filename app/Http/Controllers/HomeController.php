<?php

namespace App\Http\Controllers;

use App\OE\Configuration\RecruitmentConfiguration;
use App\OE\News\NewsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;

class HomeController extends Controller
{
    public function index(RecruitmentConfiguration $recruitmentConfiguration, NewsRepository $newsRepository)
    {
        return view('home.index')
            ->with('recruitment', $recruitmentConfiguration->open())
            ->with('news', $newsRepository->latest());
    }
}
