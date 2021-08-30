<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\SocialProvider;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        $this->checkProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $this->checkProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e);
        }

        $socialProvider = SocialProvider::where('provider_id', $socialUser->getId())->first();

        if (!$socialProvider) {
            $user = User::firstOrCreate([
                'email' => $socialUser->getEmail()
                ], [
                    'name' => $socialUser->getName(),
                    'password' => bcrypt(Str::random(24)),
                    'date_of_birth' => today(),
                ]);

            $user->socialProviders()->create(
                ['provider_id' => $socialUser->getId(), 'provider' => $provider]
            );
        } else {
            $user = $socialProvider->user;
        }

        auth()->login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    private function checkProvider($provider)
    {
        abort_if(!in_array($provider, SocialProvider::PROVIDERS), Response::HTTP_NOT_FOUND);
    }
}
