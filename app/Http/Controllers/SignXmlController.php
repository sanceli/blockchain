<?php

namespace App\Http\Controllers;

use App\Models\Keys;
use App\Models\Wallet;
use App\Models\Xmlfiles;
use Illuminate\Http\Request;
use SimpleXMLElement;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3p\EthereumTx\Transaction;

class SignXmlController extends Controller
{
    private $web3;
    private $privateKey;
    private $account;

    private $eth;



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function digitalsign(Request $request, $id){
        $data = $request->all();

        $userid =  auth()->user()->id;

        $userdata = Keys::where('users_id', $userid)->first();
        $xmlfile = Xmlfiles::where('id', $id)->first();

        $privateKey = file_get_contents(public_path() . '/keys/' . $userid . '/' . $userdata->privatekey );
        $xmlContent = file_get_contents($xmlfile->path);
        $signature = $this->signXML($xmlContent, $privateKey);

        $xml = new SimpleXMLElement($xmlContent);
        $xml->addChild('Signature', $signature);
        $signedXMLContent = $xml->asXML();
        $signedXMLfile = public_path() . '/xml_files/signed_' .$xmlfile->filename;
        file_put_contents($signedXMLfile, $signedXMLContent);

        $xmlfile->issign = '1';
        $xmlfile->sign = $signature;
        $xmlfile->save();

        echo "Archivo XML firmado correctamente.";
    }

    public function sendblockchain(Request $request, $id){

        $userid =  auth()->user()->id;

        $userwallet = Wallet::where('users_id', $userid)->first();
        $xmlfile = Xmlfiles::where('id', $id)->first();

        $this->account = $userwallet->account;
        $this->privateKey = $userwallet->privatekey;

        // Genera el hash del archivo XML
        $xmlHash = hash('sha256', file_get_contents($xmlfile->pathsigned));

        $txHash = $this->publishHash($xmlHash);


        $xmlfile->ishash = '1';
        $xmlfile->hash = $txHash;
        $xmlfile->save();
    }

    public function publishHash($xmlHash)
    {
        $infura = new HttpProvider(new HttpRequestManager('https://sepolia.infura.io/v3/d387ca75f4b14f16845b2b5d38c0b3f5', 10000));

        $this->web3 = new Web3($infura);

        $this->eth = $this->web3->eth;

        $nonce = $this->getNonce($this->account);

        // Convertir el nonce de BigInteger a string hexadecimal
        $nonceHex = '0x' . dechex($nonce->toString());

        $gasPrice = '0x3B9ACA00'; // Ajusta según sea necesario
        $gasLimit = '0x5618';
        $chainId = 11155111; // Sepolia Testnet ID

        $transaction = [
            'nonce' => $nonceHex,
            'from' => $this->account,
            'to' => null,
            'value' => '0x0',
            'gas' => $gasLimit,
            'gasPrice' => $gasPrice,
            'data' => '0x' . bin2hex($xmlHash),
            'chainId' => $chainId
        ];

        $tx = new Transaction($transaction);
        $signedTx = $tx->sign($this->privateKey);

        return $this->sendRawTransaction($signedTx);
    }

    private function getNonce($account)
    {
        $nonce = 0;
        $this->web3->eth->getTransactionCount($account, 'pending', function ($err, $transactionCount) use (&$nonce) {
            if ($err !== null) {
                throw new Exception('Error fetching nonce: ' . $err->getMessage());
            }
            $nonce = $transactionCount;
        });

        return $nonce;
    }

    private function sendRawTransaction($signedTx)
    {
        $txHash = null;
        $this->eth->sendRawTransaction($signedTx, function ($err, $result) use (&$txHash) {
            if ($err !== null) {
                throw new \Exception('Error sending transaction: ' . $err->getMessage());
            }
            $txHash = $result;
        });
        return $txHash;
    }


    public function signXML($xmlContent, $privateKey) {
        openssl_sign($xmlContent, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }
}
