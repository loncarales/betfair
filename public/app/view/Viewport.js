Ext.define('Betfair.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Betfair.view.west.List'
    ],
    layout: {
        type: 'border',
        padding: '0 5 5 5' // pad the layout from the window edges
    },
	items: [{
		 id: 'app-header',
         xtype: 'box',
         region: 'north',
         height: 40,
         html: 'Betfair Scalping'
    },{
    	xtype: 'container',
        region: 'center',
        layout: 'border',
        items: [{
        	xtype: 'box',
        	region: 'north',
        	html: 'north'
        },{
        	region: 'west',
        	xtype: 'marketlist'
        },{
        	xtype: 'box',
        	region: 'center',
        	html: 'center'
        }]
    }]
});