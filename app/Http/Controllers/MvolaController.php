<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MvolaController extends Controller
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => env('MVOLA_API_URL'),
            'verify' => false
        ]);
    }

    public function initiatePayment($amount)
    {
        try {
            $num = "0387985543";
            $numNoSpace = str_replace(" ", "", $num);
            intval($numNoSpace);

            if (preg_match("/^(034|038)\d{7}$/", $numNoSpace) || preg_match("/^(\+26134|\+26138)\d{7}$/", $numNoSpace)) {
                if (str_starts_with($numNoSpace, '+261')) {
                    $numNoSpace = str_replace('+261', '0', $numNoSpace);
                }
                $accessToken = $this->getAccessToken();
                $nowTime = (new DateTime())->format('Y-m-d\TH:i:s.v\Z');

                $requestHeader = [
                    "Version" => '1.0',
                    "X-CorrelationID" => 'Auximadm01',
                    "UserLanguage" => 'MG',
                    "UserAccountIdentifier" => 'msisdn;0343500004',
                    "partnerName" => 'Auximad',
                    "Content-type" => "application/json",
                    "Authorization" => "Bearer $accessToken",
                    "X-Callback-URL" => env('MVOLA_CALLBACK_URL'),
                    "Cache-Control" => "no-cache"
                ];

                $requestBody = [
                    "amount" => $amount,
                    "currency" => "Ar",
                    "descriptionText" => "MVola Payment",
                    "requestingOrganisationTransactionReference" => "11",
                    "requestDate" => $nowTime,
                    "originalTransactionReference" => "01",
                    "debitParty" => [["key" => "msisdn", "value" => $numNoSpace]],
                    "creditParty" => [["key" => "msisdn", "value" => "0345003390"]],
                    "metadata" => [
                        ["key" => "partnerName", "value" => "MVola"],
                        ["key" => "fc", "value" => "USD"],
                        ["key" => "amountFc", "value" => "1"]
                    ]
                ];

                $response = $this->httpClient->post('mvola/mm/transactions/type/merchantpay/1.0.0', [
                    'headers' => $requestHeader,
                    'json' => $requestBody
                ]);

                $serverCorrelationId = json_decode($response->getBody())->serverCorrelationId;
                $transactionStatus = $this->pollTransactionStatus($serverCorrelationId);

                var_dump($serverCorrelationId);
                var_dump($transactionStatus);
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid phone number format'], 400);
                var_dump('erreur');
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred during the transaction request'], 500);
        }
    }

    private function getAccessToken()
    {
        try {
            $auth = "Basic " . base64_encode(env('CONSUMER_KEY') . ':' . env('CONSUMER_SECRET'));

            $requestHeader = [
                "Authorization" => $auth,
                "Content-type" => "application/x-www-form-urlencoded",
                "Cache-Control" => "no-cache"
            ];
            $requestBody = [
                "grant_type" => "client_credentials",
                "scope" => "EXT_INT_MVOLA_SCOPE"
            ];

            $response = $this->httpClient->post('token', [
                'headers' => $requestHeader,
                'form_params' => $requestBody
            ]);

            return json_decode($response->getBody())->access_token;
        } catch (Exception $e) {
            throw new Exception("Unable to retrieve access token.");
        }
    }

    private function pollTransactionStatus($serverCorrelationId)
    {
        $start = microtime(true);
        while ((microtime(true) - $start) < 45) {
            $transactionStatus = $this->getTransactionStatus($serverCorrelationId)->status;
            if (!empty($transactionStatus) && $transactionStatus !== 'pending') {
                return $transactionStatus;
            }
            sleep(3);
        }
        return 'timeout';
    }

    private function getTransactionStatus($serverCorrelationId)
    {
        try {
            $accessToken = $this->getAccessToken();
            $requestHeader = [
                "Version" => '1.0',
                "X-CorrelationID" => 'AkataChatBot1',
                "UserLanguage" => 'MG',
                "UserAccountIdentifier" => 'msisdn;0343500004',
                "partnerName" => 'AkataGoavana',
                "Content-type" => "application/json",
                "Authorization" => "Bearer $accessToken",
                "Cache-Control" => "no-cache"
            ];

            $response = $this->httpClient->get('mvola/mm/transactions/type/merchantpay/1.0.0/status/'.$serverCorrelationId, [
                'headers' => $requestHeader,
            ]);

            return json_decode($response->getBody());
        } catch (Exception $e) {
            throw new Exception("Unable to retrieve transaction status.");
        }
    }
}
