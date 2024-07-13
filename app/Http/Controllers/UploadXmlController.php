<?php

namespace App\Http\Controllers;

use App\Models\Xmlfiles;
use Illuminate\Http\Request;

class UploadXmlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('uploadxml');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function uploadfile(Request $request)
    {
         $validateresult =$request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);

        if($request->hasFile('xml_file')){
            $file = $request->file('xml_file');

            $destinationPath = public_path() . '/xml_files';
            $name =  $file->getClientOriginalName();

            if(!$file->move($destinationPath, $name)) {
                return redirect()->back()->with('status', 'El archivo XML no se ha cargado correctamente.');
            } else {
                $xml = new Xmlfiles();
                $xml->path = $destinationPath.'/'.$name;
                $xml->filename = $name;
                $xml->issign = '0';
                $xml->ishash = '0';
                $xml->save();

                $mensaje = 'El archivo '. $name . 'se subio correctamente en la ubicacion ' ;
                return redirect()->back()->with('status', $mensaje);
            }
        }











    }
}
