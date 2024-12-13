<?php

namespace BentoHook\Elements\Snippets;

use BentoHook\Services\Bento;
use MatDave\MODXPackage\Elements\Snippet\Snippet;
use MODX\Revolution\modX;

class CheckList extends Snippet
{
    public function run() {
        try {
            $bento = new Bento($this->service);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'BentoCheckList: ' . $e->getMessage());
            return false;
        }
        $email = $this->getOption('email');
        $inListTpl = $this->getOption('inListTpl');
        $notInListTpl = $this->getOption('notInListTpl');

        $subscriber = $bento->getSubscriber($email);

        if (empty($subscriber)) {
            return ($notInListTpl) ? $this->modx->getChunk($notInListTpl) : false;
        } else {
            return ($inListTpl) ? $this->modx->getChunk($inListTpl) : true;
        }
    }
}