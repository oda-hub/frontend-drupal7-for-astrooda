<?php
/**
 * @file
 * Contains Service Provider information for miniOrange SAML Login Module.
 */

/**
 * Showing IdP Setup form info.
 */

include "includes/MetadataReader.php";
include "mo_saml_visualTour.php";
function miniorange_saml_idp_setup($form, &$form_state){

    drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));

  $pdo_exception_error = Utilities::pdo_exception_error();
  if($pdo_exception_error){
    $form['markup_reg_msg'] = array(
      '#markup' => '<div class="mo_saml_register_message" style="padding-left: 100px; padding-right: 100px;">User is trying to perform the SSO and unable to create an account. The possible cause for this error could be the Email ID is missing in the NameID attribute of IDP response. Also, Username and Email ID should be unique for different users. </div>',
    );
  }

    $form['header_top_style_1'] = array('#markup' => '<div class="mo_saml_table_layout_1">');

    $form['markup_top'] = array('#markup' => '<div class="mo_saml_table_layout mo_saml_container">',);

    $form['miniorange_saml_IDP_tab'] = array(
        '#markup' => '<div id="tabhead"><h5> Enter the information gathered from your Identity Provider &nbsp; OR &nbsp;<a class="mo_saml_btn mosaml_upload mo_saml_btn-primary btn-large"
                      id="mosaml_upload" style="padding:5px 10px;margin-left: 0px;box-shadow: 0 1px 0 #006799;" onclick="show_metadata_form()">Upload IDP Metadata</a>
                     <a class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_restart_button" id="mo_saml_restart_tour_button" style="margin-right: 0px;" >Take a Tour</a></h5></div><hr>',
    );

    $form['metadata_1'] = array(
        '#markup' =>'<div border="1" id="upload_metadata_form">
                        <table style="width:100%;">
                            <tr> <td colspan="3">
                                <h3>UPLOAD IDP METADATA
                                    <span style="float:right;margin-right:25px;">
                                        <input type="button" class="mo_saml_btn mo_saml_btn-sm mo_saml_btn-danger" value="Cancel" onclick = "hide_metadata_form()"/></a><br>
                                    </span><br><br><hr>',
    );

    $form['metadata_file'] = array(
        '#type' => 'file',
        '#prefix' => '</span></h1></td></tr><tr><td><h4>Upload Metadata  :</h4><td colspan="2">',
    );

    $form['metadata_upload'] = array(
        '#type' => 'submit',
        '#value' => t('Upload File'),
        '#submit' => array('miniorange_saml_upload_file'),
        '#attributes' => array('style' => 'border-radius:4px;background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;'),
    );

    $form['metadata_3'] = array(
        '#markup' =>'</td></td></tr>
                     <tr>
                        <td colspan="3" >
                            <p style="font-size:13pt;text-align:center;"><b>OR<hr><hr></b></p>
                        </td>
                     </tr>
                     <tr>
                         <td width="20%"><h4>Enter metadata URL:</h4></td>
                         <td width="10%">',
    );

    $form['metadata_URL'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => 'Enter metadata URL of your IdP.'),
    );

    $form['metadata_4'] = array(
        '#markup' =>'</td><td>',
    );

    $form['metadata_fetch'] = array(
        '#type' => 'submit',
        '#value' => t('Fetch Metadata'),
        '#submit' => array('miniorange_saml_fetch_metadata'),
        '#attributes' => array('style' => 'border-radius:4px;background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;'),
    );

    $form['metadata_4'] = array(
        '#markup' =>'</td></tr><tr><td></td><td>',
    );

    $form['miniorange_saml_fetch_metadata_after_specific_time'] = array(
        '#type' => 'checkbox',
        '#title' => t('Update IdP settings by pinging metadata URL ( We will store the metadata URL )<a href="' . Utilities::getLicensePageURL() . '"> [Enterprise]</a>'),
        '#disabled' => TRUE,
    );

    $form['note_for metadata_fetch'] = array(
        '#markup' => '<b>Note: </b>You can set how often you want to ping the IdP from <b><a style="font-size: small;" href="">Here</a> OR</b> you can goto <b>Configuration=>Cron=>Run Cron Every</b> section of your drupal site',
    );

    $form['metadata_5'] = array(
        '#markup' => '</td></tr></table></div><br><div id="idpdata">',
    );

    $form['miniorange_saml_idp_name'] = array(
        '#type' => 'textfield',
        '#title' => t('Identity Provider name<span class="miniorange_saml_red">*</span>'),
        '#default_value' => variable_get('miniorange_saml_idp_name', ''),
        '#attributes' => array('style' => 'width:81%','placeholder' => 'Identity Provider Name like ADFS, SimpleSAML etc.'),
        '#prefix' => '<div id="mosaml_vt_name">',
        '#suffix' => '</div>',
    );

    $form['miniorange_saml_idp_issuer'] = array(
        '#type' => 'textfield',
        '#title' => t('IdP Entity ID or Issuer<span class="miniorange_saml_red">*</span>'),
        '#description' => t('<b>Note :</b> You can find the EntityID in Your IdP-Metadata XML file enclosed in <code>EntityDescriptor</code> tag having<br> attribute as <code>entityID</code>'),
        '#default_value' => variable_get('miniorange_saml_idp_issuer', ''),
        '#attributes' => array('style' => 'width:81%','placeholder' => 'Enter IdP Entity ID url or Issuer url '),
        '#prefix' => '<div id="mosaml_vt_issuer">',
        '#suffix' => '</div>',
    );

    $form['miniorange_saml_idp_login_url'] = array(
        '#type' => 'textfield',
        '#title' => t('SAML Login URL<span class="miniorange_saml_red">*</span>'),
        '#description' => t('<b>Note :</b> You can find the SAML Login URL in Your IdP-Metadata XML file enclosed in <code>SingleSignOnService</code><br> tag (Binding type: HTTP-Redirect)'),
        '#default_value' => variable_get('miniorange_saml_idp_login_url', ''),
        '#attributes' => array('style' => 'width:81%','placeholder' => 'Single Sign-On Service URL (HTTP-Redirect binding) of your IdP'),
        '#prefix' => '<div id="mosaml_vt_loginUrl">',
        '#suffix' => '</div>',
    );

    $form['mo_saml_attrs_list_idp'] = array(
        '#markup' => '<div class="table-responsive" style="font-family: sans-serif;font-size: 12px;">
                        <form>
                        <h4>x.509 Certificate Value</h4>
                            <input type="radio" name="myRadios" onclick="toggle_view(\'#mosaml_vt_x509Cert\',\'#mo_saml_file\');" value="1" checked /> Enter as Text &nbsp;&nbsp;
                            <input type="radio" name="myRadios" onclick="toggle_view(\'#mo_saml_file\',\'#mosaml_vt_x509Cert\');" value="2" /> Upload Certificate<br>
                        </form>
                      </div>

                     <script>
                        function toggle_view(elementIDshow,elementIDhide) {
                            jQuery(elementIDshow).show();
                            jQuery(elementIDhide).hide();
                        }
                     </script>'
    );

    $form['mo_saml_certificate_div'] = array(
        '#markup' => '<div id="mosaml_vt_x509Cert">',
    );

    $form['miniorange_saml_idp_x509_certificate'] = array(
        '#type' => 'textarea',
        '#title' => t('x.509 Certificate Value'),
        '#cols' => '6',
        '#rows' => '5',
        '#default_value' => variable_get('miniorange_saml_idp_x509_certificate', ''),
        '#attributes' => array('style' => 'width:81%;','placeholder' => 'Open the certificate provided by your IdP in Notepad and Copy-Paste the entire content of the file in this textarea or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file'),
        '#resizable' => False,
        '#description'=>t('<b>NOTE:</b> Format of the certificate:<br><b>-----BEGIN CERTIFICATE-----<br>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br>-----END CERTIFICATE-----</b><br><br>'),
    );

    $form['mo_saml_certificate_div_end'] = array(
        '#markup' => '</div><div id="mo_saml_file" style = "display:none;">',
    );

    $form['mo_saml_cert_file'] = array(
        '#type' => 'file',
        '#attributes' => array('style' => 'float:left;margin-top: 14px;margin-right: 10px;'),
    );

    $form['metadata_upload_cert_file'] = array(
        '#type' => 'submit',
        '#value' => t('Upload'),
        '#submit' => array('mo_saml_upload_certificate'),
        '#attributes' => array('style' => 'margin-top:-27px;border-radius:4px;background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;'),

    );

    $form['miniorange_vt_divstart'] = array(
        '#markup'=>'</div><div id="mosaml_vt_enableLogin">'
    );

    $form['miniorange_saml_enable_login'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable login with SAML'),
        '#default_value' => variable_get('miniorange_saml_enable_login', FALSE),
    );
  
    $form['miniorange_saml_enable_field'] = array(
        '#type' => 'fieldset',
        '#states' => array(
                'visible' => array(
            ':input[name = "miniorange_saml_enable_login"]' => array('checked' => TRUE),),),
    );

    $form['miniorange_saml_enable_field']['miniorange_saml_enable_login_with_URL'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable login with URL'),
        '#default_value' => variable_get('miniorange_saml_enable_login_with_URL', FALSE ),
        );
    
    $form['miniorange_saml_enable_field']['login_text']=array(
        '#markup' => '<div><h1 align=center> <b> OR </b> </h1></div>' ,
    );

    $b_url = Utilities::miniorange_get_baseURL();
    $form['miniorange_saml_enable_field']['login_URL']=array(
        '#markup'=> '<div><p><p>Login with URL</p><a href=" '.$b_url.'/?q=samllogin"> '.$b_url.'/?q=samllogin</a></p></h5></div>',
    );

    $form['miniorange_saml_character_encoding'] = array(
    '#type' => 'checkbox',
    '#title' => t('Character Encoding'),
    '#default_value' => variable_get('miniorange_saml_character_encoding', TRUE),
    '#description' => '<strong>' . t('Note: ') . '</strong>' . t('Uses iconv encoding to convert X509 certificate into correct encoding.'),
  );

    $form['miniorange_vt_divend'] = array(
        '#markup'=>'</div><br>'
    );

    $form['security_signature_algorithm'] = array(
        '#type' => 'select',
        '#title' => t('Signature algorithm <a href="' . Utilities::getLicensePageURL() . '"> <b>[Enterprise].</b></a>'),
        '#options' => [
            'RSA_SHA256' => 'sha256',
            'RSA_SHA384' => 'sha384',
            'RSA_SHA512' => 'sha512',
            'RSA_SHA1' => 'sha1',
        ],
        '#description' => t('<b>Note:</b> Algorithm used in the signing process.'),
        '#attributes' => array('style' => 'width:44%'),
    );

    $form['Note'] = array(
        '#markup' => '<div class="mo_saml_highlight_background_note">',
    );

    $form['miniorange_saml_sign_request'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option to send Signed SSO and SLO requests.<a href="' . Utilities::getLicensePageURL() . '"> [Standard, Premium and Enterprise].</a>'),
        '#disabled' => TRUE,
    );

    $form['miniorange_saml_enable_logout'] = array(
        '#type' => 'checkbox',
        '#title' => t('Check this option for logout if you do not want to wait for response.<a href="' . Utilities::getLicensePageURL() . '"> [Premium and Enterprise]</a>'),
        '#description'=>'<b>Note: </b>Checking this option Users will be logout from Service Provider and Identity Provider without waiting for response from IdP.',
        '#disabled' => TRUE,
    );

    $form['miniorange_vt_divends'] = array(
        '#markup'=>'</div><br>'
    );

    $form['miniorange_saml_idp_config_submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save Configuration'),
        '#submit' => array('miniorange_saml_save_idp_config'),
    );

    $form['miniorange_saml_test_config_button'] = array(
        '#markup' => '<a class="mo_saml_btn mo_saml_test_config mo_saml_btn-primary btn-large" onclick="testConfig(\'' . getTestUrl() . '\');">'
            . 'Test Configuration</a><br>',
    );

    $form['miniorange_saml_test_show_SAML_request_button'] = array(
        '#markup' => '<a class="mo_saml_btn mo_saml_show_request mo_saml_btn-primary btn-large" onclick="showSAMLrequest(\'' . getSAMLrequest() . '\');">'
            . 'Show SAML Request</a>&nbsp',
    );

    $form['miniorange_saml_test_show_SAML_response_button'] = array(
        '#markup' => '&nbsp;&nbsp;<a class="mo_saml_btn mo_saml_show_response mo_saml_btn-primary btn-large" onclick="showSAMLresponse(\'' . getSAMLresponse() . '\');">'
            . 'Show SAML Response</a>',
    );

    $form['markup_support_close1'] = array(
        '#markup' => '</div></div>',
    );

    Utilities::spConfigGuide($form, $form_state);

    Utilities::AddSupportButton($form, $form_state);

    return $form;

}

