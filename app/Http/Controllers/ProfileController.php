<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Character_item;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Users_character;
use App\Models\Level;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $userAvatar=Users_character::where('fk_user', auth()->user()->id)->first()->load('getHead');
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'userAvatar' => $userAvatar,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function characterShow(Request $request)
    {
        $character=Users_character::where('fk_user', auth()->user()->id)->first()->load('getHead', 'getTop', 'getBottom', 'getShoes');
        $level=Level::where('requiredXP', '<=', auth()->user()->experience_points)->orderBy('requiredXP', 'desc')->first();
        $itemsHead=Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'head')->get();
        $itemsTop=Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'top')->get();
        $itemsBottom=Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'bottom')->get();
        $itemsShoes=Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'shoes')->get();
        return inertia::render('Profile/CharacterEdit', [
            'character' => $character,
            'itemsHead' => $itemsHead,
            'itemsTop' => $itemsTop,
            'itemsBottom' => $itemsBottom,
            'itemsShoes' => $itemsShoes,
        ]);
    }
    public function characterEdit(Request $request)
    {
        $character=Users_character::where('fk_user', auth()->user()->id)->first();
        $character->head=$request->head;
        $character->top=$request->top;
        $character->bottom=$request->bottom;
        $character->shoes=$request->shoes;
        $character->save();
        return Redirect::route('profile.edit');
    }
}
