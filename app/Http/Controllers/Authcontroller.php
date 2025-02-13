<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function index()
    {
        return view('security.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Validation reCAPTCHA
        $recaptchaSecret = config('services.recaptcha.secret_key');
        $response = $request->input('g-recaptcha-response');
    
        if (!$response) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Veuillez valider le CAPTCHA.');
        }
    
        $recaptchaValidation = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $response,
        ]);
    
        if (!$recaptchaValidation->json()['success']) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'La validation CAPTCHA a échoué.');
        }
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $otp = rand(100000, 999999);
            $user = User::where('email', $request->email)->first();
            
            session([
                'otp' => $otp,
                'auth_email' => $request->email,
                'otp_phone' => $user->phone
            ]);
    
            if ($user && $user->phone) {
                $this->sendOtpWhatsApp($user->phone, $otp);
            }
    
            return redirect()->route('showOtpForm');
        }
    
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Ces identifiants ne correspondent pas à nos enregistrements.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Stocker les données temporairement dans la session
        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        $otp = rand(100000, 999999);

        session([
            'otp' => $otp,
            'user_data' => $userData,
            'otp_phone' => $request->phone
        ]);

        // Envoyer l'OTP sur WhatsApp
        $this->sendOtpWhatsApp($request->phone, $otp);

        return redirect()->route('showOtpForm')
            ->with('success', 'Un code OTP a été envoyé sur votre WhatsApp.');
    }

    public function showOtpForm()
    {
        Log::info('Session data:', session()->all());
        
        if (!session('otp') || (!session('user_data') && !session('auth_email'))) {
            return redirect()->route('login')
                ->with('error', 'Session expirée. Veuillez réessayer.');
        }
    
        return view('security.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        if ($request->otp == session('otp')) {
            // Si c'est une nouvelle inscription
            if (session('user_data')) {
                $user = User::create(session('user_data'));
                Auth::login($user);
                session()->forget(['otp', 'user_data', 'otp_phone']);
                return redirect()->route('index')
                    ->with('success', 'Inscription réussie !');
            }
            // Si c'est une connexion
            else if (session('auth_email')) {
                $user = User::where('email', session('auth_email'))->first();
                if ($user) {
                    session()->forget(['otp', 'auth_email', 'otp_phone']);
                    return redirect()->route('index')
                        ->with('success', 'Connexion réussie !');
                }
            }
        }
    
        return back()->with('error', 'Code OTP incorrect.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

//     public function sendOtpWhatsApp($phone, $otp)
// {
//     $message = "Votre code OTP est : $otp";
//     $client = new Client();

//     try {
//         $response = $client->post('https://e52pr1.api.infobip.com/whatsapp/1/message/text', [
//             'headers' => [
//                 'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
//                 'Content-Type'  => 'application/json',
//                 'Accept'        => 'application/json',
//             ],
//             'json' => [
//                 "from" => env('INFOBIP_WHATSAPP_SENDER'),
//                 "to"   => "221" . $phone,
//                 "content" => [
//                     "text" => $message
//                 ]
//             ]
//         ]);

//         Log::info("OTP envoyé avec succès à $phone : " . $response->getBody());
//         return true;
//     } catch (\Exception $e) {
//         Log::error("Erreur lors de l'envoi de l'OTP : " . $e->getMessage());
//         return false;
//     }
// }

public function sendOtpWhatsApp($phone, $otp)
{
    $message = "Votre code OTP est : $otp";
    $client = new Client();

    try {
        $response = $client->post('https://e52pr1.api.infobip.com/sms/2/text/advanced', [
            'headers' => [
                'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'json' => [
                "messages" => [
                    [
                        "from" => env('INFOBIP_SMS_SENDER'),
                        "destinations" => [
                            ["to" => "221" . $phone]
                        ],
                        "text" => $message
                    ]
                ]
            ]
        ]);

        Log::info("OTP envoyé par SMS à $phone : " . $response->getBody());
        return true;
    } catch (\Exception $e) {
        Log::error("Erreur lors de l'envoi du SMS : " . $e->getMessage());
        return false;
    }
}
}