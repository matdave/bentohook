<?php
abstract class BentoHookBaseManagerController extends modExtraManagerController {
    /** @var \BentoHook\BentoHook $bentohook */
    public $bentohook;

    public function initialize(): void
    {
        $this->bentohook = $this->modx->services->get('bentohook');

        $this->addCss($this->bentohook->getOption('cssUrl') . 'mgr.css');
        $this->addJavascript($this->bentohook->getOption('jsUrl') . 'mgr/bentohook.js');

        $this->addHtml('
            <script type="text/javascript">
                Ext.onReady(function() {
                    bentohook.config = '.$this->modx->toJSON($this->bentohook->config).';
                });
            </script>
        ');

        parent::initialize();
    }

    public function getLanguageTopics(): array
    {
        return array('bentohook:default');
    }

    public function checkPermissions(): bool
    {
        return true;
    }
}
