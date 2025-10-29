<?php
namespace GohalalgoMitra;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MitraClient
{
   private $client;
    private $baseUrl;
    private $clientId;
    private $clientKey;
    private $token;
    private $verifySsl;

    public function __construct(
        string $baseUrl,
        string $clientId,
        string $clientKey,
        bool $verifySsl = true 
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->verifySsl = $verifySsl;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 15.0,
            'verify'   => $this->verifySsl,
        ]);
    }

    private function request(string $method, string $endpoint, array $options = [])
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);
            $body = (string) $response->getBody();
            return json_decode($body, true) ?? $body;
        } catch (RequestException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'body' => $e->hasResponse() ? (string) $e->getResponse()->getBody() : null,
            ];
        }
    }

    public function getToken()
    {
        $response = $this->request('POST', '/api/mitra/v2/get-token', [
            'json' => [
                'client_id'  => $this->clientId,
                'client_key' => $this->clientKey,
            ],
        ]);

        if (isset($response['data']['token'])) {
            $this->token = $response['data']['token'];
        }

        return $response;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    private function authHeader(): array
    {
        if (!$this->token) {
            throw new \Exception("Token belum di-set. Jalankan getToken() atau setToken().");
        }

        return ['Authorization' => "Bearer {$this->token}"];
    }

    public function createPackage(array $data)
    {
        return $this->request('POST', '/api/mitra/v2/create-package', [
            'headers' => $this->authHeader(),
            'json' => $data,
        ]);
    }

    public function updatePackage(array $data)
    {
        return $this->request('POST', '/api/mitra/v2/update-package', [
            'headers' => $this->authHeader(),
            'json' => $data,
        ]);
    }

    public function updateSeat(array $data)
    {
        return $this->request('POST', '/api/mitra/v2/update-seat', [
            'headers' => $this->authHeader(),
            'json' => $data,
        ]);
    }

    public function uploadItinerary(string $kodePaket, string $filePath)
    {
        return $this->request('POST', '/api/mitra/v2/upload-itenary', [
            'headers' => $this->authHeader(),
            'multipart' => [
                [
                    'name'     => 'kode_paket',
                    'contents' => $kodePaket,
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);
    }
}
