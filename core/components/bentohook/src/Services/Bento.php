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
        $subscribe = $this->curl(
            '/fetch/commands',
            'POST',
            [
                'command' => $command,
                'site_uuid' => $this->siteUuid,
            ],
            $this->getHeaders()
        );
        return json_decode($subscribe, true);
    }

    public function removeSubscriber($email)
    {
        $remove = $this->curl(
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
            ],
            $this->getHeaders()
        );
        return json_decode($remove, true);
    }

    public function getSubscriber($email): array
    {
        $subscribers = $this->curl(
            '/fetch/subscribers',
            'GET',
            [
                'email' => $email,
                'site_uuid' => $this->siteUuid,
            ],
            $this->getHeaders()
        );
        return json_decode($subscribers, true);
    }

    private function getHeaders(): array
    {
        return [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->publishable . ':' . $this->secret),
        ];
    }
}