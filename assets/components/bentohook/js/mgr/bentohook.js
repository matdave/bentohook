var BentoHook = function (config) {
    config = config || {};
    BentoHook.superclass.constructor.call(this, config);
};
Ext.extend(BentoHook, Ext.Component, {

    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    field: {},
    config: {},

});
Ext.reg('bentohook', BentoHook);
bentohook = new BentoHook();
