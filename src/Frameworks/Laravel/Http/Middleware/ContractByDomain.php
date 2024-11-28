<?php

namespace Nanicas\Auth\Frameworks\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nanicas\Auth\Helpers\LaravelAuthHelper;
use Symfony\Component\HttpFoundation\Response;
use Nanicas\Auth\Contracts\AuthorizationClient;
use Nanicas\Auth\Exceptions\UndefinedContractByDomainException;
use Nanicas\Auth\Exceptions\MultiplesContractsByDomainException;

class ContractByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$guards
    ): Response {

        $config = config(LaravelAuthHelper::CONFIG_FILE_NAME);
        $auth = $request->session()->get($config['SESSION_AUTH_KEY']);

        if (array_key_exists('contract',  $auth)) {
            return $next($request);
        }

        $client = app()->make(AuthorizationClient::class);

        extract($this->getDomainInfo($request));

        $response = $client->cache([
            'cacheable_type' => 'App\\Models\\Contract',
            'value' => [
                'subdomain' => $subdomain,
                'application_domain' => $domain,
            ],
        ]);

        if (!$response['status'] || count($response['body']['data']) === 0) {
            throw new UndefinedContractByDomainException();
        }

        if (count($response['body']['data']) > 1) {
            throw new MultiplesContractsByDomainException();
        }

        $contract = $response['body']['data'][0];

        $authKey = LaravelAuthHelper::getAuthSessionKey();

        LaravelAuthHelper::attachInSession(
            $request->session(),
            'contract',
            [
                'id' => $contract['cacheable_id'],
                'subdomain' => $contract['value']['subdomain'],
                'domain' => $contract['value']['application_domain'],
            ],
            $authKey
        );

        return $next($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getDomainInfo(Request $request): array
    {
        $domain = $request->getHost();
        $domainParts = explode('.', $domain);
        $subdomain = $domainParts[0];
        $domain = $domainParts[1] . '.' . $domainParts[2];

        return [
            'subdomain' => $subdomain,
            'domain' => $domain,
        ];
    }
}