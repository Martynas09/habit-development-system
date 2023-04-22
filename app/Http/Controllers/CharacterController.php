<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users_character;
use App\Models\Character_item;
use App\Models\Level;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class CharacterController extends Controller
{
    public function characterShow(Request $request)
    {
        $character = Users_character::where('fk_user', auth()->user()->id)->first()->load('getHead', 'getTop', 'getBottom', 'getShoes');
        $level = Level::where('requiredXP', '<=', auth()->user()->xp)->orderBy('requiredXP', 'desc')->first();
        $itemsHead = Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'head')->get();
        $itemsTop = Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'top')->get();
        $itemsBottom = Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'bottom')->get();
        $itemsShoes = Character_item::where('fk_level', '<=', $level->id)->where('category', '=', 'shoes')->get();
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
        $request -> validate([
            'head' => 'required',
            'top' => 'required',
            'bottom' => 'required',
            'shoes' => 'required',
        ]);
        $character = Users_character::where('fk_user', auth()->user()->id)->first();
        $character->head = $request->head;
        $character->top = $request->top;
        $character->bottom = $request->bottom;
        $character->shoes = $request->shoes;
        $user = User::where('id', auth()->user()->id)->first();
        $avatarHead = Character_item::where('id', $request->head)->first();
        $user->avatar = $avatarHead->picture;
        $user->save();
        $character->save();
        return Redirect::route('profile.edit');
    }
    public function showCharacterItemsList()
    {
        $items = Character_item::orderBy('updated_at','desc')->get();
        $levels= Level::all();
        return inertia::render('CharacterItems', [
            'items' => $items,
            'levels' => $levels,
        ]);
    }
    public function addCharacterItem(Request $request)
    {
        $request -> validate([
            'title' => 'required',
            'image' => 'required',
            'rarity' => 'required',
            'category' => 'required',
            'level' => 'required',
        ]);
        $image = substr($request->image, 2, -2);
        $item = new Character_item;
        $item->title = $request->title;
        $item->picture = $image;
        $item->rarity= $request->rarity;
        $item->category = $request->category;
        $item->fk_level = $request->level;
        $item->save();
    }
    public function pictureUpload(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        if ($request->file()) {
            $fileName = uniqid() . "." . pathinfo($request->file->getClientOriginalName(), PATHINFO_EXTENSION);
            $request->file('file')->storeAs('', $fileName, 'public');
        }

        return response()->json([
            'success' => 'You have successfully uploaded file.',
            'file' => $fileName
        ]);
    }
    public function deleteCharacterItem(Request $request)
    {
        $item=Character_item::find($request->id);
        $item->delete();
    }
    public function editCharacterItem(Request $request)
    {
        $request -> validate([
            'title' => 'required',
            'rarity' => 'required',
            'category' => 'required',
            'level' => 'required',
        ]);
        if($request->image!='null')
        {
            $image = substr($request->image, 2, -2);
        }
        else
        {
            $image = $request->picture;
        }
        $item=Character_item::find($request->id);
        $item->title=$request->title;
        $item->rarity=$request->rarity;
        $item->picture=$image;
        $item->category=$request->category;
        $item->fk_level=$request->fk_level;
        $item->save();
    }
}
