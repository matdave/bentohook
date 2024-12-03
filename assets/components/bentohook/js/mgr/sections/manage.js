bentohook.page.Manage = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [
            {
                xtype: 'bentohook-panel-manage',
                renderTo: 'bentohook-panel-manage-div'
            }
        ]
    });
    bentohook.page.Manage.superclass.constructor.call(this, config);
};
Ext.extend(bentohook.page.Manage, MODx.Component);
Ext.reg('bentohook-page-manage', bentohook.page.Manage);
