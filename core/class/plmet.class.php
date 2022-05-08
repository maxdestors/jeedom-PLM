<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class plmet extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  public static function cron() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  */
  public static function cronHourly($_eqLogic_id = null) {
		if ($_eqLogic_id == null) { // La fonction n’a pas d’argument donc on recherche tous les équipements du plugin
			$eqLogics = self::byType('vdm', true);
		} else { // La fonction a l’argument id(unique) d’un équipement(eqLogic
			$eqLogics = [self::byId($_eqLogic_id)];
		}		  
	
		foreach ($eqLogics as $eq) { //parcours tous les équipements du plugin
			if ($eq->getIsEnable() == 1) { //vérifie que l'équipement est acitf
				$cmd = $eq->getCmd(null, 'refresh'); //retourne la commande "refresh si elle existe
				if (!is_object($cmd)) { //Si la commande n'existe pas
					continue; //continue la boucle
				}
				$cmd->execCmd(); // la commande existe on la lance
			}
		}
	}


  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $info = $this->getCmd(null, 'story');
    if (!is_object($info)) {
      $info = new plmetCmd();
      $info->setName(__('Histoire', __FILE__));
    }
    $info->setLogicalId('story');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();

    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new plmetCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $refresh->save();
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  public function getET() {
    $param1 = $this->getConfiguration("param1");
    log::add('plmet', 'debug', 'Refresh param1='.print_r($param1, true));
    $param2 = $this->getConfiguration("param2");
    log::add('plmet', 'debug', 'Refresh param2='.print_r($param2, true));
    $type = $this->getConfiguration("type");
    log::add('plmet', 'debug', 'Refresh type='.print_r($type, true));

    $this->getConfiguration("type");
    $builder = new UrlBuilderv2('qtxx3akao8cbppvrszygtvtj7hzzjfvp', 'sdtpxwfglvxygc7po3wksksi54dnejp9');
    $url = $builder->getFullUrl('/stations', []);
    $data = file_get_contents($url);
    log::add('plmet', 'debug', 'Refresh data='.print_r($data, true));
    $json = json_decode($data);
    return $json->generated_at;
  }
  

  /*     * **********************Getteur Setteur*************************** */

}

class plmetCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
    $eqLogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
    switch ($this->getLogicalId()) { //vérifie le logicalid de la commande
      case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe vdm .
        $info = $eqLogic->getET(); //On lance la fonction randomVdm() pour récupérer une vdm et on la stocke dans la variable $info
        log::add('plmet', 'debug', 'Refresh info='.$info);
        $eqLogic->checkAndUpdateCmd('story', $info); //on met à jour la commande avec le LogicalId "story"  de l'eqlogic
        break;
    }
  }

  /*     * **********************Getteur Setteur*************************** */

}


class UrlBuilderv2 {

  private $baseUrl = 'https://api.weatherlink.com/v2';
  private $apiKey;
  private $apiSecret;

  public function __construct($apiKey, $apiSecret) {
      $this->apiKey = $apiKey;
      $this->apiSecret = $apiSecret;
  }

  public function getFullUrl($subUrl, $inputParameters) {
      $parameters = array_merge(
          $inputParameters,
          [
              "api-key" => $this->apiKey,
              "t" => time().''
          ]
      );
      $parameters['api-signature'] = $this->calculateSignature($parameters);
      return $this->baseUrl . $subUrl . '?' . http_build_query($parameters);
  }

  private function calculateSignature($parametersToHash) {
      ksort($parametersToHash);
      $stringToHash = "";
      foreach ($parametersToHash as $parameterName => $parameterValue) {
          $stringToHash = $stringToHash . $parameterName . $parameterValue;
      }
      $apiSignature = hash_hmac("sha256", $stringToHash, $this->apiSecret);
      return $apiSignature;
  }
}
