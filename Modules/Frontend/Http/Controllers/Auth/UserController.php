<?php

namespace Modules\Frontend\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Frontend\Http\Requests\UserRequest;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;
use Modules\Subscriptions\Trait\SubscriptionTrait;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\SubscriptionTransactions;
use Modules\Subscriptions\Models\Subscription;
use Modules\Currency\Models\Currency;
use App\Models\Payment;
use Illuminate\Support\Facades\Notification;
use Modules\NotificationTemplate\Trait\NotificationTemplateTrait;

class UserController extends Controller
{

    use NotificationTemplateTrait;
    use SubscriptionTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend::index');
    }

    public function login()
    {
        return view('frontend::components.auth.login');
    }
    public function forgetpassword()
    {
        return view('frontend::components.auth.forget_password');
    }
    public function registration()
    {
        return view('frontend::components.auth.register');
    }
    public function otp_verify()
    {
        $email = session('user_email');

        return view('frontend::components.auth.otp_login', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',   
            'email' => 'required|email',   
        ]);
    
        $storedOtp = session('otp');
        \Log::info($storedOtp);
        $userData = session('user_data');
    
        if (!$storedOtp || !$userData || $userData['email'] !== $request->email) {
            return response()->json(['message' => 'OTP expired or invalid request'], 422);
        }
    
        if ($storedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP. Please try agains.'], 422);
        }
    
        $userData['password'] = Hash::make($userData['password']);
    
        $userData['email_verified_at'] = now();
    
        $user = User::create($userData);
    
        \Artisan::call('cache:clear');

        $user->assignRole('admin');

        \Artisan::call('cache:clear');

        $this->addNotificationTemplate($user->id);
    
        session()->forget(['otp', 'user_data']);
    
        sendNotificationOnUserRegistration($user);
    
        $plan = Plan::where('identifier','free')->where('is_free_plan',1)->whereNull('deleted_at')->first();
        if (!$plan) {
            Auth::login($user);
            return response()->json(['redirect_url' => route('pricing')]);
        }
    
        $subscription = Subscription::create([
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'start_date' => now(),
            'end_date' => $this->get_plan_expiration_date(now(), $plan->type, $plan->duration),
            'status' => 'active',
            'amount' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'plan_details' => json_encode($plan),
            'gateway_type' => null,
            'transaction_id' => null,
            'name' => $plan->name,
            'identifier' => $plan->identifier,
            'type' => $plan->type,
            'duration' => $plan->duration,
            'payment_id' => null,
            'max_appointment' => $plan->max_appointment,
            'max_branch' => $plan->max_branch,
            'max_service' => $plan->max_service,
            'max_staff' => $plan->max_staff,
            'max_customer' => $plan->max_customer,
            'is_active' => 1,
        ]);
    
        $plan->givePermissionToUser($user->id);
    
        $currency = strtolower(GetcurrentCurrency());
    
        Payment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => 0,
            'currency' => $currency,
            'payment_method' => 2,
            'payment_date' => now(),
            'subscription_id' => $subscription->id,
            'status' => 2,
        ]);
    
        SubscriptionTransactions::create([
            'user_id' => $user->id,
            'amount' => 0,
            'payment_type' => null,
            'payment_status' => null,
            'tax_data' => null,
            'discount_data' => null,
            'transaction_id' => 0,
            'subscriptions_id' => $subscription->id,
        ]);
    
        $user->update(['is_subscribe' => 1]);
    
        Auth::login($user);
        $currencyData = [
            'currency_name' => 'Dollar',
            'currency_symbol' => '$',
            'currency_code' => 'USD',
            'currency_position' => 'left',
            'no_of_decimal' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'is_primary' => 1,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ];
        Currency::create($currencyData);
        return response()->json(['redirect_url' => route('app.home')]);
    }
    

    public function resendOtp(Request $request)
    {
        $userData = session('user_data');
        $email = $userData['email'];

        if (!$email) {
            return response()->json(['message' => 'Session expired. Please try registering again.'], 400);
        }

        $newOtp = rand(100000, 999999);

        session([
            'otp' => $newOtp,
        ]);

        try {
            Notification::route('mail', $email)->notify(new \App\Notifications\VerifyEmail($newOtp));
            return response()->json(['message' => 'A new OTP has been sent to your email address.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP. Please try again.'], 500);
        }
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('frontend::components.auth.edit_profile',compact('user'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
    
        $data['user_type'] = 'admin';
    
        $otp = rand(100000, 999999);
    
        session([
            'otp' => $otp,
            'user_data' => $data,
        ]);
    
        Notification::route('mail', $data['email'])->notify(new \App\Notifications\VerifyEmail($otp));
    
        return view('frontend::components.auth.otp_login', ['email' => $data['email']]);
    }
    


    public function adminLogin(Request $request)
    {
        $user = User::withTrashed()->where('email', $request->input('email'))->first();


        if ($user == null) {
            return redirect()->back()->with('error', __('messages.register_before_login'));
        }

        if ($user->status == 0) {
            return redirect()->back()->with('error', __('messages.account_disabled'));
        }

        if ($user->hasRole('user')) {
            
            return redirect()->back()->with('error', __('messages.unauthorised'));
        }

        if ($user->hasRole('employee')) {
            
            return redirect()->back()->with('error', __('messages.unauthorised'));
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {

            $user = Auth::user();

            if ($user->hasRole('admin') || $user->hasRole('super admin')) {
                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        return redirect()->back()->with('error', __('messages.invalid_credentials'));


    }




    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
