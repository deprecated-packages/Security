<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\DI;


interface ModularFirewallInterface
{

	/**
	 * @return string
	 */
	function getFirewallName();

}
