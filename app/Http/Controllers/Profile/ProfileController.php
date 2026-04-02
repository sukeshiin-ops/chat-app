<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Reverb\Loggers\Log;

class ProfileController extends Controller
{
    public function view()
    {
        $user = User::where('id', Auth::id())->first();

        return view('profile.edit-profile', compact('user'));
    }



    public function store(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif,webp'
            ]);

            $user = User::findOrFail($id);

            if ($request->hasFile('profile_image')) {

                if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
                    Storage::disk('public')->delete($user->profile_img);
                }

                $path = $request->file('profile_image')->store('profile_images', 'public');

                $user->profile_img = $path;
            }

            $user->name = $request->name;
            $user->email = $request->email;

            $user->save();

            DB::commit();

            return redirect()->route('chat.home.page')
                ->with('success', 'User Profile Update Successfully !!!');
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->route('chat.home.page')
                ->with('error', 'User Profile Update Fail !!!');
        }
    }



}
