<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_savelistparams.class.php
 * \ingroup savelistparams
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsSaveListParams
 */
class ActionsSaveListParams
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	function updateSession($parameters, &$object, &$action, $hookmanager) {
		// Vérification que la page est une liste
		$page = $_SERVER['SCRIPT_NAME'];
		
		if(strpos($page,'list.php') > 0) {
			// Récupération des paramètres enregistrés en session pour la page
			$sessionParams = $_SESSION['savelistparams'][$page];
			
			// Récupération des paramètres passés à la page
			parse_str($_SERVER['QUERY_STRING'], $TParams);
			
			// On enlève les paramètres des menus
			unset($TParams['mainmenu']);
			unset($TParams['leftmenu']);
			
			// Si paramètres passés, l'utilisateur a filtré ou trié, on enregistre les paramètres
			if(!empty($TParams)) {
				// Cas de la réinitialisation des filtres, on supprimes les paramètres enregistrés
				if(isset($TParams['button_removefilter_x']) || isset($TParams['button_removefilter_y'])) {
					unset($_SESSION['savelistparams'][$page]);
				} else {
					$_SESSION['savelistparams'][$page] = $TParams;
				}
			}
			// Si pas d'autre paramètres, on applique ceux en mémoire session et on redirige
			else if(!empty($sessionParams))
			{
				$params = http_build_query($sessionParams);
				header('Location: '.$_SERVER['PHP_SELF'].'?'.$params);
			}
		}
		
		return 0;
	}
}