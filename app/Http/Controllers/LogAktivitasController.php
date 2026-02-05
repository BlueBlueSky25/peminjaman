<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->latest('timestamp')
            ->paginate(50);
        
        return view('pages.log.index', compact('logs'));
    }
}