Ext.onReady(function(){
	//Definimos que todas as colunas de todos os grid serão orndenáveis
	Ext.grid.ColumnModel.prototype.defaults = {sortable: true};
	
	//Criamos nossa classe herdando da classe base do ExtJS, a Observable
	var usuario = Ext.extend(Ext.util.Observable, {
		
		//Ésta função é disparada automáticamente quando se cria uma classe
		//Aqui definimos subfunções que irão começar a montar nossa tela
		constructor: function(){
			/*
			 * with coloca todo o código dentro de seu bloco no escopo do
			 * objeto entre parenteses, neste caso "this",
			 * Seria a mesma coisa que fazer:
			 * this.initStores();
			 * this.initPrincipal();
			 */
			with (this){
				//Aqui separo a criação dos stores que devem ser criados
				//antes dos componentes que os usarão
				initStores();
				//Aqui crio a tela principal da aplicação, a Window com
				//um Grid dentro
				initPrincipal();
			}
		},
		
		initStores: function(){
			//Criação do Store, JsonStore pq receberemos dados em formato JSON
			this.dsCategorias = new Ext.data.JsonStore({
				url       : 'php/categorias.php', //Arquivo de onde os dados devem ser buscados
				root      : 'data', //Propriedade que contém os registros
				idProperty: 'cat_id', //Propriedade que será o id do store, caso haja uma coluna primaryKey use-a aqui
				fields    : [ //Lista de campos que devem ser mapeados para o store
					'cat_id',
					'cat_descricao'
				],
				baseParams: { //Parametros a serem enviados na requisição ajax
					_action: 'select' //Ação que queremos executar no PHP, deve ser tratada do lado servidor
				}
			})
			
			this.dsUsuarios = new Ext.data.JsonStore({
				url: 'php/usuarios.php',
				root: 'data',
				fields: [
					'usu_login', //Caso um campo deva ser apenas adicionado basta colocar o seu nome
					'usu_nome',
					'usu_senha',
					'usu_email',
					//Caso precisarmos passar mais dados sobre este campo devemos passar um objeto
					//como por exemplo no caso de campos de data devemos passar o formato que esta data vem do banco
					{name: 'usu_data_nascimento', type: 'date', dateFormat: 'Y-m-d g:i:s'},
					{name: 'cat_id', type: 'int'},
					{name: 'inserted', type: 'bool'}
				],
				baseParams: {
					_action: 'select'
				}
			})
		},
		
		initPrincipal: function(){
			//Aqui criamos o Grid é responsável por mostrar os dados ao usuário
			this.grid = new Ext.grid.GridPanel({
				store     : this.dsUsuarios, //Definimos de qual store o grid deve buscar os dados
				loadMask  : true, //Aqui definimos que queremos uma mascara de "carregando" quando o grid estiver buscando dados
				border    : false, //Aqui tiramos uma borda fina que fica ao redor do grid, teste com true para verificar a diferença, é sutil
				stripeRows: true, //Dixa as linhas zebradas
				columns: [{ //Aqui definimos cada coluna do grid, não, não basta ligar o grid ao stora
					dataIndex: 'usu_nome',  //Campo do store que esta coluna deve mostrar os dados
					header   : 'Usuário',  //Titulo da coluna que será visivel ao usuário
					width    : 170 //Largura da coluna em pixels
				},{
					dataIndex: 'usu_login', 
					header   : 'Login',
					width    : 50
				},{
					dataIndex: 'usu_senha', 
					header   : 'Senha',
					width    : 50
				},{
					dataIndex: 'usu_email', 
					header   : 'E-mail',
					width    : 180
				},{
					dataIndex: 'usu_data_nascimento', 
					header   : 'Dt. Nasc.',
					width    : 70,
					xtype    : 'datecolumn' //Aqui definimos um tipo de coluna, datecolumn irá renderizar a data no formato da lingua, nosso calo pt_BR
				},{
					dataIndex: 'cat_id',
					header   : 'Categoria',
					width    : 80,
					renderer: { //Aqui temos uma coluna que é na verdade o id de uma categoria e queremos mostrar a descrição desta categoria
					            //Por isso definimos uma função de renderização
						scope: this, //Escopo para podermos pegar objetos dentro desta função atravéz do this
						fn   : function(value, metaData, record, rowIndex, colIndex, store){
							//procuramos o valor, que é o id da categoria, no store de categorias
							if(this.dsCategorias.getById(value)){
								//achando o registro pegamos a descrição do mesmo e retornamos para ser mostrado na coluna
								return this.dsCategorias.getById(value).data.cat_descricao;
							}
						}
					}
				}],
				viewConfig: {
					forceFit: true, //Mantém as colunas com tamanho máximo, evita espaços em branco depois das colunas
					emptyText: '<center>Sem registros para exibir</center>' //Texto a ser mostrado quando não houver dados no grid
				}
			});
			
			//Aqui criamos a janela
			this.winGrid = new Ext.Window({
				title : '<center>CRUD Completo - Parte 1</center>', //Titulo da janela, centarlizado
				height: 240, //Altura
				width : 620, //Largura
				layout: 'fit', //Aqui definimos que o filho da janela, nosso grid, ficará com o tamanho total da janela
				items : this.grid, //Atribuímos o filho a janela
				listeners: { //Eventos
					show: function(){ //Ao abrir a janela
						//Carrega o Store de categorias
						this.dsCategorias.load({ 
							callback: function(){ //Função disparada quando o store de categorias terminar de carregar
								//Carrega o store de Usuários
								this.dsUsuarios.load();
							},
							scope: this
						});
					},
					scope: this
				}
			})
			
			//Mostramos a janela
			this.winGrid.show();
		}
	});
	//Aqui criamos o objeto, isso dispara todo o nosso código acima
	var cadUsuario = new usuario;
})