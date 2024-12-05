<?php

$BentoHook = new \BentoHook\Service($modx, $scriptProperties);

$snippet = new \BentoHook\Elements\Snippets\CheckList($BentoHook, $scriptProperties);

$snippet->run();