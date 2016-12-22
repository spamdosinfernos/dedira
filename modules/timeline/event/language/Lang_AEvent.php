<?php
require_once __DIR__ . '/../AEvent.php';
final class Lang_AEvent {
	public static function getDescriptions($descriptionId) {
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				Event::RECORRENCY_NO => "Não recorrente",
				Event::RECORRENCY_DAY => "Todo dia",
				Event::RECORRENCY_WEEK => "Toda semana",
				Event::RECORRENCY_MONTH => "Todo mês",
				Event::RECORRENCY_YEAR => "Todo ano",
				Event::RECORRENCY_BIMESTRAL => "Bimestral",
				Event::RECORRENCY_TRIMESTRAL => "Trimestral",
				Event::RECORRENCY_SEMESTRAL => "Semestral",
				Event::RECORRENCY_SUNDAY => "Todo domingo",
				Event::RECORRENCY_MONDAY => "Toda segunda",
				Event::RECORRENCY_TUESDAY => "Toda terça",
				Event::RECORRENCY_WEDNESDAY => "Toda quarta",
				Event::RECORRENCY_THURSDAY => "Toda quinta",
				Event::RECORRENCY_FRIDAY => "Toda sexta",
				Event::RECORRENCY_SATURDAY => "Toda sábado",
				Event::ERROR_1 => "ERROR_1 - O tipo de recorrência informada é inválida",
				Event::ERROR_2 => "ERROR_2 - Para informar se um evento é particular ou não informe um valor booleano" 
		);
		
		// English United States
		$languages ["en_US"] = array (
				Event::RECORRENCY_NO => "No recorrencies",
				Event::RECORRENCY_DAY => "Every day",
				Event::RECORRENCY_WEEK => "Every week",
				Event::RECORRENCY_MONTH => "Every month",
				Event::RECORRENCY_YEAR => "Every year",
				Event::RECORRENCY_BIMESTRAL => "Bimestral",
				Event::RECORRENCY_TRIMESTRAL => "Trimestral",
				Event::RECORRENCY_SEMESTRAL => "Semestral",
				Event::RECORRENCY_SUNDAY => "Every sunday",
				Event::RECORRENCY_MONDAY => "Every monday",
				Event::RECORRENCY_TUESDAY => "Every tuesday",
				Event::RECORRENCY_WEDNESDAY => "Every wednesday",
				Event::RECORRENCY_THURSDAY => "Every thusday",
				Event::RECORRENCY_FRIDAY => "Every friday",
				Event::RECORRENCY_SATURDAY => "Every saturday",
				Event::ERROR_1 => "ERROR_1 - The informed recurrency type is inválid",
				Event::ERROR_2 => "ERROR_2 - To inform if an event is private or not use a boolean value" 
		);
		
		return $languages [Configuration::getSelectedLanguage ()] [$descriptionId];
	}
}
?>