<?php
// Création de la classe DisclaimerOpitons
class DisclaimerOptions {
	// Propriétés privées de la classe
    private $id_disclaimer;
    private $message_disclaimer;
    private $redirection_ko;

	// Création des getters et setters de la classe DisclaimerOptions

	/**
	 * @return mixed
	 */
	public function getId_disclaimer() {
		return $this->id_disclaimer;
	}

	/**
	 * @return mixed
	 */
	// Méthode pour obtenir la valeur de la propriété $message_disclaimer
	public function getMessage_disclaimer() {
		return $this->message_disclaimer;
	}

	/**
	 * Méthode pour définir la valeur de la propriété $message_disclaimer
	 * 
	 * @param mixed $message_disclaimer La nouvelle valeur à définir
	 * @return self L'instance de la classe actuelle
	 */
	public function setMessage_disclaimer($message_disclaimer): self {
		// Affecte la nouvelle valeur à la propriété $message_disclaimer
		$this->message_disclaimer = $message_disclaimer;
		
		// Retourne l'instance de la classe actuelle
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getRedirection_ko() {
		return $this->redirection_ko;
	}
	
	/**
	 * @param mixed $redirection_ko 
	 * @return self
	 */
	public function setRedirection_ko($redirection_ko): self {
		$this->redirection_ko = $redirection_ko;
		return $this;
	}
}
