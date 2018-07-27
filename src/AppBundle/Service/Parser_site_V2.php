<?php

namespace AppBundle\Service;

use AppBundle\Entity\Affaire;

class Parser_site_V2
{
	public function preFilter($mail){
		$xpath = $this->getMailContent($mail);
		$nlist = $xpath->query('//meta[@name="type_demande"]/@content');

		if(sizeof($nlist)){
			return true;
		}else{
			return false;
		}
	}

	public function getMailContent($mail){
		$html = $mail->Body->_;
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($html);
		$xpath = new \DOMXPath($doc);

		return $xpath;
	}


	public function parser($mail){

		$xpath = $this->getMailContent($mail);
		$affaire = $this->parseBody( $xpath );

		$this->setOtherData( $affaire, $mail);
		

		return $affaire;
	}

	public function parseBody($xpath){
		$affaire = new Affaire();
		$nlist = $xpath->query('//span');

		foreach( $nlist as $node ){
			switch ($node->getAttribute("data-type")) {
				case "devis_type":
					switch ($node->nodeValue) {
						case 'renseignement':
							$affaire->setDevisType('Rens.');
							break;
						case 'location':
							$affaire->setDevisType('Loc.');
							break;
						case 'achat':
							$affaire->setDevisType('Achat');
							break;
					}
					break;
				case "societe":
					$affaire->setSociete($node->nodeValue);
					break;
				case "civilite":
					if( $node->nodeValue == 'f'){
						$affaire->setCivilite('Mme');
					}else{
						$affaire->setCivilite('M.');
					}
					break;
				case "nom":
					$affaire->setNom($node->nodeValue);
					break;
				case "rue":
					$affaire->setRue($node->nodeValue);
					break;
				case "cp":
					$affaire->setCP($node->nodeValue);
					break;
				case "ville":
					$affaire->setVille($node->nodeValue);
					break;
				case "tel":
					$affaire->setTelephone(str_replace(' ', '', $node->nodeValue));
					break;
				case "email":
					$affaire->setEmail($node->nodeValue);
					break;
				case "system_type":
					switch ($node->nodeValue) {
						case 'Entreprise':
							$affaire->setSystemType('Ent.');
							break;
						case 'Education':
							$affaire->setSystemType('Educ.');
							break;
						case 'Assemblee':
							$affaire->setSystemType('AG');
							break;
						case 'Universite':
							$affaire->setSystemType('Univ.');
							break;
						default:
							$affaire->setSystemType($node->nodeValue);
							break;
					}
					break;
				case "nb_controller":
					$affaire->setNbController($node->nodeValue);
					break;
				case "provenance":
					$affaire->setProvenance($node->nodeValue);
					break;
				case "commentaire":
					$affaire->setCommentaire($node->nodeValue);
					break;
			}
		}

		return $affaire;
	}


	public function setOtherData($affaire, $mail){

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

	}

}