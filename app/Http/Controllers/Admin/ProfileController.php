<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\PasswordUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function destroy()
    {
        $user = User::find(Auth::id());
        // Ideally we should logout here but for now just delete
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully');
    }
}
