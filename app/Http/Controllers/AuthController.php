<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;
use ExinOne\MixinSDK\Facades\MixinSDK;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getAuthCode()
    {
        $url = MixinSDK::getOauthUrl(env('MIXIN_SDK_CLIENT_ID'), 'PROFILE:READ');

        return redirect($url);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function auth(Request $request)
    {
        $res = MixinSDK::network()->requestAccessToken($request->get('code'));

        $info = MixinSDK::network()->accessTokenGetInfo($res['access_token']);

        $user = (new User)->where('uuid', $info['user_id'])->first();

        if (! $user) {
            $user           = new User;
            $user->uuid     = $info['user_id'];
            $user->mixin_id = intval($info['identity_number']);
        }

        $user->nickname     = $info['full_name'];
        $user->avatar_url   = $info['avatar_url'];
        $user->access_token = $res['access_token'];

        $user->save();

        Auth::loginUsingId($user->id, true);

        return redirect()->intended($this->redirectPath());
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return '/mixin';
    }
}