<?php

$BentoHook = new \BentoHook\Service($this->modx, $this->scriptProperties);

$snippet = new \BentoHook\Elements\Snippets\FormIt($BentoHook, $this->scriptProperties);

$snippet->run();