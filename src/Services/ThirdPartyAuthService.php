<?php

namespace Nanicas\Auth\Services;

use Nanicas\Auth\Core\HTTPRequest;
use Nanicas\Auth\Helpers\LaravelAuthHelper;

class ThirdPartyAuthService
{
    private string $baseAPI;

    public function __construct()
    {
        $config = config(LaravelAuthHelper::CONFIG_FILE_NAME);

        $this->baseAPI = $config['AUTHENTICATION_API_URL'];
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function users()
    {
        $token = $this->getToken();

        return HTTPRequest::do(function () use ($token) {

            $client = HTTPRequest::client();

            $url = 'api/users';
            $response = $client->get(
                $this->baseAPI . $url,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders(),
                        $this->authorizationHeader($token),
                    )
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                return HTTPRequest::getDefaultSuccess($response);
            }

            $fail = $response->getBody()->getContents();
            return HTTPRequest::getDefaultFail($statusCode, $fail);
        });
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function retrieveByCredentials(array $credentials)
    {
        return HTTPRequest::do(function () use ($credentials) {

            $client = HTTPRequest::client();
            $data = [
                'form_params' => $credentials,
                'headers' => $this->defaultHeaders(),
            ];

            $url = 'oauth/token';
            $response = $client->post($this->baseAPI . $url, $data);

            if ($response->getStatusCode() == 200) {
                return HTTPRequest::getDefaultSuccess($response);
            }

            return HTTPRequest::getDefaultFail($response->getStatusCode());
        });
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function refreshToken(array $credentials)
    {
        return HTTPRequest::do(function () use ($credentials) {

            $client = HTTPRequest::client();
            $data = [
                'form_params' => $credentials,
                'headers' => $this->defaultHeaders(),
            ];

            $url = 'oauth/token';
            $response = $client->post($this->baseAPI . $url, $data);

            if ($response->getStatusCode() == 200) {
                return HTTPRequest::getDefaultSuccess($response);
            }

            return HTTPRequest::getDefaultFail($response->getStatusCode());
        });
    }

    /**
     * @param string $token
     * @return array
     */
    public function checkAccessToken(string $token)
    {
        return HTTPRequest::do(function () use ($token) {

            $client = HTTPRequest::client();

            $url = 'api/user/check';
            $response = $client->get(
                $this->baseAPI . $url,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders(),
                        $this->authorizationHeader($token),
                    )
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                return HTTPRequest::getDefaultSuccess($response);
            }

            $fail = $response->getBody()->getContents();
            return HTTPRequest::getDefaultFail($statusCode, $fail);
        });
    }

    /**
     * @param string $token
     * @return array
     */
    public function retrieveByToken(string $token)
    {
        return HTTPRequest::do(function () use ($token) {

            $client = HTTPRequest::client();

            $url = 'api/user';
            $response = $client->get(
                $this->baseAPI . $url,
                [
                    'headers' => array_merge(
                        $this->defaultHeaders(),
                        $this->authorizationHeader($token),
                    )
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                return HTTPRequest::getDefaultSuccess($response);
            }

            $fail = $response->getBody()->getContents();
            return HTTPRequest::getDefaultFail($statusCode, $fail);
        });
    }

    /**
     * @return string
     */
    private function getToken(): string
    {
        return session()->get(LaravelAuthHelper::getClientAuthSessionKey())['access_token'];
    }

    /**
     * @return array
     */
    private function defaultHeaders()
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param string $token
     * @return array
     */
    private function authorizationHeader(string $token)
    {
        return [
            'Authorization' => 'Bearer ' . $token,
        ];
    }
}
