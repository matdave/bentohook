<?php

namespace BentoHook\Services;

use BentoHook\Service;
use MatDave\MODXPackage\Traits\Curl;
use MODX\Revolution\modX;

class Bento
{
    use Curl;

    private Service $service;
    private modX $modx;


    private string $api = 'https://app.bentonow.com/api/v1';

    private string $secret;
    private string $publishable;
    private string $siteUuid;

    /**
     * @throws \Exception
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->secret = $this->service->getOption('secret-key');
        $this->publishable = $this->service->getOption('publishable-key');
        $this->siteUuid = $this->service->getOption('site-uuid');

        if (empty($this->secret) || empty($this->publishable) || empty($this->siteUuid)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Missing Bento credentials');
            throw new \Exception('Missing Bento credentials');
        }
    }

    public function addSubscriber($email, $fields = []): array
    {
        $command = [];
        $command[] = [
            'command' => 'subscribe',
            'email' => $email,
        ];
        foreach ($fields as $key => $value) {
            $command[] = [
                'command' => 'add_field',
                'email' => $email,
                'query' => [
                    'key' => $key,
                    'value' => $value,
                ]
            ];
        }
        return $this->sendRequest(
            '/fetch/commands',
            'POST',
            [
                'command' => $command,
                'site_uuid' => $this->siteUuid,
            ]
        );
    }

    public function removeSubscriber($email): array
    {
        return $this->sendRequest(
            '/fetch/commands',
            'POST',
            [
                'command' => [
                    [
                        'command' => 'unsubscribe',
                        'email' => $email,
                    ]
                ],
                'site_uuid' => $this->siteUuid,
            ]
        );
    }

    public function getSubscriber($email): array
    {
       return $this->sendRequest(
            '/fetch/subscribers',
            'GET',
            [
                'email' => $email,
                'site_uuid' => $this->siteUuid,
            ]
        );
    }

    private function sendRequest($path, $method = 'GET', $params = []): array
    {
        $response = $this->curl(
            $path,
            $method,
            $params,
            [
                'Accept: application/json',
            ],
            [
                CURLOPT_USERPWD => $this->publishable . ':' . $this->secret,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            ]
        );
        return json_decode($response, true);
    }
}