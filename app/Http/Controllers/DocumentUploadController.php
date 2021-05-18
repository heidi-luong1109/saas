<?php

namespace App\Http\Controllers;

use App\DocumentUpload;
use Illuminate\Http\Request;

class DocumentUploadController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $documents = DocumentUpload::where('created_by', \Auth::user()->creatorId())->get();

            return view('documentUpload.index', compact('documents'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        return view('documentUpload.create');
    }


    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'document' => 'required|mimes:jpeg,png,jpg,svg,pdf,doc|max:20480',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(!empty($request->document))
            {

                $filenameWithExt = $request->file('document')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('document')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/documentUpload/');

                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('document')->storeAs('uploads/documentUpload/', $fileNameToStore);
            }

            $document              = new DocumentUpload();
            $document->name        = $request->name;
            $document->document    = !empty($request->document) ? $fileNameToStore : '';
            $document->description = $request->description;
            $document->created_by  = \Auth::user()->creatorId();
            $document->save();

            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($id)
    {

        $documentUpload = DocumentUpload::find($id);

        return view('documentUpload.edit', compact('documentUpload'));
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'document' => 'mimes:jpeg,png,jpg,svg,pdf,doc|max:20480',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $document = DocumentUpload::find($id);

            if(!empty($request->document))
            {

                $filenameWithExt = $request->file('document')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('document')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/documentUpload/');

                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('document')->storeAs('uploads/documentUpload/', $fileNameToStore);

                if(!empty($document->document))
                {
                    unlink($dir . $document->document);
                }

            }


            $document->name = $request->name;
            if(!empty($request->document))
            {
                $document->document = !empty($request->document) ? $fileNameToStore : '';
            }

            $document->description = $request->description;
            $document->save();

            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if(\Auth::user()->type == 'company')
        {
            $document = DocumentUpload::find($id);
            $document->delete();

            $dir = storage_path('uploads/documentUpload/');

            if(!empty($document->document))
            {
                unlink($dir . $document->document);
            }

            return redirect()->route('document-upload.index')->with('success', __('Document successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
