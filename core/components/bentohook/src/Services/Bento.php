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
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Missing Bento credentials');
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
        try {
            $subscribe = $this->bento->V1->addSubscriber([
                'email' => $email,
                'fields' => []
            ]);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error adding subscriber: ' . $e->getMessage());
            return false;
        }
        if ($subscribe) {
            foreach ($fields as $key => $value) {
                $this->bento->V1->Commands->addField([
                    'email' => $email,
                    'field' => [
                        'key' => $key,
                        'value' => $value,
                    ],
                ]);
            }
        }
        return $subscribe;
    }

    public function removeSubscriber($email): bool
    {
        try {
            $remove = $this->bento->V1->removeSubscriber([
                'email' => $email
            ]);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error removing subscriber: ' . $e->getMessage());
            return false;
        }
        return $remove;
    }

    public function getSubscriber($email): array
    {
        return $this->bento->V1->Subscribers->getSubscribers([
            'email' => $email
        ]);
    }
}