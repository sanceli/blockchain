<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3p\EthereumTx\Transaction;
use kornrunner\Keccak;
use App\Models\Xmlfiles;
use App\Models\Wallet;
use Exception;
use SimpleXMLElement;

class BlockchainController extends Controller
{
    private $web3;
    private $eth;

    public function __construct()
    {
        // Inicializar Web3 con el proveedor de Infura
        $infura = new HttpProvider(new HttpRequestManager('https://sepolia.infura.io/v3/d387ca75f4b14f16845b2b5d38c0b3f5', 10000));
        $this->web3 = new Web3($infura);
        $this->eth = $this->web3->eth;
    }

    public function publishHash(Request $request, $id)
    {
        $userId = auth()->user()->id;
        $wallet = Wallet::where('users_id', $userId)->first();
        $xmlfile = Xmlfiles::where('id', $id)->first();

        if (!$wallet || !$xmlfile) {
            return response()->json(['error' => 'Datos de usuario o archivo no encontrados.'], 404);
        }

        $account = $wallet->account;
        $privateKey = $wallet->privatekey;

        if (strlen($privateKey) !== 64) {
            return response()->json(['error' => 'Clave privada inválida. Debe tener 64 caracteres hexadecimales.'], 400);
        }

        // Genera el hash del archivo XML
        $xmlHash = hash('sha256', file_get_contents($xmlfile->pathsigned));

        try {
            // Obtener el nonce
            $nonce = $this->getNonce($account);

            // Crear la transacción
            $transaction = [
                'nonce' => '0x' . dechex($nonce->toString()),
                'from' => $account,
                'to' => $account, // Enviar a la misma cuenta para registrar el hash
                'value' => '0x0', // Sin transferencia de valor
                'gas' => '0x5618', // 22040 gas units
                'gasPrice' => '0x3B9ACA00', // 1 Gwei
                'data' => '0x' . bin2hex($xmlHash),
                'chainId' => 11155111 // Sepolia Testnet ID
            ];

            // Firmar la transacción
            $tx = new Transaction($transaction);
            $signedTx = '0x' . $tx->sign($privateKey);

            // Enviar la transacción
            $txHash = $this->sendRawTransaction($signedTx);

            // Guardar el txHash en un nuevo archivo XML ademas de la firma digital

            $xmlContent = file_get_contents($xmlfile->pathsigned);
            $xml = new SimpleXMLElement($xmlContent);
            $xml->addChild('TxHash', $txHash);
            $signedXMLContent = $xml->asXML();
            $signedXMLfile = public_path() . '/xml_files/signedhash_' . $xmlfile->filename;
            file_put_contents($signedXMLfile, $signedXMLContent);


            // Guardar el hash de la transacción en la base de datos
            $xmlfile->ishash = '1';
            $xmlfile->hash = $txHash;
            $xmlfile->signedhash = '/xml_files/signedhash_' . $xmlfile->filename;
            $xmlfile->save();

            return redirect()->back()->with('status', 'Archivo XML firmado, enviado al Blockchain correctamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('status',  'Error publicando el hash en la blockchain: ' . $e->getMessage(), 500);

        }
    }

    private function getNonce($account)
    {
        $nonce = 0;
        $this->eth->getTransactionCount($account, 'pending', function ($err, $transactionCount) use (&$nonce) {
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
                throw new Exception('Error sending transaction: ' . $err->getMessage());
            }
            $txHash = $result;
        });
        return $txHash;
    }
}
