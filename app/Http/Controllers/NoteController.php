<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
            {
                $notes = Note::where('created_by', \Auth::user()->id)->get();
            }
            else
            {
                $notes = Note::where('created_by', \Auth::user()->creatorId())->get();
            }

            return view('note.index', compact('notes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('note.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $note              = new Note();
            $note->title       = $request->title;
            $note->description = $request->description;
            if(!empty($request->file))
            {
                $fileName = time() . "_" . $request->file->getClientOriginalName();
                $request->file->storeAs('uploads/notes', $fileName);
                $note->file = $fileName;
            }
            if(\Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
            {
                $note->created_by = \Auth::user()->id;
            }
            else
            {
                $note->created_by = \Auth::user()->creatorId();
            }

            $note->save();

            return redirect()->route('note.index')->with('success', __('Note successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function show(Note $note)
    {
        //
    }


    public function edit(Note $note)
    {
        return view('note.edit', compact('note'));
    }


    public function update(Request $request, Note $note)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $note->title       = $request->title;
            $note->description = $request->description;
            if(!empty($request->file))
            {
                if($note->file)
                {
                    \File::delete(storage_path('uploads/notes/' . $note->file));
                }

                $fileName = time() . "_" . $request->file->getClientOriginalName();
                $request->file->storeAs('uploads/notes', $fileName);
                $note->file = $fileName;
            }
            $note->created_by = \Auth::user()->creatorId();
            $note->save();

            return redirect()->route('note.index')->with('success', __('Note successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Note $note)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
        {
            $note->delete();
            if($note->file)
            {
                \File::delete(storage_path('uploads/notes/' . $note->file));
            }

            return redirect()->route('note.index')->with('success', __('Note successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
}
