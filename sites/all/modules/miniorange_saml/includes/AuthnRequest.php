<?php
/**
 * @file
 * The AuthnRequest.php file for the miniorange_samlauth module.
 *
 * @package miniOrange
 *
 * @license GNU/GPLv3
 *
 * @copyright Copyright 2015 miniOrange. All Rights Reserved.
 *
 * This file is part of miniOrange SAML plugin.
 */

/**
 * The MiniOrangeAuthnRequest class.
 */
class MiniOrangeAuthnRequest {

  /**
   * The function initiateLogin.
   */
  public function initiateLogin($acs_url, $sso_url, $issuer) {
	$nameid_format = variable_get('miniorange_nameid_format',"");
	$relay_state = $_SERVER['HTTP_REFERER'];
    $saml_request = Utilities::createAuthnRequest($acs_url, $issuer, $sso_url, $nameid_format);
	
    if (strpos($sso_url, '?') > 0) {
		 
      $redirect = $sso_url . '&SAMLRequest=' . $saml_request . '&RelayState=' . urlencode($relay_state);
    }
    else {
      $redirect = $sso_url . '?SAMLRequest=' . $saml_request . '&RelayState=' . urlencode($relay_state);	
    }
    
    header('Location: ' . $redirect);
  }

}