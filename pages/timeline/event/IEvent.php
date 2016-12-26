<?php
interface IEvent {
	public function IsPrivate();
	public function setPrivate($private);
	public function getFinalDate();
	public function setFinalDate(DateTime $dataFim);
	public function getBeginDate();
	public function setBeginDate(DateTime $dataInicio);
	public function getObservations();
	public function setObservations($observacoes);
	public function getArrMoreContats();
	public function setArrMoreContats($arrMaisContatos);
	public function getArrPlacesAddresses();
	public function setArrPlacesAddresses($arrEnderecosDosLocais);
	public function getArrPromoters();
	public function setArrPromoters($arrPersonsOuOrganizacoesPromotoras);
	public function getArrRelatedDocumentsIds();
	public function setArrRelatedDocumentsIds($arrIdsDosDocumentosRelacionados);
	public function getRecurringType();
	public function setRecurringType($tipoDeRecorrencia);
	public function getRecurringAmount();
	public function setRecurringAmount($qtdeDeRecorrencia);
	public function getRememberingDate();
	public function setRememberingDate(DateTime $dataDeLembrete);
}
?>