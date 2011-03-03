<?php

abstract class db_abs {
	private $_error	= '';
	
	/*
	 * Connect/opent de database en creeert indien nodig de nodige tabellen.
	 *
	 * Geeft true terug als connectie gelukt is, anders false.
	 */
	abstract function connect();
	
	/*
	 * Voer query uit en vergeet de output (true indien geen error).
	 * SQL statements worden niet ge-escaped of iets dergelijks.
	 */
	abstract function rawExec($sql);
	
	/*
	 * Voer query uit met $params aan parameters. Alle parameters worden eerst
	 * door de safe() functie gehaald om SQL injectie te voorkomen.
	 *
	 * Geeft een enkele rij terug met resulaten (associative array), of 
	 * FALSE in geval van een error
	 */
	abstract function singleQuery($sql, $params = array());

	/*
	 * Voer query uit met $params aan parameters. Alle parameters worden eerst
	 * door de safe() functie gehaald om SQL injectie te voorkomen.
	 *
	 * Geeft een array terug met alle resulaten (associative array), of 
	 * FALSE in geval van een error
	 */
	abstract function arrayQuery($sql, $params = array());
	
	/*
	 * Voert de database specifieke "safe-parameter" functie uit.
	 */
	abstract function safe($s);	
	
	/*
	 * Prepared de query string door vsprintf() met safe() erover heen te gooien
	 */
	function prepareSql($s, $p) {
		#
		# Als er geen parameters zijn mee gegeven, dan voeren we vsprintf() ook niet
		# uit, dat zorgt er voor dat we bv. LIKE's kunnen uitvoeren (met %'s) zonder
		# dat vsprintf() die probeert te interpreteren.
		if (empty($p)) {
			return $s;
		} else {
			$p = array_map(array($this, 'safe'), $p);
			return vsprintf($s, $p);
		} # else
	} # prepareSql()

	/*
	 * Voer een query uit en geef het resultaat (resource of handle) terug
	 */
	function exec($s, $p = array()) {
		return $this->rawExec($this->prepareSql($s, $p));
	} # exec()

	/*
	 * Set een bepaalde error string zodat, we storen deze hier in plaats 
	 * de database specifieke op te halen omdat we willen dat er eventueel nog
	 * extra informatie bijgezet kan worden.
	 */
	function setError($s) {
		$this->_error = $s;
	} # setError

	/*
	 * Geeft de error string terug. 
	 */
	function getError() {
		return $this->_error;
	} # getError
}