<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Affaire;

class Mail
{
	public function mailParser($mail)
	{
		$body = $mail->Body->_;
		$data = array();
		$token = explode("\r\n", $body, 13);

		$affaire = new Affaire();

		$affaire->setDevisType(explode(" : " , $token[0], 2)[1]);
		$affaire->setSociete(explode(" : " , $token[1], 2)[1]);
		if( explode(" : " , $token[2], 2)[1] == 'Monsieur'){
			$affaire->setCivilite('M.');
		}else{
			$affaire->setCivilite('Mme');
		}
		$affaire->setNom(explode(" : " , $token[3], 2)[1]);
		$affaire->setRue(explode(" : " , $token[4], 2)[1]);
		$affaire->setCP(explode(" : " , $token[5], 2)[1]);
		$affaire->setVille(explode(" : " , $token[6], 2)[1]);
		$affaire->setTelephone(str_replace(' ', '', explode(" : " , $token[7], 2)[1]));
		$withMailTo = explode(" : " , $token[8], 2)[1];
		$affaire->setEmail(explode("<mailto:" ,$withMailTo)[0]);
		$affaire->setSystemType(explode(" : " , $token[9], 2)[1]);
		$affaire->setNbController(explode(" : " , $token[10], 2)[1]);
		$affaire->setProvenance(explode(" : " , $token[11], 2)[1]);
		$affaire->setCommentaire(explode(" : " , $token[12], 2)[1]);

		$affaire->setDebut( substr( $mail->DateTimeCreated, 0, 10) );
		$affaire->setEtat( 'En Cours' );

		return $affaire;


	}
}