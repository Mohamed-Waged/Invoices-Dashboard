<?php

namespace App\Http\Controllers;

use App\Http\Requests\section\StoreSectionRequest;
use App\Http\Requests\section\UpdateSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        // $request->validate([
        //     'name' => ['required','unique:sections','min:3','max:255'],
        //     'description' => ['required','between:10,600'],
        // ],[
        //     'name.required' => 'يرجي ادخال اسم القسم',
        //     'name.unique' => 'اسم القسم موجود بالفعل',
        //     'name.min' => 'اسم القسم لا يقل عن 3 احرف',
        //     'name.max' => 'اسم القسم لا يزيد عن 255 احرف',
        //     'description.required' => 'يرجي ادخال الوصف ',
        //     'description.between' => 'الوصف يجب الا يقل عن 10 احرف ولا يزيد عن 600 حرف',
        // ]);

        // Section::create([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'created_by' => (Auth::User()->name),
        // ]);

        $data = $request->validated();
        $data['created_by'] = Auth::user()->name; 
        Section::create($data);
        return redirect()->route('sections.index')->with('message','تم إضافة القسم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request)
    {
        $id = $request->id;
        $section = Section::findOrFail($id);
        $data = $request->validated();
        $section->update($data);
        return redirect()->route('sections.index')->with('message','تم تعديل القسم بنجاح');


        // $id = $request->id;
        // $request->validate([
        //     'name' => 'required|min:3|max:255|unique:sections,name,'.$id,
        //     'description' => 'required|between:10,600',
        // ],[
        //     'name.required' => 'يرجي ادخال اسم القسم',
        //     'name.unique' => 'اسم القسم موجود بالفعل',
        //     'name.min' => 'اسم القسم لا يقل عن 3 احرف',
        //     'name.max' => 'اسم القسم لا يزيد عن 255 احرف',
        //     'description.required' => 'يرجي ادخال الوصف ',
        //     'description.between' => 'الوصف يجب الا يقل عن 10 احرف ولا يزيد عن 600 حرف',
        // ]);

        // $sections = Section::findOrFail($id);
        // $sections->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        // ]);
        // return redirect()->route('sections.index')->with('message','تم تعديل القسم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Section::findOrFail($request->id)->delete();
        return redirect()->route('sections.index')->with('message','تم حذف القسم بنجاح');
    }
}
