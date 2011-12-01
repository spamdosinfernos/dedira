<?php
require_once __DIR__ . '/../AEvent.php';

final class Lang_AEvent{

	public static function getDescriptions($descriptionId){

		//Português Brasil
		$languages["pt-br"] = array(
		Event::CONST_RECORRENCY_NO => "Não recorrente",
		Event::CONST_RECORRENCY_DAY => "Todo dia",
		Event::CONST_RECORRENCY_WEEK => "Toda semana",
		Event::CONST_RECORRENCY_MONTH => "Todo mês",
		Event::CONST_RECORRENCY_YEAR => "Todo ano",
		Event::CONST_RECORRENCY_BIMESTRAL => "Bimestral",
		Event::CONST_RECORRENCY_TRIMESTRAL => "Trimestral",
		Event::CONST_RECORRENCY_SEMESTRAL => "Semestral",
		Event::CONST_RECORRENCY_SUNDAY => "Todo domingo",
		Event::CONST_RECORRENCY_MONDAY => "Toda segunda",
		Event::CONST_RECORRENCY_TUESDAY => "Toda terça",
		Event::CONST_RECORRENCY_WEDNESDAY => "Toda quarta",
		Event::CONST_RECORRENCY_THURSDAY => "Toda quinta",
		Event::CONST_RECORRENCY_FRIDAY => "Toda sexta",
		Event::CONST_RECORRENCY_SATURDAY => "Toda sábado",
		Event::CONST_ERROR_1 => "CONST_ERROR_1 - O tipo de recorrência informada é inválida",
		Event::CONST_ERROR_2 => "CONST_ERROR_2 - Para informar se um evento é particular ou não informe um valor booleano"
		);

		//English United States
		$languages["en-us"] = array(
		Event::CONST_RECORRENCY_NO => "No recorrencies",
		Event::CONST_RECORRENCY_DAY => "Every day",
		Event::CONST_RECORRENCY_WEEK => "Every week",
		Event::CONST_RECORRENCY_MONTH => "Every month",
		Event::CONST_RECORRENCY_YEAR => "Every year",
		Event::CONST_RECORRENCY_BIMESTRAL => "Bimestral",
		Event::CONST_RECORRENCY_TRIMESTRAL => "Trimestral",
		Event::CONST_RECORRENCY_SEMESTRAL => "Semestral",
		Event::CONST_RECORRENCY_SUNDAY => "Every sunday",
		Event::CONST_RECORRENCY_MONDAY => "Every monday",
		Event::CONST_RECORRENCY_TUESDAY => "Every tuesday",
		Event::CONST_RECORRENCY_WEDNESDAY => "Every wednesday",
		Event::CONST_RECORRENCY_THURSDAY => "Every thusday",
		Event::CONST_RECORRENCY_FRIDAY => "Every friday",
		Event::CONST_RECORRENCY_SATURDAY => "Every saturday",
		Event::CONST_ERROR_1 => "CONST_ERROR_1 - The informed recurrency type is inválid",
		Event::CONST_ERROR_2 => "CONST_ERROR_2 - To inform if an event is private or not use a boolean value"
		);

		return $languages[Configuration::getLanguage()][$descriptionId];
	}
}
?>