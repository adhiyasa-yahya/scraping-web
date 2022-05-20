<?php

namespace App\Http\Controllers\Scrape;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ClearFileRateJob;

class ScrapeController extends Controller
{
    public function clearRate()
    {
        ClearFileRateJob::dispatch();
        return redirect()->back()->withSuccess('Proses berhasil ditambahkan pada Job');
    }
}
