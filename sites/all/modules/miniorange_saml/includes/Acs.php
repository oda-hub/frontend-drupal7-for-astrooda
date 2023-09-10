<?php
/**
 * @package    miniOrange
 * @author	   miniOrange Security Software Pvt. Ltd.
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
 *
 *
 * This file is part of miniOrange SAML plugin.
 */

/**
 * The MiniOrangeAcs class.
 */
class MiniOrangeAcs {

  /**
   * The function processSamlResponse.
   */
  public function processSamlResponse($post, $acs_url, $cert_fingerprint, $issuer, $b_url, $spEntityId) {


    if (array_key_exists('SAMLResponse', $post)) {
      $saml_response = $post['SAMLResponse'];
    }
    else {
      throw new Exception('Missing SAMLRequest or SAMLResponse parameter.');
    }

	if(array_key_exists('RelayState', $post)) {
		$RelayState = $post['RelayState'];
	}
	else {
		$RelayState = '';
	}
    $saml_response = base64_decode($saml_response);
    $document = new DOMDocument();
    $document->loadXML($saml_response);
    $saml_response_xml = $document->firstChild;

    if ($RelayState == "showSamlResponse") {
        Utilities::Print_SAML_Request($saml_response,"displaySamlResponse");
    }

	$doc = $document->documentElement;
		$xpath = new DOMXpath($document);
		$xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
		$xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

		$status = $xpath->query('/samlp:Response/samlp:Status/samlp:StatusCode', $doc);
		$statusString = $status->item(0)->getAttribute('Value');
		$statusChildString = '';
		if($status->item(0)->firstChild !== null){
			$statusChildString = $status->item(0)->firstChild->getAttribute('Value');
		}

		$stat = explode(":",$statusString);
		$status = $stat[7];

		if($status!="Success"){
			if(!empty($statusChildString)){
				$stat = explode(":", $statusChildString);
				$status = $stat[7];
			}
			$this->show_error_message($status, $RelayState);
		}

    $cert_fingerprint = XMLSecurityKey::getRawThumbprint($cert_fingerprint);

    $saml_response = new SAML2_Response($saml_response_xml);
    $cert_fingerprint = preg_replace('/\s+/', '', $cert_fingerprint);
    if (variable_get('miniorange_saml_character_encoding', TRUE)){
      $cert_fingerprint = iconv("UTF-8", "CP1252//IGNORE", $cert_fingerprint);
    }
    
      $response_signature_data = $saml_response->getSignatureData();
      $assertion_signature_data = current($saml_response->getAssertions())->getSignatureData();
      if (is_null($response_signature_data) && is_null($assertion_signature_data)) {
        echo 'Neither response nor assertion is signed';
        exit();
      }


      if ( !is_null($response_signature_data)  ) {
        $response_valid_signature = Utilities::processResponse($acs_url, $cert_fingerprint, $response_signature_data, $saml_response ,$RelayState);
        if(!$response_valid_signature)
        {
          echo 'Invalid Signature in SAML Response';
          exit();
        }
      }

      if ( !is_null($assertion_signature_data) ) {
        $assertion_valid_signature = Utilities::processResponse($acs_url, $cert_fingerprint, $assertion_signature_data, $saml_response, $RelayState);
        if(  !$assertion_valid_signature )
        {
          echo 'Invalid Signature in SAML Assertion';
          exit();
        }
      }
    




    Utilities::validateIssuerAndAudience($saml_response, $spEntityId, $issuer, $b_url, $RelayState);

    $username = current(current($saml_response->getAssertions())->getNameId());
    $attrs = current($saml_response->getAssertions())->getAttributes();
    // Get RelayState if any.
    if(array_key_exists('RelayState', $post)) {
      if($post['RelayState'] == 'testValidate') {
        $this->showTestResults($username, $attrs);
      }
    }
    return $username;

  }

  function show_error_message($statusCode, $relayState){
		if($relayState=='testValidate'){

			echo '<div style="font-family:Calibri;padding:0 3%;">';
			echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
			<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong> Invalid SAML Response Status.</p>
			<p><strong>Causes</strong>: Identity Provider has sent \''.$statusCode.'\' status code in SAML Response. </p>
							<p><strong>Reason</strong>: '.$this->get_status_message($statusCode).'</p><br>
			</div>

			<div style="margin:3%;display:block;text-align:center;">
			<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
							exit;
		  }
		  else{
				if($statusCode == 'RequestDenied' ){
					echo 'You are not allowed to login into the site. Please contact your Administrator.';
					exit;
				}else{
					echo 'We could not sign you in. Please contact your Administrator.';
					exit;
				}
		  }
	}

	function get_status_message($statusCode){
		switch($statusCode){
			case 'RequestDenied':
				return 'You are not allowed to login into the site. Please contact your Administrator.';
				break;
			case 'Requester':
				return 'The request could not be performed due to an error on the part of the requester.';
				break;
			case 'Responder':
				return 'The request could not be performed due to an error on the part of the SAML responder or SAML authority.';
				break;
			case 'VersionMismatch':
				return 'The SAML responder could not process the request because the version of the request message was incorrect.';
				break;
			default:
				return 'Unknown';
		}
	}

  public function showTestResults($username, $attrs) {
    $module_path = drupal_get_path('module', 'miniorange_saml');

    echo '<div style="font-family:Calibri;padding:0 3%;">';
    if(!empty($username)) {
      echo '<div style="color: #3c763d;
          background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
          <div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. $module_path . '/includes/images/green_check.png"></div>';
    }
    else {
      echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
          <div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Some Attributes Did Not Match.</div>
          <div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. $module_path . 'includes/images/wrong.png"></div>';
    }
    echo '<span style="font-size:14pt;"><b>Hello</b>, '.$username.'</span><br/><p style="font-weight:bold;font-size:14pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p>
        <table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;background-color:#EDEDED;">
        <tr style="text-align:center;"><td style="font-weight:bold;border:2px solid #949090;padding:2%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td></tr>';
    echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>NameID</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" . $username . "</td></tr>";
	if(!empty($attrs)) {
	  foreach ($attrs as $key => $value) {
        echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>" .$key . "</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" . implode("<br/>",$value) . "</td></tr>";
      }
    }

    echo '</table></div>';
    echo '<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="window_close();"></div>
    <script>
       function window_close(){
            window.opener.location.href = "admin/config/people/miniorange_saml/sp_setup";
            self.close();
       }
        </script>';
    exit;
  }

}