function getTestUrl() {
    $b_url = Utilities::miniorange_get_baseURL();
    $testUrl = $b_url . '/?q=testConfig';
    return $testUrl;
}

function getSAMLrequest() {
    $b_url = Utilities::miniorange_get_baseURL();
    $SAMLrequestUrl = $b_url . '/?q=showSAMLrequest';
    return $SAMLrequestUrl;
}

function getSAMLresponse() {
    $b_url = Utilities::miniorange_get_baseURL();
    $SAMLresponseUrl = $b_url . '/?q=showSAMLresponse';
    return $SAMLresponseUrl;
}

function mo_saml_upload_certificate($form, &$form_state)
{
    if(! empty( $_FILES['files']['tmp_name']['mo_saml_cert_file'] )) {
        $certificate = $_FILES['files']['tmp_name']['mo_saml_cert_file'];
        $file_name = $_FILES['files']['name']['mo_saml_cert_file'];
        list($name_without_extention, $extention) = explode('.', $file_name);
        if($extention == 'crt' || $extention == 'cer' || $extention == 'cert')
        {
            $cert_content = file_get_contents( $certificate);
            $idp_name = $form['miniorange_saml_idp_name']['#value'];
            $issuer = $form['miniorange_saml_idp_issuer']['#value'];
            $login_url = $form['miniorange_saml_idp_login_url']['#value'];

            variable_set('miniorange_saml_idp_name', $idp_name);
            variable_set('miniorange_saml_idp_issuer', $issuer);
            variable_set('miniorange_saml_idp_login_url', $login_url);
            variable_set('miniorange_saml_idp_x509_certificate', $cert_content);
            drupal_set_message(t('x.509 Certificate Value successfully updated.'));
        }
        else{
            drupal_set_message(t('File type is not compatible!! Please Select <b class="miniorange_saml_red">".crt" or ".cer" or ".cert"</b> extended file to upload Configuration!'),'error');
        }
    }
    else{
        drupal_set_message(t('Please select file first to upload Configuration!'),'error');
    }
}
/**
 * Configure IdP.
 */
