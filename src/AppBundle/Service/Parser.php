<?php

namespace AppBundle\Service;

use AppBundle\Entity\Affaire;

class Parser
{
	/**
     *	Filtre les mail concernant les demandes de devis.
     *
     *	@param $mail : Mail à filtrer.
     *	@param boolean : true si demande de devis.
     *					 false sinon.
     */
	public function preFilter($mail){
		$subject = $mail->Subject;
		$token = explode(" - ", $subject);

		if( sizeof( $token ) > 1 ){
			return true;
		}else{
			return false;
		}
	}

	/**
     *	Retourne un type Affaire à partir d'un mail reçu
     *
     *	@param $mail : Mail à parser.
     *	@param 
     */
	public function parser($mail){
		$subject = $mail->Subject;
		$token = explode(" - ", $subject);
		if( $token[1] === "Demande devis site web" ){
			return Parser::frParser($mail);
		}elseif( $token[1] === "Demande devis site web Allemand" ){
			return Parser::alParser($mail);
		}
	}

	/**
     *	Sépare une ligne en 2 éléments.
     *
     *	@param $line : Ligne à séparer
     *	@return tableau contenant le nom de la valeur, et la valeur associée 
     */
	private function parseLine( string $line ): string
	{
		$data = explode(" : ", $line, 2);
		if(sizeof($data) >= 2){
			return explode(" : ", $line, 2)[1];
		}else{
			return '';
		}
	}

	/**
     *	Créer une affaire à partir du contenue d'un mail provenant de France
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function frParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body, 13);

		$affaire = new Affaire();

		switch (Parser::parseLine($token[0])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType(Parser::parseLine($token[0]));
				break;
		}
		
		$affaire->setSociete(Parser::parseLine($token[1]));
		if( Parser::parseLine($token[2]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom(Parser::parseLine($token[3]));
		$withGoogleMap = Parser::parseLine($token[4]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setCP(Parser::parseLine($token[5]));
		$affaire->setVille(Parser::parseLine($token[6]));
		$affaire->setTelephone(str_replace(' ', '', Parser::parseLine($token[7])));
		$withMailTo = Parser::parseLine($token[8]);
		$affaire->setEmail(explode("<mailto:" ,$withMailTo)[0]);
		switch (Parser::parseLine($token[9])) {
			case 'Entreprise':
				$affaire->setSystemType('Ent.');
				break;
			case 'Education':
				$affaire->setSystemType('Educ.');
				break;
			case 'Assemblée Générale':
				$affaire->setSystemType('AG');
				break;
			default:
				$affaire->setSystemType(Parser::parseLine($token[9]));
				break;
		}
		$affaire->setNbController(Parser::parseLine($token[10]));
		$affaire->setProvenance(Parser::parseLine($token[11]));
		$affaire->setCommentaire(Parser::parseLine($token[12]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeCreated), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'un mail provenant d'Allemagne
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function alParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body, 15);

		$affaire = new Affaire();

		switch (Parser::parseLine($token[1])) {
			case 'Renseignements':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType(Parser::parseLine($token[1]));
				break;
		}
		$affaire->setSociete(Parser::parseLine($token[2]));
		if( Parser::parseLine($token[3]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom(Parser::parseLine($token[4]));
		$withMailTo = Parser::parseLine($token[5]);
		$affaire->setEmail(explode("<mailto:", $withMailTo)[0]);
		$withGoogleMap = Parser::parseLine($token[6]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', Parser::parseLine($token[7])));
		$affaire->setCP(Parser::parseLine($token[8]));
		$affaire->setVille(Parser::parseLine($token[9]));
		
		
		switch (Parser::parseLine($token[10])) {
			case 'Entreprise':
				$affaire->setSystemType('Ent.');
				break;
			case 'Education':
				$affaire->setSystemType('Educ.');
				break;
			case 'Assemblée Générale':
				$affaire->setSystemType('AG');
				break;
			default:
				$affaire->setSystemType(Parser::parseLine($token[10]));
				break;
		}
		//Nb systeme...
		$affaire->setNbController(Parser::parseLine($token[12]));
		$affaire->setProvenance(Parser::parseLine($token[13]));
		$affaire->setCommentaire(Parser::parseLine($token[14]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeCreated), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}
}