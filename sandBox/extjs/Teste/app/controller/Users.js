Ext.define('AM.controller.Users', {
	extend : 'Ext.app.Controller',

	// Views do sistema
	views : [ 'user.List', 'user.Edit' ],

	stores : [ 'Users' ],

	models : [ 'User' ],

	init : function() {

	// Detecta os eventos das views
	this.control( {
		'userlist' : {
			// Detecta evento render
			render : this.onPanelRendered,
			// Detecta evento duplo clique
			itemdblclick : this.editUser
		}
	});
},

onPanelRendered : function() {
	console.log('Evento: Lista de usuários carregada!');
},

editUser : function(grid, record) {
	console.log('Evento: Duplo clique em: ' + record.get('name'));

	var view = Ext.widget('useredit');
	view.down('form').loadRecord(record);
}
});