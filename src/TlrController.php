<?php
namespace Laraveltlr\Tlr;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TlrController extends Controller
{

    public function index($timezone)
    {
        $current_time = ($timezone)
            ? Carbon::now(str_replace('-', '/', $timezone))
            : Carbon::now();
        return view('tlr::time', compact('current_time'));
    }

}