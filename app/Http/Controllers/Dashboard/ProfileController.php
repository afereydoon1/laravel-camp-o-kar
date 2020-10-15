<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request, User $user)
    {
        $data = $request->only(['name', 'email']);

        if ($request->password) {
            $data['password'] = $request->password;
        }

        if ($request->profile && $request->profile_name) {
            $profile_image = $request->user()->id . '.' .  Str::afterLast($request->profile_name, '.');

            $src = public_path('profiles/') . $profile_image;

            Image::make($request->profile)
                ->fit(100)
                ->save($src);

            $data['profile'] = $profile_image . '?' . Str::random(10);
        }
        $user->update($data);

        return $user;
    }
}
