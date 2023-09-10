
<?php

/**
 * @file
 * Contains support form for miniOrange SAML Login Module.
 */

include "mo_saml_visualTour.php";
/**
 * Showing Support form info.
 */
function miniorange_saml_export_config($form, &$form_state)
{
  drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));

  $b_url = Utilities::miniorange_get_baseURL();

  $form['markup_top_head'] = array(
    '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout mo_saml_container"><b>
                        <span style="font-size: 17px;">IMPORT/EXPORT CONFIGURATIONS</span></b>
                        <a class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_restart_button" id="mo_saml_restart_tour_button">Take a Tour</a>
                        <br><br><hr><br/>'
  );

  $form['markup_1s'] = array(
    '#markup' => '<div class="mo_saml_highlight_background_note" style="width: auto" ><p><b>NOTE: </b>This tab will help you to transfer your module configurations when you change your Drupal instance.
                        <br>Example: When you switch from test environment to production.<br>Follow these 3 simple steps to do that:<br>
                        <br><strong>1.</strong> Download module configuration file by clicking on the Download Configuration button given below.
                        <br><strong>2.</strong> Install the module on new Drupal instance.<br><strong>3.</strong> Upload the configuration file in Import module Configurations section.<br>
                        <br><b>And just like that, all your module configurations will be transferred!</b></p></div>',
  );



  $form['mo_markup_top'] = array(
    '#markup' => '<div id="mosaml_vt_import"><br><br><div class="mo_saml_tadble_layout mo_saml_container_3" style="text-align: center;float: left;margin-right: 20px;border: solid 1px #00000024;padding-bottom: 55px;">
                    <div id="mosaml_vt_impexp"><b><span style="font-size: 17px;">EXPORT CONFIGURATION</span></b><br><br><hr><br/><br>'
  );

  if(Utilities::miniorange_saml_is_sp_configured()) {
    $form['miniorange_saml_idp_export'] = array(
      '#type' => 'submit',
      '#value' => t('Download Module Configuration'),
      '#prefix' => '<td>',
      '#suffix' => '</td>',
      '#submit' => array('miniorange_import_export'),
      '#attributes' => array('class'=>array('mo_saml_save_mapping_config_button')),
    );
  }else {
    $form['miniorange_saml_idp_export'] = array(
      '#markup' => '<div class="mo_saml_register_message">
	                            Please <a href="' . $b_url . '/admin/config/people/miniorange_saml/sp_setup">configure the module</a> first to export the configurations.
                          </div>',
    );
  }

  $form['miniorange_idp_guide_link_cont'] = array(
    '#markup' => '</div></div><div class="mo_saml_tadble_layout mo_saml_container_3" style="float: left;text-align:center;padding-bottom: 20px;border: solid 1px #00000024;;">
                      <b><span style="font-size: 17px;">IMPORT CONFIGURATION <b><a href="' . Utilities::getLicensePageURL() . '"> [Standard]</a></b></span></b><br><br><hr><br>  ',
  );

  $form['import_Config_file'] = array(
    '#type' => 'file',
    '#disabled' => TRUE,
    '#attributes' => array('id'=>'mosaml_vt_import','style'=>'width: 175px;'),
  );

  $form['miniorange_saml_idp_import'] = array(
    '#type' => 'submit',
    '#value' => t('Upload'),
    '#submit' => array('miniorange_import_Config'),
    '#disabled' => TRUE,
    '#suffix' => '</div></div></div>',
  );

  Utilities::Two_FA_Advertisement($form, $form_state);

  Utilities::AddSupportButton($form, $form_state);

  return $form;
}

function miniorange_import_export() {
  $tab_class_name = array(
    "Service_Provider_Metadata" => 'mo_options_enum_identity_provider',
    "Service_Provider_Setup"    => 'mo_options_enum_service_provider',
  );
  $configuration_array = array();
  foreach ( $tab_class_name as $key => $value ) {
    $configuration_array[ $key ] = mo_get_configuration_array( $value );
  }
  $configuration_array["Version_dependencies"] = mo_get_version_informations();
  header( "Content-Disposition: attachment; filename=miniorange-saml-config.json" );
  echo( json_encode( $configuration_array, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) );
  exit;
}

function mo_get_configuration_array( $class_name ) {
  $class_object = Utilities::getVariableNames($class_name);
  $mo_array = array();
  foreach ( $class_object as $key => $value ) {
    $mo_option_exists=variable_get($value);
    if($mo_option_exists){
      $mo_array[ $key ] = $mo_option_exists;
    }
  }
  return $mo_array;
}

function mo_get_version_informations(){
  $array_version = array();
  $array_version["PHP_version"] = phpversion();
  $array_version["Drupal_version"] = VERSION;
  $array_version["OPEN_SSL"] = mo_saml_is_openssl_installed();
  $array_version["CURL"] = mo_saml_is_curl_installed();
  $array_version["ICONV"] = mo_saml_is_iconv_installed();
  $array_version["DOM"] = mo_saml_is_dom_installed();
  return $array_version;
}

function mo_saml_is_openssl_installed() {
  if ( in_array( 'openssl', get_loaded_extensions() ) ) {
    return 1;
  } else {
    return 0;
  }
}

function mo_saml_is_curl_installed() {
  if ( in_array( 'curl', get_loaded_extensions() ) ) {
    return 1;
  } else {
    return 0;
  }
}

function mo_saml_is_iconv_installed(){
  if ( in_array( 'iconv', get_loaded_extensions() ) ) {
    return 1;
  } else {
    return 0;
  }
}

function mo_saml_is_dom_installed(){
  if ( in_array( 'dom', get_loaded_extensions() ) ) {
    return 1;
  } else {
    return 0;
  }
}


