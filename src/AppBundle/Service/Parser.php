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
		$isTR = strpos('z'.$subject, "TR:") + strpos('z'.$subject, "FW:") + strpos('z'.$subject, "FWD:"); // 'z'. permet de ne jamais avoir 0 si TR: est le premier mot
		$isRE = strpos('z'.$subject, "RE:");
		if( $isTR != 1 && $isRE != 1){
			$isDemand = stripos('z'.$subject, "Demande");

			if( $isDemand && (sizeof( $token ) > 1 || strpos( $subject , " Demande internet provenance mobile" ))){
				return true;
			}
		}

		return false;
		
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

		if( sizeof($token) > 1){
			if( $token[1] === "Demande devis site web Allemand" ){
				return $this->alDevisParser($mail);
			}elseif( $token[1] === "Demande devis site web Anglais" ){
				return $this->enDevisParser($mail);
			}elseif( $token[1] === "Demande devis site web" ){
				return $this->frDevisParser($mail);
			}elseif( $token[1] === "Demande internet provenance mobile Allemand" ){
				if(sizeof(explode("\r\n", $mail->Body->_)) < 10){
					return $this->al_enContactMobileParser($mail);
				}else{
					return $this->alDevisMobileParser($mail);
				}
			}elseif( $token[1] === "Demande internet provenance du site Mobile Anglais" ){
				return $this->enDevisMobileParser($mail);
			}elseif( $token[1] === "Demande internet provenance du site Mobile" ){
				return $this->frDevisMobileParser($mail);
			}elseif( $subject === "Demande de contact - Site web Allemand" || $subject === "Demande de contact - Site web Anglais" || $token[1] === "Demande de contact site internet" ){
				return $this->contactParser($mail);
			}elseif( $token[1] === "Demande internet provenance mobile Anglais" ){
				return $this->al_enContactMobileParser($mail);
			}elseif( $token[1] === "Demande internet provenance mobile" ){
				return $this->frContactMobileParser($mail);
			}
		}elseif(strpos( $subject , " Demande internet provenance mobile" )){
			return $this->frContactMobileParser($mail);
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
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site Allemand
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function alDevisParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[1])) {
			case 'Renseignements':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[1]));
				break;
		}
		$affaire->setSociete($this->parseLine($token[2]));
		$civilite = $this->parseLine($token[3]);
		if( $civilite == 'Monsieur'){
			$affaire->setCivilite('M.');
		}elseif( $civilite == 'Madame'){
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[4]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		$withGoogleMap = $this->parseLine($token[6]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[7])));
		$affaire->setCP($this->parseLine($token[8]));
		$affaire->setVille($this->parseLine($token[9]));
		
		
		switch ($this->parseLine($token[10])) {
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
				$affaire->setSystemType($this->parseLine($token[10]));
				break;
		}
		//Nb systeme...
		$affaire->setNbController($this->parseLine($token[12]));
		$affaire->setProvenance($this->parseLine($token[13]));
		$affaire->setCommentaire($this->parseLine($token[14]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}


	/**
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site Anglais
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function enDevisParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[0])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[0]));
				break;
		}
		
		$affaire->setSociete($this->parseLine($token[1]));
		$civilite = $this->parseLine($token[2]);
		if( $civilite == 'Monsieur'){
			$affaire->setCivilite('M.');
		}elseif( $civilite == 'Madame'){
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[3]));
		$withGoogleMap = $this->parseLine($token[4]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[5])));
		$affaire->setCP($this->parseLine($token[6]));
		$affaire->setVille($this->parseLine($token[7]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		switch ($this->parseLine($token[9])) {
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
				$affaire->setSystemType($this->parseLine($token[9]));
				break;
		}

		//Nb systeme...
		$affaire->setNbController($this->parseLine($token[11]));
		$affaire->setProvenance($this->parseLine($token[12]));
		$affaire->setCommentaire($this->parseLine($token[13]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}


	/**
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site Français
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function frDevisParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[0])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[0]));
				break;
		}
		
		$affaire->setSociete($this->parseLine($token[1]));
		$civilite = $this->parseLine($token[2]);
		if( $civilite == 'Monsieur'){
			$affaire->setCivilite('M.');
		}elseif( $civilite == 'Madame'){
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[3]));
		$withGoogleMap = $this->parseLine($token[4]);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setCP($this->parseLine($token[5]));
		$affaire->setVille($this->parseLine($token[6]));
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[7])));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		switch ($this->parseLine($token[9])) {
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
				$affaire->setSystemType($this->parseLine($token[9]));
				break;
		}
		$affaire->setNbController($this->parseLine($token[10]));
		$affaire->setProvenance($this->parseLine($token[11]));
		$affaire->setCommentaire($this->parseLine($token[12]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site mobile Allemand
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function alDevisMobileParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[1])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[1]));
				break;
		}
		
		$affaire->setSociete($this->parseLine($token[2]));
		if( $this->parseLine($token[3]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[4]));
		$withGoogleMap = substr($token[5], 8);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[6])));
		$affaire->setVille($this->parseLine($token[7]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		switch ($this->parseLine($token[9])) {
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
				$affaire->setSystemType($this->parseLine($token[9]));
				break;
		}
		$affaire->setNbController($this->parseLine($token[10]));
		$affaire->setCommentaire($this->parseLine($token[11]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site mobile Anglais
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function enDevisMobileParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[1])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[1]));
				break;
		}
		
		$affaire->setSociete($this->parseLine($token[2]));
		if( $this->parseLine($token[3]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[4]));
		$withGoogleMap = substr($token[6], 8);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[7])));
		$affaire->setVille($this->parseLine($token[8]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		switch ($this->parseLine($token[9])) {
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
				$affaire->setSystemType($this->parseLine($token[9]));
				break;
		}
		$affaire->setNbController($this->parseLine($token[10]));
		$affaire->setCommentaire($this->parseLine($token[11]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'une demande de devis provenant du site mobile Français
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function frDevisMobileParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		switch ($this->parseLine($token[1])) {
			case 'Renseignement':
				$affaire->setDevisType('Rens.');
				break;
			case 'Location':
				$affaire->setDevisType('Loc.');
				break;
			default:
				$affaire->setDevisType($this->parseLine($token[1]));
				break;
		}
		
		$affaire->setSociete($this->parseLine($token[2]));
		if( $this->parseLine($token[3]) == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom($this->parseLine($token[4]));
		$withGoogleMap = substr($token[6], 8);
		$affaire->setRue(explode("<https://", $withGoogleMap)[0]);
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[7])));
		$affaire->setCP($this->parseLine($token[8]));
		$affaire->setVille($this->parseLine($token[9]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		switch ($this->parseLine($token[10])) {
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
				$affaire->setSystemType($this->parseLine($token[10]));
				break;
		}
		$affaire->setNbController($this->parseLine($token[11]));
		$affaire->setCommentaire($this->parseLine($token[12]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}
	
	/**
     *	Créer une affaire à partir du contenue d'une demande de contact
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function contactParser($mail){
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		$affaire->setSociete($this->parseLine($token[2]));
		$affaire->setNom($this->parseLine($token[3]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		$affaire->setCommentaire($this->parseLine($token[4]));
		$affaire->setTelephone(str_replace(' ', '', $this->parseLine($token[5])));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'une demande de contact mobile Allemand ou Anglais
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function al_enContactMobileParser($mail){
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		$affaire->setSociete($this->parseLine($token[1]));
		$affaire->setNom($this->parseLine($token[2]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		$affaire->setCommentaire($this->parseLine($token[4]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

	/**
     *	Créer une affaire à partir du contenue d'une demande de contact mobile Français
     *
     *	@param $mail : mail à traiter
     *	@return AppBundle\Entity\Affaire
     */
	public function frContactMobileParser($mail){
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body);

		$affaire = new Affaire();

		$affaire->setSociete($this->parseLine($token[2]));
		$affaire->setNom($this->parseLine($token[3]));
		$affaire->setEmail($mail->From->Mailbox->EmailAddress);
		$affaire->setCommentaire($token[4]);
		$affaire->setTelephone(str_replace(' ', '', $token[5]));

		$affaire->setDebut(new \DateTime(substr( str_replace("T", " ", $mail->DateTimeReceived), 0, 19)));
		$affaire->setEtat( 'En Cours' );

		return $affaire;
	}

}