function miniorange_saml_save_idp_config($form, &$form_state) {
    $b_url = Utilities::miniorange_get_baseURL();
    $idp_name = $form['miniorange_saml_idp_name']['#value'];
    $issuer = $form['miniorange_saml_idp_issuer']['#value'];
    $nameid_format = "urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified";
    $login_url = $form['miniorange_saml_idp_login_url']['#value'];
    $x509_cert_value = Utilities::sanitize_certificate($form['miniorange_saml_idp_x509_certificate']['#value']);
    $enable_login = $form['miniorange_saml_enable_login']['#value'];
    $character_encoding = $form['miniorange_saml_character_encoding']['#value'];
    $enable_URL_login = $form['miniorange_saml_enable_field']['miniorange_saml_enable_login_with_URL']['#value'];
    if(empty( $idp_name ) || empty( $issuer ) || empty( $login_url ) ){
        drupal_set_message(t('The <b>IDP Name</b>, <b>IdP Entity ID or Issuer</b> and <b>SAML Login URL</b> fields are mandatory.'), 'error');
        return;
    }

    if(!$enable_login)
    {
        $enable_URL_login = false;
    }

    $sp_issuer = $b_url;
    variable_set('miniorange_saml_idp_name', $idp_name);
    variable_set('miniorange_saml_sp_issuer', $sp_issuer);
    variable_set('miniorange_saml_idp_issuer', $issuer);
    variable_set('miniorange_nameid_format',$nameid_format);
    variable_set('miniorange_saml_idp_login_url', $login_url);
    variable_set('miniorange_saml_idp_x509_certificate', $x509_cert_value);
    variable_set('miniorange_saml_enable_login', $enable_login);
    variable_set('miniorange_saml_enable_login_with_URL',$enable_URL_login);
    variable_set('miniorange_saml_character_encoding', $character_encoding );

    drupal_set_message(t('Identity Provider Configuration successfully saved'));
}

function miniorange_saml_upload_file($form, &$form_state) {
    $file_name = $_FILES['files']['tmp_name']['metadata_file'];
    if (!empty($file_name)) {
        $file = @file_get_contents( $file_name );
        Utilities::upload_metadata( $file );
    }else{
        drupal_set_message(t('Please provide a valid metadata file.'),'error');
        return;
    }

}

function miniorange_saml_fetch_metadata($form, &$form_state) {
    $url=filter_var($form['metadata_URL']['#value'],FILTER_SANITIZE_URL);
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );
    if (!empty($url)) {
        $file = @file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );
        Utilities::upload_metadata( $file );
    }else{
        drupal_set_message(t('Please provide a valid metadata url.'),'error');
        return;
    }
}
