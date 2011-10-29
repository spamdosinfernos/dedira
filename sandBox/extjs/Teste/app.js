Ext.application( {
	name : 'AM',
	appFolder : 'app',

	stores : [ 'Users' ],

	// Anexa os controladores do sistema
	controllers : [ 'Users' ],

	models : [ 'User' ],

	launch : function() {

		// Cria uma janela e exibição
	Ext.create('Ext.container.Viewport', {
		layout : 'fit',
		// Adiciona as views à interface
		items : {
			xtype : 'userlist'
		}
	});
}
});