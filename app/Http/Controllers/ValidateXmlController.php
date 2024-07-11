<?php

namespace App\Http\Controllers;

use App\Models\Xmlfiles;
use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;


class ValidateXmlController extends Controller
{
    private $web3;

    public function __construct()
    {
        $this->middleware('auth');
        $infura = new HttpProvider(new HttpRequestManager('https://sepolia.infura.io/v3/d387ca75f4b14f16845b2b5d38c0b3f5', 10000));
        $this->web3 = new Web3($infura);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $xml = Xmlfiles::paginate(10)->where('ishash', '1');

        return view('validatexml', compact('xml'));
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

    public function verifyIntegrity(Request $request, $id)
    {
        $xmlfile = Xmlfiles::findOrFail($id);
        $xmlHash = hash('sha256', file_get_contents( public_path() . $xmlfile->pathsigned));

        // Obtener la transacción desde la blockchain
        $this->web3->eth->getTransactionByHash($xmlfile->hash, function ($err, $transaction) use ($xmlHash, &$onChainHash) {
            if ($err !== null) {
                throw new \Exception('Error fetching transaction: ' . $err->getMessage());
            }

            // Extraer el hash almacenado en la blockchain (data field)
            $onChainHash = hex2bin(substr($transaction->input, 2));
        });

        // Comparar el hash actual del archivo XML con el hash almacenado en la blockchain
        if ($xmlHash === $onChainHash) {
            return redirect()->back()->with('status', 'El archivo XML no ha sido alterado. La integridad está garantizada.');
        } else {
            return redirect()->back()->with('status', 'El archivo XML ha sido alterado. La integridad no está garantizada.');
        }
    }
}
