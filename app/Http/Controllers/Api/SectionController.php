<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Resources\SectionCollection;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //return SectionCollection::collection(Section::paginate(30));
        return response()->json(Section::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Section::create(['title' => $request->text]);

        return response()->json(['message' => 'success']);
    }

    /**
     */
    public function update(Request $request, Section $section)
    {
        $section->update(['title' => $request->text]);

        return response()->json(['message' => 'success']);
    }

    public function returnSections()
    {
        return response()->json(Section::all());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
