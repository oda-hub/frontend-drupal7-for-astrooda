<?php
/**
 * @file
 * Contains Service Provider information for miniOrange SAML Login Module.
 */

/**
 * Showing Service Provider information.
 */

include "mo_saml_visualTour.php";
function miniorange_sp_information($form, &$form_state)
{
    drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));

  $pdo_exception_error = Utilities::pdo_exception_error();
  if($pdo_exception_error){
    $form['markup_reg_msg'] = array(
      '#markup' => '<div class="mo_saml_register_message" style="padding-left: 100px; padding-right: 100px;">User is trying to perform the SSO and unable to create an account. The possible cause for this error could be the Email ID is missing in the NameID attribute of IDP response. Also, Username and Email ID should be unique for different users. </div>',
    );
  }

    $form['header_top_style_1'] = array('#markup' => '<div class="mo_saml_table_layout_1">');

    $form['markup_top'] = array(
        '#markup' => '<div class="mo_saml_table_layout mo_saml_container"><b><span style="font-size: 18px;">Service Provider Metadata</span></b><a class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_restart_button" id="mo_saml_restart_tour_button">'
            . 'Take a Tour</a><br><br><hr>'
    );

    $b_url = Utilities::miniorange_get_baseURL();
    $issuer_id = Utilities::miniorange_get_issuer();

    $module_path = drupal_get_path('module', 'miniorange_saml');

    substr($b_url, -1) == '/' ? $acs_url = $b_url . '?q=samlassertion' : $acs_url = $b_url . '/?q=samlassertion';

    $form['miniorange_saml_base_url'] = array(
        '#type' => 'textfield',
        '#id' => 'mo_saml_base_url',
        '#title' => t('SP Base URL:'),
        '#default_value' => $b_url,
        '#attributes' => array('style' => 'width:81%'),
        '#prefix' => '<br><div class="mo_saml_highlight_background_note_1"><b>Note:
                        </b> If you have already shared the below URLs or Metadata with your IdP, do NOT change <strong>SP EntityID</strong>. It might break your existing login flow.
                        </div><br><div id="mo_saml_base_url">'
    );

    $form['miniorange_saml_entity_id'] = array(
        '#type' => 'textfield',
        '#prefix' => '</div><div id="mo_saml_issuer_id">',
        '#title' => t('SP Entity ID/Issuer:'),
        '#default_value' => $issuer_id,
        '#attributes' => array('style' => 'width:81%'),
    );

    $form['miniorange_saml_config_submit'] = array(
      '#prefix' => '</div>',
      '#type' => 'submit',
      '#value' => t('Save'),
      '#attributes' => array('class'=>array('mo_saml_save_mapping_config_button')),
      '#submit' => array('miniorange_saml_save_config'),
      '#suffix' => '<hr>'
    );

    $form['mo_saml_metadata_option'] = array(
        '#markup' => '<br><div style="color: #d9534f;"><b>Provide this module information to your Identity Provider team. You can choose any one of the below options:</b></div>',
    );

    $form['markup_1_idp_sp_1'] = array(
        '#markup' => '<br><div id="mo_saml_vt_metadata"><b>a) Provide this metadata URL to your Identity Provider:</b><br>',
    );

  $form['markup_idp_sp_2'] = array(
    '#markup' => '<div><div class="mo_saml_highlight_background_url_note" "><code id="mo_saml_idp_vt_metadata"><b>'
      . '<span id="idp_metadata_url"><a target="_blank" href="' .  $b_url . '/?q=mosp_metadata_download">' .  $b_url . '/?q=mosp_metadata' . '</a></span></b></code></div>
            <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" style="margin-left: 10px;" onclick="copyToClipboard(\'#idp_metadata_url\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy"><br></div></div>',
  );

  $form['mo_saml_download_btn_title'] = array(
     '#markup' => '<br><br><div id="miniorange_download_metadata"><b>b) Download the Module XML metadata and upload it on your Identity Provider : </b>
                        <span><a href="'.$b_url.'/?q=mosp_metadata&download=1" class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_btn" style="padding: 4px 10px;">Download XML Metadata</a></span></div>',
  );

    $form['header'] = array(
        '#markup' => '<br><br><div><b>c) Provide the following information to your Identity Provider. Copy it and keep it handy.</b></div><br>',
    );

    $form['mosaml_vtId']=array(
        '#markup'=>'<div id="mo_saml_vt_id">'
    );

    $form['mo_saml_attrs_list_idp'] = array(
        '#markup' => '<div class="table-responsive" style="font-family: sans-serif;font-size: 12px;" id="mo_idp_vt_conf_table">
                <table class="mo_saml_mo_guide_table mo_saml_mo_guide_table-striped mo_saml_mo_guide_table-bordered" style="border: 1px solid #ddd;max-width: 100%;border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th class="mo_saml_mo_guide_text-center mo_td_values"><b>ATTRIBUTE</b></th>
                            <th class="mo_saml_mo_guide_text-center mo_td_values"><b>VALUE</b></th>
                            <tbody style="font-size: 12px;color:gray;">
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">SP-Entity ID / Issuer</td>
                                    <td>
						               <span id="issuer_id">' . $issuer_id . '</span>
                                       <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-pull-right mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" onclick="copyToClipboard(\'#issuer_id\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy">
						            </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">ACS URL	</td>
                                    <td>
						               <span id="acs_url">' . $acs_url . '</span>
						               <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-pull-right mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" onclick="copyToClipboard(\'#acs_url\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy">
						            </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">Audience URI</td>
                                    <td>
						               <span id="base_url">' . $issuer_id . '</span>
						               <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-pull-right mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" onclick="copyToClipboard(\'#base_url\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy">
						            </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">Recipient URL</td>
                                    <td>
						               <span id="acs_url_1">' . $acs_url . '</span>
						               <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-pull-right mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" onclick="copyToClipboard(\'#acs_url_1\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy">
						            </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">Destination URL</td>
                                    <td>
						               <span id="dest_url">' . $acs_url . '</span>
						               <img class ="mo_saml_fa mo_saml_fa-fw mo_saml_fa-pull-right mo_saml_fa-lg mo_saml_fa-copy mo_saml_mo_copy" onclick="copyToClipboard(\'#dest_url\');" src="'. $b_url.'/'.$module_path . '/includes/images/copy-regular.svg" alt="copy">
						            </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">Single Logout URL</td>
                                    <td>
                                       Available in </b><a href="' .Utilities::getLicensePageURL().'">Premium and Enterprise</a> version.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">Certificate</td>
                                    <td>
                                       Available in </b><a href="' .Utilities::getLicensePageURL().'">Standard,Premium and Enterprise</a> version.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;padding: 15px;">NameID Format</td>
                                    <td>
						               <span id="name_id">urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</span>
						            </td>
                                </tr>


                            </tbody>
                        </tr>
                    </thead>
                    </table>
           <script>
              function copyToClipboard(element) {
                jQuery(".mo_saml_selected-text").removeClass("mo_saml_selected-text");
                var temp = jQuery("<input>");
                jQuery("body").append(temp);
                jQuery(element).addClass("mo_saml_selected-text");
                temp.val(jQuery(element).text()).select();
                document.execCommand("copy");
                temp.remove();
            }
              jQuery(window).click(function(e) {
                  if( e.target.className == undefined || e.target.className.indexOf("mo_saml_mo_copy") == -1)
                   jQuery(".selected-text").removeClass("mo_saml_selected-text");
              });
          </script>'
    );

    $form['markup_support_close1'] = array(
        '#markup' => '</div><br></div></div>',
    );

    Utilities::spConfigGuide($form, $form_state);

    Utilities::AddSupportButton($form, $form_state);

    return $form;
}

function miniorange_saml_save_config($form, &$form_state) {
    $b_url = $form['miniorange_saml_base_url']['#value'];
    $issuer_id = $form['miniorange_saml_entity_id'] ['#value'];
    variable_set('miniorange_saml_base_url', $b_url);
    variable_set('miniorange_saml_entity_id', $issuer_id);
}


