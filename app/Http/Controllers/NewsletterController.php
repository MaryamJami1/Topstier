<?php

namespace App\Http\Controllers;

use App\Mail\EmailManager;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use App\Exceptions\Redirectingexception;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:send_newsletters'])->only('index', 'send');
    }

    public function index(Request $request)
    {
        $users = User::where('user_type', 'customer')->where('email', '!=', null)->get();
        $subscribers = Subscriber::all();
        return view('backend.marketing.newsletters.index', compact('users', 'subscribers'));
    }

    public function send(Request $request)
    {
        if (env('MAIL_USERNAME') != null) {
            //sends newsletter to selected users
            if ($request->has('user_emails')) {
                foreach ($request->user_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        //dd($e);
                    }
                }
            }

            //sends newsletter to subscribers
            if ($request->has('subscriber_emails')) {
                foreach ($request->subscriber_emails as $key => $email) {
                    $array['view'] = 'emails.newsletter';
                    $array['subject'] = $request->subject;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['content'] = $request->content;

                    try {
                        Mail::to($email)->queue(new EmailManager($array));
                    } catch (\Exception $e) {
                        //dd($e);
                    }
                }
            }
        } else {
            flash(translate('Please configure SMTP first'))->error();
            return back();
        }

        flash(translate('Newsletter has been send'))->success();
        return redirect()->route('admin.dashboard');
    }

    public function testEmail(Request $request)
    {
        if(env('DEMO_MODE') == 'On'){
             flash(translate('Data chaning action is not allowed in demo mode.','warning'));
             throw new Redirectingexception();
        }
        $array['view'] = 'emails.newsletter';
        $array['subject'] = "SMTP Test";
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = "This is a test email.";

        try {
            Mail::to($request->email)->queue(new EmailManager($array));
        } catch (\Exception $e) {
            dd($e);
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }
}
