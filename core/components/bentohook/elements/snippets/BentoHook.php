<?php

$BentoHook = new \BentoHook\Service($modx, $scriptProperties);

$snippet = new \BentoHook\Elements\Snippets\FormIt($BentoHook, $scriptProperties);

return $snippet->run();