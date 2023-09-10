<?php
/**
 * @file
 * Contains Login Settings for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
include "mo_saml_visualTour.php";
 function miniorange_saml_login_options($form, &$form_state)
 {
     drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));


  $form['markup_1'] = array(
    '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout mo_saml_container">
                   <b><span style="font-size: 17px;">SIGN IN SETTINGS</span></b>
                    <a class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_restart_button" id="mo_saml_restart_tour_button">Take a Tour</a>
                    <br><br><hr><br/>
                    <div class="mo_saml_highlight_background_note"> <strong>Note:</strong> Available in
                    <a href="' . Utilities::getLicensePageURL() . '">Standard, Premium and Enterprise</a> versions of the module</div><br>',
  );

  $form['miniorange_saml_disable_autocreate_users'] = array(
         '#type' => 'checkbox',
         '#title' => t('Check this option if you want to disable <b>auto creation</b> of users if user does not exist.'),
         '#description'=>t('<b>Note: </b>If you enable this feature new user wont be created, only existing users can login using SSO.<br><br>'),
         '#disabled' => TRUE,
         '#prefix' => '<div id="mosaml_redirect_chckbx">',
     );

  $form['miniorange_saml_force_auth'] = array(
    '#type' => 'checkbox',
    '#title' => t('Protect website against anonymous access.'),
	'#disabled' => TRUE,
    '#attributes' => array('id'=>'mosaml_chkbx_prot'),
    '#description' => t('<b>Note: </b>Users will be redirected to your IdP for login in case user is not logged in and tries to access <strong>website</strong> [Any page].<br><br>'),
  );


  $form['miniorange_saml_auto_redirect'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check this option if you want to <b>auto redirect the user to IdP.</b>'),
	'#disabled' => TRUE,
      '#attributes' => array('id'=>'mosaml_vt_auto_redirect'),
      '#description' => t('<b>Note: </b>Users will be redirected to your IdP for login in case user is not logged in and tries to access <strong>login page</strong>.<br><br>'),
  );

  $form['miniorange_saml_enable_backdoor'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check this option if you want to enable <b>backdoor login.</b>'),
	'#disabled' => TRUE,
    '#attributes' => array('id'=>'mosaml-chkbx-enable-backdoor'),
    '#description' => t('<b>Note: </b>Checking this option <b>creates a backdoor login to your Website using Drupal credentials</b>
           incase you<br> get locked out of your IdP.<br> <strong>Note down this URL:</strong> <code>We provide bcakdoor URL in <b><a href="' .Utilities::getLicensePageURL().'">Standard, Premium and Enterprise</a></b> versions of the module.</code><br><br>'),
    '#suffix' => '</div>',
  );

     $form['miniorange_saml_default_relaystate'] = array(
         '#type' => 'textfield',
         '#title' => t('Default Redirect URL after login'),
         '#attributes' => array('id'=>'mosaml_def_redirect','placeholder' => 'Enter Default Redirect URL'),
         '#disabled' => TRUE,
         '#prefix' => '<div id="vs_default_relaystate">',
         '#suffix' => '</div>',
     );

     $form['miniorange_saml_default_logout'] = array(
         '#type' => 'textfield',
         '#title' => t('Default Redirect URL after logout'),
         '#attributes' => array('id'=>'mosaml_def_redirect_logout','placeholder' => 'Enter Default Redirect URL'),
         '#disabled' => TRUE,
         '#prefix' => '<div id="vs_default_relaystate_logout">',
         '#suffix' => '</div></br>',
     );


    $form['miniorange_saml_domain_restriction_checkbox'] = array(
     '#type' => 'checkbox',
     '#title' => t('Check this option if you want  <b>Domain Restriction</b><a href="'.Utilities::getLicensePageURL().'"><strong>&nbsp;&nbsp;[Enterprise]</strong></a>'),
     '#disabled' => TRUE,
     '#prefix' => '<div id="vs_domain_restriction">',
   );

   $form['miniorange_saml_set_of_radiobuttons'] = array(
     '#type' => 'fieldset',
     '#disabled'=>TRUE,
   );

   $form['miniorange_saml_set_of_radiobuttons']['miniorange_saml_allow_or_block_domains']=array(
     '#type'=>'radios',
     '#maxlength' => 5,
     '#options' => array(  'white' => 'I want to allow only some of the domains','black' => 'I want to block some of the domains'),
     '#disabled' => TRUE,


   );

   $form['miniorange_saml_set_of_radiobuttons']['miniorange_saml_domains'] = array(
     '#type' => 'textfield',
     '#title' => t('Enter list of domains'),
     '#attributes' => array('id'=>'mo_saml_domain_textfield','placeholder' => 'Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'),
     '#disabled' => TRUE,
     '#suffix' => '</div>',

   );
   $form['miniorange_saml_gateway_config_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#disabled' => TRUE,
         '#suffix' => '<br><br><br></div>',

     );

     Utilities::advertiseNetworkSecurity($form, $form_state);

     Utilities::AddSupportButton($form, $form_state);

  return $form;

 }
