<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Note;

class JournalController extends Controller
{
    public function showJournal()
    {
        $notes=Note::where('fk_user', auth()->user()->id)->orderBy('updated_at','desc')->paginate(4);
        return Inertia::render('Journal', ['notes' => $notes]);
    }
    public function addNote(Request $request)
    {
        $request -> validate([
            'value' => 'required',
        ]);

        $note=new Note();
        $note->description=$request->value;
        $note->fk_user=auth()->user()->id;
        $note->save();

    }
    public function editNote(Request $request)
    {
        $request -> validate([
            'description' => 'required',
        ]);
        $note=Note::find($request->id);
        $note->description=$request->description;
        $note->save();
    }
    public function deleteNote(Request $request)
    {
        $note=Note::find($request->id);
        $note->delete();
    }
}
