<?php

$BentoHook = new \BentoHook\Service($this->modx, $this->scriptProperties);

$snippet = new \BentoHook\Elements\Snippets\CheckList($BentoHook, $this->scriptProperties);

$snippet->run();