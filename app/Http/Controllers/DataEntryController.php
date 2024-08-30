<?php

namespace App\Http\Controllers;

use App\Models\Xmlfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DataEntryController extends Controller
{
    public function create()
    {
        return view('data_entry.create');
    }

    public function store(Request $request)
    {
        $datafecha = $request->input('fecha');
        $datahora = $request->input('hora');
        $datanombre= $request->input('nombre');
        $dataidentificacion = $request->input('identificacion');
        $datanombredestinatario = $request->input('nombredestinatario');
        $datarecibidopor = $request->input('recibidopor');
        $dataemail= $request->input('email');
        $datatelefono= $request->input('telefono');
        $datadireccion= $request->input('direccion');


        $fecha = new \DateTime();
        $dataid = $fecha->getTimestamp();



        // Crear la estructura XML
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('bitacora');
        $dom->appendChild($root);
        $registro = $dom->createElement('registro');


        $fechaElement = $dom->createElement('fecha', $datafecha);
        $horaElement = $dom->createElement('hora', $datahora);
        $nombreremitenteElement = $dom->createElement('nombreremitente', $datanombre);
        $idremitenteElement = $dom->createElement('idremitente', $dataidentificacion);
        $nombredestinatarioElement = $dom->createElement('nombredestinatario', $datanombredestinatario);
        $recibidoporElement = $dom->createElement('recibidopor', $datarecibidopor);
        $correoremitenteElement = $dom->createElement('correoremitente', $dataemail);
        $telefonoElement = $dom->createElement('telefonoremitente', $datatelefono);
        $direccionElement = $dom->createElement('direccionremitente', $datadireccion);


        $registro->appendChild($fechaElement);
        $registro->appendChild($horaElement);
        $registro->appendChild($nombreremitenteElement);
        $registro->appendChild($idremitenteElement);
        $registro->appendChild($nombredestinatarioElement);
        $registro->appendChild($recibidoporElement);
        $registro->appendChild($correoremitenteElement);
        $registro->appendChild($telefonoElement);
        $registro->appendChild($direccionElement);

        $root->appendChild($registro);



        // Generar el XML
//        $xml = new \SimpleXMLElement('<data_entry/>');
//        $xml->addChild('nombre', $datanombre);
//        $xml->addChild('email', $dataemail);
//        $xml->addChild('telefono', $datatelefono);
//        $xml->addChild('direccion', $datadireccion);

//        $filePath = public_path() . '/xml_files/'. 'datos_' . $dataid . '.xml';
//        File::put($filePath, $xml->asXML());

        // Guardar el XML
        $fileName = 'bitacora_' . now()->timestamp . '.xml';
        $filePath = public_path('xml_files/') . $fileName;
        $dom->save($filePath);

        $xmlfile = new Xmlfiles();
        $xmlfile->filename = $fileName;
        $xmlfile->path= $filePath;
        $xmlfile->save();


        return redirect()->back()->with('success', 'XML ha sido generado.');
    }

}
