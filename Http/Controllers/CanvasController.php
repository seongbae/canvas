<?php

namespace App\Canvas\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class CanvasController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    	
        \Spatie\Flash\Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);

        
    }
}
