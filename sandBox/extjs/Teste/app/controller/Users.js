Ext.define('AM.controller.Users', {
	extend : 'Ext.app.Controller',

	// Views do sistema
	views : [ 'user.List', 'user.Edit' ],

	stores : [ 'Users' ],

	models : [ 'User' ],

	init : function() {

		// Detecta os eventos das views
	this.control( {
		'viewport > userlist' : {
			// Detecta evento render
			render : this.onPanelRendered,
			// Detecta evento duplo clique
			itemdblclick : this.editUser
		},
		// Quando um botão cuja ação for "save" for clicado dispara o evento
		// de salvamento
		'useredit button[action=save]' : {
			click : this.updateUser
		}
	});
},

updateUser : function(button) {
	console.log('O botão salvar foi clicado!');
	var win = button.up('window');
	var form = win.down('form');
	var record = form.getRecord();

	var values = form.getValues();

	record.set(values);
	win.close();
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