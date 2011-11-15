<?php
interface IEvento{
	
	public function getParticular();
	public function setParticular($particular);
	
	public function getDataFim();
	public function setDataFim(DateTime $dataFim);
	
	public function getDataInicio();
	public function setDataInicio(DateTime $dataInicio);

	public function getObservacoes();
	public function setObservacoes($observacoes);

	public function getArrMaisContatos();
	public function setArrMaisContatos($arrMaisContatos);

	public function getArrEnderecosDosLocais();
	public function setArrEnderecosDosLocais($arrEnderecosDosLocais);

	public function getArrPessoasOuOrganizacoesPromotoras();
	public function setArrPessoasOuOrganizacoesPromotoras($arrPessoasOuOrganizacoesPromotoras);

	public function getArrIdsDosDocumentosRelacionados();
	public function setArrIdsDosDocumentosRelacionados($arrIdsDosDocumentosRelacionados);

	public function getTipoDeRecorrencia();
	public function setTipoDeRecorrencia($tipoDeRecorrencia);

	public function getQtdeDeRecorrencia();
	public function setQtdeDeRecorrencia($qtdeDeRecorrencia);

	public function getDataDeLembrete();
	public function setDataDeLembrete(DateTime $dataDeLembrete);
}
?>