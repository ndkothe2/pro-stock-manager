<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    /**
     * Redirect to Google for authentication.
     */
    public function redirectToGoogle()
    {
        Log::info("Social Login [Step 1]: Initiating redirect to Google OAuth provider.");
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Secure Logging: Verify receipt of code without logging the sensitive value
            Log::info("Social Login [Step 2]: Callback received from Google.", [
                'has_code' => $request->has('code')
            ]);
    
            // Verify database schema integrity
            if (!Schema::hasColumn('tbl_customer_details', 'google_id')) {
                Log::error("Social Login [Error]: Missing 'google_id' column in 'tbl_customer_details' table.");
                return redirect()->route('login')->with('error', 'Critical System Error: Database schema mismatch.');
            }
    
            Log::info("Social Login [Step 3]: Requesting user profile information from Google server.");
            
            // Apply conditional SSL verification (Local vs Production)
            $verifySsl = app()->environment('local') ? false : true;
            
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => $verifySsl]))
                ->user();
            
            Log::info("Social Login [Step 4]: User profile data retrieved successfully.", [
                'id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name
            ]);
    
            // Authorization logic: Find or Create Customer
            $customer = Customer::where('google_id', $googleUser->id)->first();
    
            if ($customer) {
                Log::info("Social Login [Step 5]: Established session for existing customer (Matched by Social ID).", ['id' => $customer->id]);
                $customer->update(['avatar' => $googleUser->avatar]); // Sync latest profile picture
                Auth::guard('customer')->login($customer);
                return redirect()->route('customer.dashboard');
            }
    
            $existingCustomer = Customer::where('email', $googleUser->email)->first();
    
            if ($existingCustomer) {
                Log::info("Social Login [Step 5]: Established session for existing customer (Matched by Email).", ['id' => $existingCustomer->id]);
                $existingCustomer->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
                Auth::guard('customer')->login($existingCustomer);
                return redirect()->route('customer.dashboard');
            }
    
            Log::info("Social Login [Step 5]: Registering new customer account based on Google profile.");
            $newCustomer = Customer::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(Str::random(16)),
                'status' => '0', 
            ]);
    
            Log::info("Social Login [Success]: New account provisioned and session established.", ['id' => $newCustomer->id]);
            Auth::guard('customer')->login($newCustomer);
            return redirect()->route('customer.dashboard');
    
        } catch (\Exception $e) {
            Log::error("Social Login [Critical]: Authentication process failed.", [
                'error' => $e->getMessage()
            ]);
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again or contact support.');
        }
    }
}
