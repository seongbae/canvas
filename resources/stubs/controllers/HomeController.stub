<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactusSubmitted;

class HomeController extends Controller
{
    public function index()
    {
        return view('canvas::frontend.home');
    }

    public function showContact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $emails = $this->addressToArray(option('notification_email'));

        Mail::to($emails)
            ->send(new ContactusSubmitted($request->get('name'), $request->get('email'), $request->get('phone'), $request->get('message')));

        flash('E-mail sent!', 'alert-success');

        return redirect()->back();
    }

    
    private function addressToArray($emails)
    {
        if( strpos($emails, ',') !== false ) 
            return explode(",",$emails);
        elseif( strpos($emails, ';') !== false ) 
            return explode(";",$emails);
        else
            return $emails;

    }
    


}
