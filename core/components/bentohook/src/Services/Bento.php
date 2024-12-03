<?php

namespace BentoHook\Services;

use BentoHook\Service;
use bentonow\Bento\BentoAnalytics;
use MODX\Revolution\modX;

class Bento
{
    private Service $service;

    private BentoAnalytics $bento;

    /**
     * @throws \Exception
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
        $secret = $this->service->getOption('secret-key');
        $publishable = $this->service->getOption('publishable-key');
        $siteUuid = $this->service->getOption('site-uuid');

        if (empty($secret) || empty($publishable) || empty($siteUuid)) {
            throw new \Exception('Missing Bento credentials');
        }

        $this->bento = new BentoAnalytics([
            'authentication' => [
                'secretKey' => $secret,
                'publishableKey' => $publishable
            ],
            'siteUuid' => $siteUuid
        ]);

    }

    public function addSubscriber($email, $fields = []): bool
    {
        return $this->bento->V1->addSubscriber([
            'email' => $email,
            'fields' => $fields
        ]);
    }

    public function removeSubscriber($email): bool
    {
        return $this->bento->V1->removeSubscriber([
            'email' => $email
        ]);
    }

    public function getSubscriber($email): array
    {
        return $this->bento->V1->Subscribers->getSubscribers([
            'email' => $email
        ]);
    }
}