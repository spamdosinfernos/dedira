Ext.application( {
	name : 'AM',
	appFolder : 'app',

	// Anexa os controladores do sistema
	controllers : [ 'Users' ],

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