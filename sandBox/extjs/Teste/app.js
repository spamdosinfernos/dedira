Ext.Loader.setPath('Ext', 'ext-4.0/src');
Ext.Loader.setConfig( {
	enabled : true
});

// Ext.require('Ext.layout.container.Border', 'Ext.tab.Panel');

Ext.application( {
	// app namespace (from ExtBlog)
	name : 'EB',
	// here goes the models
	models : [],
	// here goes the controllers
	controllers : [ 'Posts' ],
	// automatically creates Viewport
	autoCreateViewport : true
});