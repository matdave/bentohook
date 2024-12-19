<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';

class BentoHookManageManagerController extends BentoHookBaseManagerController
{

    public function process(array $scriptProperties = []): void
    {
    }

    public function getPageTitle(): string
    {
        return $this->modx->lexicon('bentohook');
    }

    public function loadCustomCssJs(): void
    {
        $this->addLastJavascript($this->bentohook->getOption('jsUrl') . 'mgr/widgets/manage.panel.js');
        $this->addLastJavascript($this->bentohook->getOption('jsUrl') . 'mgr/sections/manage.js');

        $this->addHtml(
            '
            <script type="text/javascript">
                Ext.onReady(function() {
                    MODx.load({ xtype: "bentohook-page-manage"});
                });
            </script>
        '
        );
    }

    public function getTemplateFile(): string
    {
        return $this->bentohook->getOption('templatesPath') . 'manage.tpl';
    }

}
