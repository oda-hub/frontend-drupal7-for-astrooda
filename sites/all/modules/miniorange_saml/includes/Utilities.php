<?php
/**
 * This file is part of miniOrange SAML plugin.
 *
 * miniOrange SAML plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * miniOrange SAML plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with miniOrange SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

class Utilities
{
  public static function faq(&$form, &$form_state){

    $form['miniorange_faq'] = array(
      '#markup' => '<div ><b></b><br>
                          <a class="mo_saml_btn mo_saml_btn-primary-faq btn-large mo_saml_btn_faq_buttons" style="float: inherit;color: #48a0dc;border: 2px solid #48a0dc;" href="https://faq.miniorange.com/kb/drupal/saml-drupal/" target="_blank">FAQs</a>
                          <b></b><a class="mo_saml_btn mo_saml_btn-primary-faq btn-large mo_saml_btn_faq_buttons" style="float: inherit;color: #48a0dc;border: 2px solid #48a0dc; margin-left:5px;" href="https://forum.miniorange.com/" target="_blank">Ask questions on forum</a></div>',
    );
  }

  public static function spConfigGuide(&$form, &$form_state){

    $form['miniorange_idp_guide_link1'] = array(
      '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2">
                        <div style="font-size: 15px; text-align: justify;">Documentation of how to configure
                            Drupal SAML Service Provider with any Identity Provider.</div></br>',
    );
    $form['miniorange_saml_guide_table_list'] = array(
      '#markup' => '<div class="table-responsive mo_saml_mo_guide_text-center" style="font-family: sans-serif; font-size: 15px;">
                <table class="mo_saml_mo_guide_table mo_saml_mo_guide_table-striped mo_saml_mo_guide_table-bordered" style="border: 1px solid #ddd;max-width: 100%;border-collapse: collapse;">
                    <thead>
                    </thead>
                    <tbody style="color:gray;">
                    <tr><td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-azure-ad-idp" target="_blank">Azure AD</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-into-drupal-using-adfs-as-idp" target="_blank">ADFS</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-okta-idp" target="_blank">Okta</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-google-apps-idp" target="_blank">Google Apps</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-salesforce-idp" target="_blank">Salesforce</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-miniorange-idp" target="_blank">miniOrange</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-drupal-single-sign-on-using-pingone-as-identity-provider" target="_blank">PingOne</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-onelogin-idp" target="_blank">Onelogin</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-bitium-idp" target="_blank">Bitium</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-sso-using-centrify-idp" target="_blank">Centrify</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-to-configure-oracle-access-manager-as-idp-and-drupal-as-sp" target="_blank">Oracle</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-to-configure-jboss-keycloak-with-drupal" target="_blank">Jboss Keycloak</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-pingfederate-as-idp-with-drupal" target="_blank">Ping Federate</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-openam-as-idp-with-drupal" target="_blank">OpenAM</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-single-sign-on-sso-using-authanvil-as-idp" target="_blank">Auth Anvil</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-auth0-as-idp-with-drupal" target="_blank">Auth0</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/guide-for-drupal-single-sign-on-sso-using-rsa-securid-as-idp" target="_blank">RSA SecureID</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/configure-saml-sso-between-two-drupal-sites" target="_blank">Drupal as IDP</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/drupal-saml-single-sign-on-sso-with-joomla" target="_blank">Joomla</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-for-drupal-using-simplesaml-as-idp" target="_blank">SimpleSAML</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-into-drupal-using-ca-identity-as-idp" target="_blank">CA Identity</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-into-drupal-using-gluu-server-as-idp" target="_blank">Gluu Server</a></strong></td>
                    </tr>

                    <tr>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-into-drupal-using-jumpcloud-as-idp" target="_blank">JumpCloud</a></strong></td>
                        <td><strong><a class="mo_saml_mo_guide_text-color" href="https://plugins.miniorange.com/saml-single-sign-on-sso-into-drupal-using-absorb-lms-as-idp" target="_blank">Absorb LMS</a></strong></td>
                    </tr>
                    </tbody>
                    <strong>Identity Provider Setup Guides</strong>
                    <p> </p>
                </table>
                <a class="mo_saml_btn mo_saml_btn-primary-faq btn-large mo_saml_btn_faq_buttons" style="float: inherit;color:#fe7e00; border: 2px solid #fe7e00; background-color:#e0e0e0" href="https://plugins.miniorange.com/configure-drupal-saml-single-sign-on" target="_blank"><strong>More IDP</strong></a>',

    );

    self::faq($form, $form_state);

    $form['miniorange_end_of_guide'] = array(
      '#markup' => '</div>',
    );
  }

  public static function AddSupportButton(&$form, &$form_state)
  {
    $form['miniorange_saml_idp_support_side_button'] = array(
      '#type' => 'button',
      '#value' => t('Support'),
      '#attributes' => array('style' => 'font-size: 15px;cursor: pointer;text-align: center;width: 150px;height: 35px;
                background: rgba(43, 141, 65, 0.93);color: #ffffff;border-radius: 3px;transform: rotate(90deg);text-shadow: none;
                position: relative;margin-left: -92px;top: 107px;'),
      '#prefix' => '<div id="mosaml-feedback-form" class="mo_saml_table_layout_support_btn">'
    );

    $form['markup_idp_attr_header_top_support'] = array(
      '#markup' => '<div id="Support_Section" class="mo_saml_table_layout_support_1">',
    );


    $form['markup_support_1'] = array(
      '#markup' => '<h3><b>Feature Request/Contact Us:</b></h3>
                          <div>Need any help? We can help you with configuring your Service Provider.
                          Just send us a query and we will get back to you soon.<br /></div>',
    );

    $form['miniorange_saml_email_address'] = array(
      '#type' => 'textfield',
      '#attributes' => array('style' => 'width:100%','placeholder' => 'Enter your Email'),
      '#default_value' => variable_get('miniorange_saml_customer_admin_email', ''),
    );

    $form['miniorange_saml_phone_number'] = array(
      '#type' => 'textfield',
      '#attributes' => array('style' => 'width:100%','pattern' => '[\+][0-9]{1,4}\s?[0-9]{7,12}', 'placeholder' => 'Enter your phone number with country code (+1)'),
      '#default_value' => variable_get('miniorange_saml_customer_admin_phone', ''),
    );

    $form['miniorange_saml_support_query'] = array(
      '#type' => 'textarea',
      '#cols' => '10',
      '#rows' => '5',
      '#attributes' => array('style' => 'width:100%','placeholder' => 'Write your query here'),
    );

    $form['miniorange_saml_support_submit_click'] = array(
      '#type' => 'submit',
      '#value' => t('Submit Query'),
      '#submit' => array('Utilities::send_support_query'),
      '#attributes' => array('style' => 'background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;display:block;margin-left:auto;margin-right:auto;'),
    );

    $form['miniorange_saml_support_note'] = array(
      '#markup' => '<div>If you want custom features in the plugin, just drop an email to
                                    <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></div>
                          </div></div><div hidden id="mosaml-feedback-overlay"></div>'
    );
  }

  public static function AddrfdButton(&$form, &$form_state)
  {
    $form['markup_idp_attr_header_top_support_btn'] = array(
      '#markup' => '<div id="mosaml-feedback-form" class="mo_saml_table_layout_support_btn">',
    );

    $form['miniorange_saml_idp_support_side_button'] = array(
      '#type' => 'button',
      '#value' => t('Request for Demo'),
      '#attributes' => array('style' => 'font-size: 15px;cursor: pointer;width: 170px;height: 35px;
                background: rgba(43, 141, 65, 0.93);color: #ffffff;border-radius: 3px;transform: rotate(90deg);text-shadow: none;
                position: relative;margin-left: -102px;top: 115px;'),
    );

    $form['markup_idp_attr_header_top_support'] = array(
      '#markup' => '<div id="Support_Section" class="mo_saml_table_layout_support_1">',
    );


    $form['markup_2'] = array(
      '#markup' => '<b>Want to test  the Premium module before purchasing?</b> <br>Just send us a request, We will setup a demo site for you on our cloud and provide you with the administrator credentials.
                So that you can test all the premium features as per your requirement.
        <br>',
    );

    $form['customer_email'] = array(
      '#type' => 'textfield',
      '#default_value'=>variable_get('miniorange_saml_idp_customer_admin_email',''),
      '#attributes' => array('style' => 'width:100%','placeholder' => 'Enter your Email'),
    );

    $form['demo_plan'] = array(
      '#type' => 'select',
      '#title' => t('Demo Plan'),
      '#attributes' => array('style' => 'width:100%;'),
      '#options' => [
        'Drupal 7 SAML SP Standard Module' => t('Drupal 7 SAML SP Standard Module'),
        'Drupal 7 SAML SP Premium Module' => t('Drupal 7 SAML SP Premium Module'),
        'Drupal 7 SAML SP Enterprise Module' => t('Drupal 7 SAML SP Enterprise Module'),
        'Not Sure' => t('Not Sure'),
      ],
    );

    $form['description_doubt'] = array(
      '#type' => 'textarea',
      '#clos' => '10',
      '#rows' => '5',
      '#attributes' => array('style' => 'width:100%','placeholder' => 'Write your query here'),
    );
    $form['markup_div'] = array(
      '#markup' => '<div>'
    );

    $form['miniorange_oauth_support_submit_click'] = array(
      '#type' => 'submit',
      '#value' => t('Submit Query'),
      '#submit' => array('send_rfd_query'),
      '#limit_validation_errors' => array(),
      '#attributes' => array('style' => 'background: #337ab7;color: #ffffff;text-shadow: 0 -1px 1px #337ab7, 1px 0 1px #337ab7, 0 1px 1px #337ab7, -1px 0 1px #337ab7;box-shadow: 0 1px 0 #337ab7;border-color: #337ab7 #337ab7 #337ab7;display:block;float:left'),
    );

    $form['markup_div_end'] = array(
      '#markup' => '</div>'
    );

    $form['miniorange_oauth_support_note'] = array(
      '#markup' => '<br><br><br><div>If you want custom features in the module, just drop an email to <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></div>'
    );

    $form['miniorange_oauth_support_div_cust'] = array(
      '#markup' => '</div></div><div hidden id="mosaml-feedback-overlay">'
    );
  }

  public static function send_demo_query($email, $query, $description)
  {
    if(empty($email)||empty($description)){
      if(empty($email)) {
        drupal_set_message(t('The <b>Email Address</b> field is required.'), 'error');
      }
      if(empty($description)) {
        drupal_set_message(t('The <b>Description</b> field is required.'), 'error');
      }
      return;
    }
    if (!valid_email_address($email)) {
      drupal_set_message(t('The email address <b><u>' . $email . '</u></b> is not valid.'), 'error');
      return;
    }

    $phone = variable_get('miniorange_saml_customer_admin_phone');
    $support = new MiniOrangeSamlSupport($email, $phone, $query,'demo');
    $support_response = $support->sendSupportQuery();
    if($support_response) {
      drupal_set_message(t('Your demo request has been sent successfully. We will get back to you soon.'));
    }else {
      drupal_set_message(t('Error sending support query. Please try again.'), 'error');
    }
  }

  public static function Two_FA_Advertisement(&$form, $form_state){
    global $base_url;

    $form['markup_idp_attr_hea555der_top_support'] = array(
      '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2">',
    );

    $form['miniorangerr_otp_email_address'] = array(
      '#markup' => '<div><h3 class="mo_otp_h_3" >Checkout our Drupal <br>Two-Factor Authentication(2FA) module<br></h3></div>
                        <div class="mo_otp_adv_tfa"><img src="'.$base_url . '/' . drupal_get_path("module", "miniorange_saml") . '/includes/images/miniorange_i.png" alt="miniOrange icon" height="60px" width="60px" class="mo_otp_img_adv"><h3 class="mo_otp_txt_h3">Two-Factor Authentication (2FA)</h3></div>',
    );

    $form['minioranqege_otp_phone_number'] = array(
      '#markup' => '<div class="mo_otp_paragraph"><p>Two Factor Authentication (TFA) for your Drupal site is highly secure and easy to setup. Adds a second layer of security to your Drupal accounts. It protects your site from hacks and unauthorized login attempts.</p></div>',
    );

    $form['miniorange_otp_2fa_button'] = array(
      '#markup' => '<div style="margin-left: 7%;"><a href="https://www.drupal.org/project/miniorange_2fa" target="_blank"
                       class="mo_saml_btn mo_saml_btn-primary" style="padding: 4px 10px;">Download Plugin</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://plugins.miniorange.com/drupal-two-factor-authentication-2fa" class="mo_saml_btn mo_saml_btn-success"
                            style="padding: 4px 10px;" target="_blank">Know More</a></div></div></div>'
    );
  }

  public static function advertiseNetworkSecurity(&$form,&$form_state,$module_type = 'Network Security'){
    global $base_url;
    $form['miniorange_idp_guide_link3'] = array(
      '#markup' => '<div class="mo_saml_table_layout mo_saml_container_2">
                        ',
    );
    $mo_image = 'security.jpg';
    $mo_module = 'Web Security module';
    $mo_description = 'Building a website is a time-consuming process that requires tremendous efforts. For smooth
                    functioning and protection from any sort of web attack appropriate security is essential and we
                     ensure to provide the best website security solutions available in the market.
                    We provide you enterprise-level security, protecting your Drupal site from hackers and malware.';
    $mo_knowMoreButton = 'https://plugins.miniorange.com/drupal-web-security-pro';
    $mo_downloadModule = 'https://www.drupal.org/project/security_login_secure';
    if ($module_type == 'SCIM'){
      $mo_image = 'user-sync.png';
      $mo_module = 'User Provisioning (SCIM)';
      $mo_description = 'miniOrange provides a ready to use solution for Drupal User Provisioning using SCIM (System for Cross-domain Identity Management) standard.
            This solution ensures that you can sync add, update, delete, and deactivate user operations with Drupal user list using the SCIM User Provisioner module.';
      $mo_downloadModule = 'https://www.drupal.org/project/user_provisioning';
      $mo_knowMoreButton = 'https://plugins.miniorange.com/drupal-scim-user-provisioning';
    }


    $form['mo_idp_net_adv']=array(
      '#markup'=>'<form name="f1">
        <table id="idp_support" class="idp-table" style="border: none;">
        <h4 style="text-align: center;">Looking for a Drupal ' . $mo_module . ' ?</h4>
            <tr>
                <th class="" style="border: none; padding-bottom: 4%; background-color: white; text-align: center;"><img
                            src="'.$base_url . '/' . drupal_get_path("module", "miniorange_saml") . '/includes/images/'. $mo_image .'"
                            alt="miniOrange icon" height=150px width=44%>
		<br>
                        <img src="'.$base_url . '/' . drupal_get_path("module", "miniorange_saml") . '/includes/images/miniorange_i.png"
                             alt="miniOrange icon" height=50px width=50px style="float: left; margin-left: 14px; margin-right: -14px;"><h3 style="margin-top: 16px;">&nbsp;&nbsp;&nbsp;Drupal '. $mo_module .'</h3>
                </th>
            </tr>

            <tr style="border-right: hidden;">
                <td style="text-align: center">
                    '. $mo_description .'
                </td>
            </tr>
            <tr style="border-right: hidden;">
                <td style="padding-left: 11%"><br>
                    <a href="'. $mo_downloadModule .'" target="_blank"
                       class="mo_saml_btn mo_saml_btn-primary" style="padding: 4px 10px;">Download Plugin</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
                            href="' . $mo_knowMoreButton .'" class="mo_saml_btn mo_saml_btn-success"
                            style="padding: 4px 10px;" target="_blank">Know More</a>
                </td>
            </tr>
        </table>
    </form>'
    );
    return $form;
  }

  public static function send_support_query(&$form, $form_state){
    $email = trim($form['miniorange_saml_email_address']['#value']);
    $phone = trim($form['miniorange_saml_phone_number']['#value']);
    $query = trim($form['miniorange_saml_support_query']['#value']);
    Utilities::send_query($email, $phone, $query);
  }

  public static function send_query($email, $phone, $query)
  {
    if( empty( $email ) || empty( $query ) ){
      drupal_set_message(t('The <b>Email</b> and<b> Query</b> fields are mandatory.'), 'error');
      return;
    }
    if ( !valid_email_address( $email ) ) {
      drupal_set_message(t('The email address <b><u>' . $email . '</u></b> is not valid.'), 'error');
      return;
    }
    $support = new MiniOrangeSamlSupport( $email, $phone, $query );
    $support_response = $support->sendSupportQuery();
    if($support_response) {
      drupal_set_message(t('Thank you for getting in touch. We will get back to you shortly.'));
    }
    else {
      drupal_set_message(t('Error sending support query'), 'error');
    }
  }

  public static function customer_setup_submit($username, $phone, $password, $called_from_popup=false, $payment_plan=NULL){
    $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
    $check_customer_response = json_decode($customer_config->checkCustomer());

    if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {
      // Create customer.
      // Store email and phone.
      variable_set('miniorange_saml_customer_admin_email', $username);
      variable_set('miniorange_saml_customer_admin_phone', $phone);
      variable_set('miniorange_saml_customer_admin_password', $password);

      $send_otp_response = json_decode($customer_config->sendOtp());

      if ($send_otp_response->status == 'SUCCESS') {
        // Store txID.
        variable_set('miniorange_saml_tx_id', $send_otp_response->txId);
        variable_set('miniorange_saml_status', 'VALIDATE_OTP');
        if ($called_from_popup == true) {
          miniorange_otp(false,false,false);
        }else{
          drupal_set_message(t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
        }
      }else{
        if ($called_from_popup == true) {
          register_data(true);
        }else{
          drupal_set_message(t('An error has been occured. Please try after some time or contact us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a>.'),'error');
        }
      }
    }
    elseif ($check_customer_response->status == 'CURL_ERROR') {

      if ($called_from_popup == true) {
        register_data(true);
      }else{
        drupal_set_message(t('cURL is not enabled. Please enable cURL'), 'error');
      }
    }
    else {
      // Customer exists. Retrieve keys.
      $customer_keys_response = json_decode($customer_config->getCustomerKeys());
      if (json_last_error() == JSON_ERROR_NONE) {
        variable_set('miniorange_saml_customer_id', $customer_keys_response->id);
        variable_set('miniorange_saml_customer_admin_token', $customer_keys_response->token);
        variable_set('miniorange_saml_customer_admin_email', $username);
        variable_set('miniorange_saml_customer_admin_phone', $phone);
        variable_set('miniorange_saml_customer_api_key', $customer_keys_response->apiKey);
        variable_set('miniorange_saml_status', 'PLUGIN_CONFIGURATION');

        if ($called_from_popup == true) {
          $payment_page_url = variable_get('redirect_plan_after_registration_' . $payment_plan,'');
          $payment_page_url = str_replace('none', $username, $payment_page_url);
          miniorange_redirect_successful($payment_page_url);
        }else{
          drupal_set_message(t('Successfully retrieved your account.'));
          self::redirect_to_licensing();
        }

      }else if($check_customer_response->status=='TRANSACTION_LIMIT_EXCEEDED') {

        if ($called_from_popup == true) {
          register_data(true);
        }else{
          drupal_set_message(t('An error has been occured. Please try after some time or contact us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a>..'), 'error');
        }
      }
      else {
        if ($called_from_popup == true) {
          register_data(false, true);
        }else{
          drupal_set_message(t('Invalid credentials'), 'error');
        }
      }
    }
  }

  public static function validate_otp_submit($otp_token, $called_from_popup=false, $payment_plan=NULL){
    $username = variable_get('miniorange_saml_customer_admin_email', NULL);
    $phone = variable_get('miniorange_saml_customer_admin_phone', NULL);
    $tx_id = variable_get('miniorange_saml_tx_id', NULL);
    $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, $otp_token);
    // Validate OTP.
    $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));
    if ($validate_otp_response->status == 'SUCCESS') {
      // OTP Validated. Show Configuration page.
      variable_del('miniorange_saml_tx_id');

      // OTP Validated. Create customer.
      $password = variable_get('miniorange_saml_customer_admin_password', '');
      $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
      $create_customer_response = json_decode($customer_config->createCustomer());
      if ($create_customer_response->status == 'SUCCESS') {
        // Customer created.
        variable_set('miniorange_saml_status', 'PLUGIN_CONFIGURATION');
        variable_set('miniorange_saml_customer_admin_email', $username);
        variable_set('miniorange_saml_customer_admin_phone', $phone);
        variable_set('miniorange_saml_customer_admin_token', $create_customer_response->token);
        variable_set('miniorange_saml_customer_id', $create_customer_response->id);
        variable_set('miniorange_saml_customer_api_key', $create_customer_response->apiKey);

        if ($called_from_popup == true) {
          $payment_page_url = variable_get('redirect_plan_after_registration_' . $payment_plan,'');
          $payment_page_url = str_replace('none', $username, $payment_page_url);
          miniorange_redirect_successful($payment_page_url);
        }else{
          drupal_set_message(t('Account created successfully. Now you can upgrade to the standard, premium and enterprise versions of the modules.'));
          self::redirect_to_licensing();
        }
      }
      else if(trim($create_customer_response->status) == 'INVALID_EMAIL_QUICK_EMAIL') {
        if ( $called_from_popup == true ) {
          variable_set('miniorange_saml_status', 'CUSTOMER_SETUP');
          register_data(false, false, true);
        } else {
          drupal_set_message(t('There was an error creating an account for you. You may have entered an invalid Email-Id
                            <strong>(We discourage the use of disposable emails) </strong>
                            <br>Please try again with a valid email.'), 'error');
        }

      }else {
        if ($called_from_popup == true) {
          self::redirect_to_licensing();
        }else{
          drupal_set_message(t('Error creating an account.'), 'error');
          return;
        }
      }
    } else {
      if ($called_from_popup == true) {
        miniorange_otp(true,false,false);
      }else{
        drupal_set_message(t('You have entered the incorrect OTP. Please try again.'), 'error');
      }
    }
  }

  public static function saml_resend_otp($called_from_popup=false){
    variable_del('miniorange_saml_tx_id');
    $username = variable_get('miniorange_saml_customer_admin_email', NULL);
    $phone = variable_get('miniorange_saml_customer_admin_phone', NULL);
    $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, NULL);
    $send_otp_response = json_decode($customer_config->sendOtp());
    if ($send_otp_response->status == 'SUCCESS') {
      // Store txID.
      variable_set('miniorange_saml_tx_id', $send_otp_response->txId);
      variable_set('miniorange_saml_status', 'VALIDATE_OTP');

      if ($called_from_popup == true) {
        miniorange_otp(false,true,false);
      }else{
        drupal_set_message(t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
      }
    }else{
      if ($called_from_popup == true) {
        miniorange_otp(false,false,true);
      }else{
        drupal_set_message(t('An error has been occured. Please try after some time or contact us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a>.'),'error');
      }
    }
  }

  public static function redirect_to_licensing(){
    $redirect = self::getLicensePageURL();
    drupal_goto($redirect);
  }

  public static function pdo_exception_error(){
    $pdo_except = variable_get('miniorange_saml_pdo_exception');
    if($pdo_except){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  public static function miniorange_saml_is_sp_configured() {
    $saml_login_url  = variable_get( 'miniorange_saml_idp_login_url' );
    $saml_idp_issuer = variable_get( 'miniorange_saml_idp_issuer' );
    $saml_x509_certificate = variable_get( 'miniorange_saml_idp_x509_certificate' );

    if ( ! empty( $saml_login_url ) && ! empty( $saml_x509_certificate )  && ! empty( $saml_idp_issuer ) ) {
      return 1;
    } else {
      return 0;
    }
  }

  public static function getLicensePageURL(){
    $b_url = Utilities::miniorange_get_baseURL();
    return $b_url.'/admin/config/people/miniorange_saml/licensing';
  }

  public static function isCustomerRegistered(){
    if (variable_get('miniorange_saml_customer_admin_email', NULL) == NULL||
      variable_get('miniorange_saml_customer_id', NULL) == NULL
      || variable_get('miniorange_saml_customer_admin_token', NULL) == NULL ||
      variable_get('miniorange_saml_customer_api_key', NULL) == NULL)
    {
      return TRUE;
    }else{
      return FALSE;
    }
  }

  public static function getVariableNames($class_name) {
    if($class_name == "mo_options_enum_identity_provider") {
      $class_object = array (
        'Broker_service' => 'mo_saml_enable_cloud_broker',
        'SP_Base_Url'    => 'miniorange_saml_base_url',
        'SP_Entity_ID'   => 'miniorange_saml_entity_id',
      );
    }
    else if($class_name == "mo_options_enum_service_provider") {
      $class_object = array(
        'Identity_name'          => 'miniorange_saml_idp_name',
        'Login_URL'              => 'miniorange_saml_idp_login_url',
        'Issuer'                 => 'miniorange_saml_idp_issuer',
        'Encrypted_Certificate'  => 'miniorange_saml_idp_certificate_encrypted',
        'Name_ID_format'         => 'miniorange_nameid_format',
        'X509_certificate'       => 'miniorange_saml_idp_x509_certificate',
        'Enable_login_with_SAML' => 'miniorange_saml_enable_login',
      );
    }
    return $class_object;
  }

  public static function miniorange_get_baseURL(){
    global $base_url;
    $url = variable_get('miniorange_saml_base_url','');
    $b_url = isset($url) && !empty($url)? $url:$base_url;
    return $b_url;
  }
  public static function miniorange_get_issuer(){
    $issuer = variable_get('miniorange_saml_entity_id','');
    $b_url = Utilities::miniorange_get_baseURL();
    $issuer_id = isset($issuer) && !empty($issuer)? $issuer:$b_url;
    return $issuer_id;
  }

  public static function upload_metadata($file)
  {
    $b_url = Utilities::miniorange_get_baseURL();
    require_once drupal_get_path('module', 'miniorange_saml') . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'MetadataReader.php';
    $document = new DOMDocument();
    if (empty($file)) {
      drupal_set_message(t('Please provide a valid metadata url.'),'error');
      return;
    }else if ($file[0] != '<') {
      drupal_set_message(t('Please provide a valid metadata file.'),'error');
      return;
    }
    $document->loadXML($file);
    restore_error_handler();
    $first_child = $document->firstChild;
    if( !empty($first_child) ) {

      /**
       * Check if IDP name is stored or not.
       */
      if( empty( variable_get('miniorange_saml_idp_name' ) ) ){
        variable_set('miniorange_saml_idp_name', 'Identity Provider');
      }

      $metadata = new IDPMetadataReader($document);
      $identity_providers = $metadata->getIdentityProviders();
      if(empty($identity_providers)) {
        drupal_set_message(t('Please provide a valid metadata file.'),'error');
        return;
      }
      foreach($identity_providers as $key => $idp)
      {
        $saml_login_url = $idp->getLoginURL( 'HTTP-Redirect' );
        if( empty($saml_login_url) ) {
          $saml_login_url = $idp->getLoginURL( 'HTTP-POST' );
        }
        $saml_issuer = $idp->getEntityID();
        $saml_x509_certificate = $idp->getSigningCertificate();
        $sp_issuer = $b_url;

        variable_set('miniorange_saml_sp_issuer', $sp_issuer);
        variable_set('miniorange_saml_idp_issuer', $saml_issuer);
        variable_set('miniorange_saml_idp_login_url', $saml_login_url);
        variable_set('miniorange_saml_idp_x509_certificate', $saml_x509_certificate[0]);
      }
      drupal_set_message(t('Identity Provider Configuration successfully saved.'));
      return;
    }
    else {
      drupal_set_message(t('Please provide a valid metadata file.'),'error');
      return;
    }
  }

  public static function isCurlInstalled() {
    if (in_array('curl', get_loaded_extensions())) {
      return 1;
    }else {
      return 0;
    }
  }

  public static function generateID() {
    return '_' . self::stringToHex( self::generateRandomBytes(21 ) );
  }

  public static function stringToHex($bytes) {
    $ret = '';
    for($i = 0; $i < strlen($bytes); $i++) {
      $ret .= sprintf('%02x', ord($bytes[$i]));
    }
    return $ret;
  }

  public static function generateRandomBytes($length, $fallback = TRUE) {
    return openssl_random_pseudo_bytes($length);
  }

  public static function createAuthnRequest($acsUrl, $issuer, $destination, $nameid_format, $force_authn = 'false', $showRequest = 'false') {
    $requestXmlStr = '<?xml version="1.0" encoding="UTF-8"?>' .
      '<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="' . self::generateID() .
      '" Version="2.0" IssueInstant="' . self::generateTimestamp() . '"';
    if( $force_authn == 'true') {
      $requestXmlStr .= ' ForceAuthn="true"';
    }
    $requestXmlStr .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acsUrl .
      '" Destination="' . $destination . '"><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer . '</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="'.$nameid_format.'"
                        /></samlp:AuthnRequest>';

    if($showRequest === 'TRUE'){
      return $requestXmlStr;
    }
    $deflatedStr = gzdeflate($requestXmlStr);
    $base64EncodedStr = base64_encode($deflatedStr);
    $urlEncoded = urlencode($base64EncodedStr);
    return $urlEncoded;
  }

  /*public static function createSAMLRequest($acsUrl, $issuer, $destination, $nameid_format, $force_authn = 'false') {
  $requestXmlStr = '<?xml version="1.0" encoding="UTF-8"?>' .
          '<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="' . self::generateID() .
          '" Version="2.0" IssueInstant="' . self::generateTimestamp() . '"';
  if( $force_authn == 'true') {
    $requestXmlStr .= ' ForceAuthn="true"';
  }
  $requestXmlStr .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acsUrl .
          '" Destination="' . $destination . '"><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer . '</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="'.$nameid_format.'"
                      /></samlp:AuthnRequest>';

      return $requestXmlStr;
  }*/

  public static function generateTimestamp($instant = NULL) {
    if($instant === NULL) {
      $instant = time();
    }
    return gmdate('Y-m-d\TH:i:s\Z', $instant);
  }

  public static function xpQuery(DOMNode $node, $query) {
    //assert('is_string($query)');
    static $xpCache = NULL;

    if ($node instanceof DOMDocument) {
      $doc = $node;
    } else {
      $doc = $node->ownerDocument;
    }

    if ($xpCache === NULL || !$xpCache->document->isSameNode($doc)) {
      $xpCache = new DOMXPath($doc);
      $xpCache->registerNamespace('soap-env', 'http://schemas.xmlsoap.org/soap/envelope/');
      $xpCache->registerNamespace('saml_protocol', 'urn:oasis:names:tc:SAML:2.0:protocol');
      $xpCache->registerNamespace('saml_assertion', 'urn:oasis:names:tc:SAML:2.0:assertion');
      $xpCache->registerNamespace('saml_metadata', 'urn:oasis:names:tc:SAML:2.0:metadata');
      $xpCache->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
      $xpCache->registerNamespace('xenc', 'http://www.w3.org/2001/04/xmlenc#');
    }

    $results = $xpCache->query($query, $node);
    $ret = array();
    for ($i = 0; $i < $results->length; $i++) {
      $ret[$i] = $results->item($i);
    }

    return $ret;
  }

  public static function parseNameId(DOMElement $xml) {
    $ret = array('Value' => trim($xml->textContent));
    foreach (array('NameQualifier', 'SPNameQualifier', 'Format') as $attr) {
      if ($xml->hasAttribute($attr)) {
        $ret[$attr] = $xml->getAttribute($attr);
      }
    }
    return $ret;
  }

  public static function xsDateTimeToTimestamp($time){
    $matches = array();

    // We use a very strict regex to parse the timestamp.
    $regex = '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d+)?Z$/D';
    if (preg_match($regex, $time, $matches) == 0) {
      echo sprintf("Invalid SAML2 timestamp passed to xsDateTimeToTimestamp: ".$time);
      exit;
    }

    // Extract the different components of the time from the  matches in the regex.
    // intval will ignore leading zeroes in the string.
    $year   = intval($matches[1]);
    $month  = intval($matches[2]);
    $day    = intval($matches[3]);
    $hour   = intval($matches[4]);
    $minute = intval($matches[5]);
    $second = intval($matches[6]);

    // We use gmmktime because the timestamp will always be given
    //in UTC.
    $ts = gmmktime($hour, $minute, $second, $month, $day, $year);

    return $ts;
  }

  public static function extractStrings(DOMElement $parent, $namespaceURI, $localName)
  {
    //assert('is_string($namespaceURI)');
    //assert('is_string($localName)');

    $ret = array();
    for ($node = $parent->firstChild; $node !== NULL; $node = $node->nextSibling) {
      if ($node->namespaceURI !== $namespaceURI || $node->localName !== $localName) {
        continue;
      }
      $ret[] = trim($node->textContent);
    }

    return $ret;
  }

  public static function validateElement(DOMElement $root)
  {
    //$data = $root->ownerDocument->saveXML($root);

    /* Create an XML security object. */
    $objXMLSecDSig = new XMLSecurityDSig();

    /* Both SAML messages and SAML assertions use the 'ID' attribute. */
    $objXMLSecDSig->idKeys[] = 'ID';


    /* Locate the XMLDSig Signature element to be used. */
    $signatureElement = self::xpQuery($root, './ds:Signature');

    if (count($signatureElement) === 0) {
      /* We don't have a signature element to validate. */
      return FALSE;
    } elseif (count($signatureElement) > 1) {
      echo sprintf("XMLSec: more than one signature element in root.");
      exit;
    }/*  elseif ((in_array('Response', $signatureElement) && $ocurrence['Response'] > 1) ||
            (in_array('Assertion', $signatureElement) && $ocurrence['Assertion'] > 1) ||
            !in_array('Response', $signatureElement) && !in_array('Assertion', $signatureElement)
        ) {
            return false;
        } */

    $signatureElement = $signatureElement[0];
    $objXMLSecDSig->sigNode = $signatureElement;

    /* Canonicalize the XMLDSig SignedInfo element in the message. */
    $objXMLSecDSig->canonicalizeSignedInfo();

    /* Validate referenced xml nodes. */
    if (!$objXMLSecDSig->validateReference()) {
      echo sprintf("XMLsec: digest validation failed");
      exit;
    }

    /* Check that $root is one of the signed nodes. */
    $rootSigned = FALSE;
    /** @var DOMNode $signedNode */
    foreach ($objXMLSecDSig->getValidatedNodes() as $signedNode) {
      if ($signedNode->isSameNode($root)) {
        $rootSigned = TRUE;
        break;
      } elseif ($root->parentNode instanceof DOMDocument && $signedNode->isSameNode($root->ownerDocument)) {
        /* $root is the root element of a signed document. */
        $rootSigned = TRUE;
        break;
      }
    }

    if (!$rootSigned) {
      echo sprintf("XMLSec: The root element is not signed.");
      exit;
    }

    /* Now we extract all available X509 certificates in the signature element. */
    $certificates = array();
    foreach (self::xpQuery($signatureElement, './ds:KeyInfo/ds:X509Data/ds:X509Certificate') as $certNode) {
      $certData = trim($certNode->textContent);
      $certData = str_replace(array("\r", "\n", "\t", ' '), '', $certData);
      $certificates[] = $certData;
    }

    $ret = array(
      'Signature' => $objXMLSecDSig,
      'Certificates' => $certificates,
    );

    return $ret;
  }

  public static function validateSignature(array $info, XMLSecurityKey $key)
  {
    //assert('array_key_exists("Signature", $info)');

    /** @var XMLSecurityDSig $objXMLSecDSig */
    $objXMLSecDSig = $info['Signature'];

    $sigMethod = self::xpQuery($objXMLSecDSig->sigNode, './ds:SignedInfo/ds:SignatureMethod');
    if (empty($sigMethod)) {
      echo sprintf('Missing SignatureMethod element');
      exit();
    }
    $sigMethod = $sigMethod[0];
    if (!$sigMethod->hasAttribute('Algorithm')) {
      echo sprintf('Missing Algorithm-attribute on SignatureMethod element.');
      exit;
    }
    $algo = $sigMethod->getAttribute('Algorithm');

    if ($key->type === XMLSecurityKey::RSA_SHA1 && $algo !== $key->type) {
      $key = self::castKey($key, $algo);
    }

    /* Check the signature. */
    if (! $objXMLSecDSig->verify($key)) {
      echo sprintf('Unable to validate Sgnature');
      exit;
    }
  }

  public static function castKey(XMLSecurityKey $key, $algorithm, $type = 'public')
  {
    //assert('is_string($algorithm)');
    //assert('$type === "public" || $type === "private"');

    // do nothing if algorithm is already the type of the key
    if ($key->type === $algorithm) {
      return $key;
    }

    $keyInfo = openssl_pkey_get_details($key->key);
    if ($keyInfo === FALSE) {
      echo sprintf('Unable to get key details from XMLSecurityKey.');
      exit;
    }
    if (!isset($keyInfo['key'])) {
      echo sprintf('Missing key in public key details.');
      exit;
    }

    $newKey = new XMLSecurityKey($algorithm, array('type'=>$type));
    $newKey->loadKey($keyInfo['key']);

    return $newKey;
  }

  public static function processResponse($currentURL, $certFingerprint, $signatureData,
                                         SAML2_Response $response, $relayState) {
    //assert('is_string($currentURL)');
    //assert('is_string($certFingerprint)');

    $ResCert = $signatureData['Certificates'][0];
    variable_set('miniorange_saml_expected_certificate', $ResCert);
    /* Validate Response-element destination. */
    $msgDestination = $response->getDestination();
    if ($msgDestination !== NULL && $msgDestination !== $currentURL) {
      echo sprintf('Destination in response doesn\'t match the current URL. Destination is "' .
        $msgDestination . '", current URL is "' . $currentURL . '".');
      exit;
    }

    $responseSigned = self::checkSign($certFingerprint, $signatureData, $relayState, $ResCert);

    /* Returning boolean $responseSigned */
    return $responseSigned;
  }

  public static function checkSign($certFingerprint, $signatureData, $relayState, $ResCert) {

    $certificates = $signatureData['Certificates'];

    if (count($certificates) === 0) {
      return FALSE;
    }

    $fpArray = array();
    $fpArray[] = $certFingerprint;
    $pemCert = self::findCertificate($fpArray, $certificates, $relayState, $ResCert);

    $lastException = NULL;

    $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'public'));
    $key->loadKey($pemCert);

    try {
      /*
       * Make sure that we have a valid signature
       */
      //assert('$key->type === XMLSecurityKey::RSA_SHA1');
      self::validateSignature($signatureData, $key);
      return TRUE;
    } catch (Exception $e) {
      $lastException = $e;
    }


    /* We were unable to validate the signature with any of our keys. */
    if ($lastException !== NULL) {
      throw $lastException;
    } else {
      return FALSE;
    }

  }

  public static function validateIssuerAndAudience($samlResponse, $spEntityId, $issuerToValidateAgainst, $b_url, $relayState) {
    $issuer = current($samlResponse->getAssertions())->getIssuer();
    variable_set('miniorange_saml_expected_issuer',$issuer);
    $audience = current(current($samlResponse->getAssertions())->getValidAudiences());
    if(strcmp($issuerToValidateAgainst, $issuer) === 0) {
      if(strcmp($audience, $b_url) === 0) {
        return TRUE;
      } else {
        echo sprintf('Invalid audience');
        exit;
      }
    } else {
      if($relayState=='testValidate'){
        ob_end_clean();

        echo '<div style="font-family:Calibri;padding:0 3%;">';
        echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Issuer cannot be verified.</p>
                <p>Please contact your administrator and report the following error:</p>
                <p><strong>Possible Cause: </strong>The value in \'IdP Entity ID or Issuer\' field in Service Provider Settings is incorrect</p>
                <p><strong>Expected Entity ID: </strong>'.$issuer.'<p>
                <p><strong>Entity ID Found: </strong>'.$issuerToValidateAgainst.'</p>
                </div>
                <div style="margin:1%;display:block;text-align:center;">
                <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Fix it" onClick="fix_it();">
                <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;margin: inherit; white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>
                <script>
                        function fix_it(){
                            var url = "admin/config/people/miniorange_saml/fix_attribute";
                            window.location = url;
                        }
                    </script>';
        exit;
      }
      else
      {
        echo ' <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>We could not sign you in. Please contact your Administrator.</p></div>
                  <div style="margin:3%;display:block;text-align:center;">
                        <form action='.$b_url.'><input style="padding:1%;width:150px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="submit" value="Back to home"></form></div>';
        exit;
      }
    }
  }

  private static function findCertificate(array $certFingerprints, array $certificates, $relayState,$ResCert) {

    $ResCert = Utilities::sanitize_certificate($ResCert);
    $candidates = array();
    foreach ($certificates as $cert) {
      $fp = strtolower(sha1(base64_decode($cert)));
      if (!in_array($fp, $certFingerprints, TRUE)) {
        $candidates[] = $fp;
        continue;
      }

      /* We have found a matching fingerprint. */
      $pem = "-----BEGIN CERTIFICATE-----\n" .
        chunk_split($cert, 64) .
        "-----END CERTIFICATE-----\n";

      return $pem;
    }

    if($relayState=='testValidate'){
      echo '<div style="font-family:Calibri;padding:0 3%;">';
      echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
            <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Unable to find a certificate matching the configured fingerprint.</p>
            <p><strong>Possible Cause: </strong>Content of \'X.509 Certificate\' field in Service Provider Settings is incorrect</p>
			<p><b>Expected value:</b>' . $ResCert . '</p>';
      echo str_repeat('&nbsp;', 15);
      echo'</div>
                <div style="margin:1%;display:block;text-align:center;">
                <form action="index.php">
                <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Fix it" onClick="fix_it();">
                <input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;margin: inherit; white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>
                </div>
                <script>
                        function fix_it(){
                            var url = "admin/config/people/miniorange_saml/fix_attribute";
                            window.location = url;
                        }
                    </script>';
      exit;

    }
    else{
      echo ' <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>We could not sign you in. Please contact your Administrator.</p></div>';
      exit;
    }
  }

  /**
   * Decrypt an encrypted element.
   *
   * This is an internal helper function.
   *
   * @param  DOMElement     $encryptedData The encrypted data.
   * @param  XMLSecurityKey $inputKey      The decryption key.
   * @param  array          &$blacklist    Blacklisted decryption algorithms.
   * @return DOMElement     The decrypted element.
   * @throws Exception
   */
  private static function doDecryptElement(DOMElement $encryptedData, XMLSecurityKey $inputKey, array &$blacklist)
  {
    $enc = new XMLSecEnc();
    $enc->setNode($encryptedData);

    $enc->type = $encryptedData->getAttribute("Type");
    $symmetricKey = $enc->locateKey($encryptedData);
    if (!$symmetricKey) {
      echo sprintf('Could not locate key algorithm in encrypted data.');
      exit;
    }

    $symmetricKeyInfo = $enc->locateKeyInfo($symmetricKey);
    if (!$symmetricKeyInfo) {
      echo sprintf('Could not locate <dsig:KeyInfo> for the encrypted key.');
      exit;
    }
    $inputKeyAlgo = $inputKey->getAlgorith();
    if ($symmetricKeyInfo->isEncrypted) {
      $symKeyInfoAlgo = $symmetricKeyInfo->getAlgorith();
      if (in_array($symKeyInfoAlgo, $blacklist, TRUE)) {
        echo sprintf('Algorithm disabled: ' . var_export($symKeyInfoAlgo, TRUE));
        exit;
      }
      if ($symKeyInfoAlgo === XMLSecurityKey::RSA_OAEP_MGF1P && $inputKeyAlgo === XMLSecurityKey::RSA_1_5) {
        /*
         * The RSA key formats are equal, so loading an RSA_1_5 key
         * into an RSA_OAEP_MGF1P key can be done without problems.
         * We therefore pretend that the input key is an
         * RSA_OAEP_MGF1P key.
         */
        $inputKeyAlgo = XMLSecurityKey::RSA_OAEP_MGF1P;
      }
      /* Make sure that the input key format is the same as the one used to encrypt the key. */
      if ($inputKeyAlgo !== $symKeyInfoAlgo) {
        echo sprintf( 'Algorithm mismatch between input key and key used to encrypt ' .
          ' the symmetric key for the message. Key was: ' .
          var_export($inputKeyAlgo, TRUE) . '; message was: ' .
          var_export($symKeyInfoAlgo, TRUE));
        exit;
      }
      /** @var XMLSecEnc $encKey */
      $encKey = $symmetricKeyInfo->encryptedCtx;
      $symmetricKeyInfo->key = $inputKey->key;
      $keySize = $symmetricKey->getSymmetricKeySize();
      if ($keySize === NULL) {
        /* To protect against "key oracle" attacks, we need to be able to create a
         * symmetric key, and for that we need to know the key size.
         */
        echo sprintf('Unknown key size for encryption algorithm: ' . var_export($symmetricKey->type, TRUE));
        exit;
      }
      try {
        $key = $encKey->decryptKey($symmetricKeyInfo);
        if (strlen($key) != $keySize) {
          echo sprintf('Unexpected key size (' . strlen($key) * 8 . 'bits) for encryption algorithm: ' .
            var_export($symmetricKey->type, TRUE));
          exit;
        }
      } catch (Exception $e) {
        /* We failed to decrypt this key. Log it, and substitute a "random" key. */

        /* Create a replacement key, so that it looks like we fail in the same way as if the key was correctly padded. */
        /* We base the symmetric key on the encrypted key and private key, so that we always behave the
         * same way for a given input key.
         */
        $encryptedKey = $encKey->getCipherValue();
        $pkey = openssl_pkey_get_details($symmetricKeyInfo->key);
        $pkey = sha1(serialize($pkey), TRUE);
        $key = sha1($encryptedKey . $pkey, TRUE);
        /* Make sure that the key has the correct length. */
        if (strlen($key) > $keySize) {
          $key = substr($key, 0, $keySize);
        } elseif (strlen($key) < $keySize) {
          $key = str_pad($key, $keySize);
        }
      }
      $symmetricKey->loadkey($key);
    } else {
      $symKeyAlgo = $symmetricKey->getAlgorith();
      /* Make sure that the input key has the correct format. */
      if ($inputKeyAlgo !== $symKeyAlgo) {
        echo sprintf( 'Algorithm mismatch between input key and key in message. ' .
          'Key was: ' . var_export($inputKeyAlgo, TRUE) . '; message was: ' .
          var_export($symKeyAlgo, TRUE));
        exit;
      }
      $symmetricKey = $inputKey;
    }
    $algorithm = $symmetricKey->getAlgorith();
    if (in_array($algorithm, $blacklist, TRUE)) {
      echo sprintf('Algorithm disabled: ' . var_export($algorithm, TRUE));
      exit;
    }
    /** @var string $decrypted */
    $decrypted = $enc->decryptNode($symmetricKey, FALSE);
    /*
     * This is a workaround for the case where only a subset of the XML
     * tree was serialized for encryption. In that case, we may miss the
     * namespaces needed to parse the XML.
     */
    $xml = '<root xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" '.
      'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
      $decrypted .
      '</root>';
    $newDoc = new DOMDocument();
    if (!@$newDoc->loadXML($xml)) {
      throw new Exception('Failed to parse decrypted XML. Maybe the wrong sharedkey was used?');
    }
    $decryptedElement = $newDoc->firstChild->firstChild;
    if ($decryptedElement === NULL) {
      echo sprintf('Missing encrypted element.');
      throw new Exception('Missing encrypted element.');
    }

    if (!($decryptedElement instanceof DOMElement)) {
      echo sprintf('Decrypted element was not actually a DOMElement.');
    }

    return $decryptedElement;
  }
  /**
   * Decrypt an encrypted element.
   *
   * @param  DOMElement     $encryptedData The encrypted data.
   * @param  XMLSecurityKey $inputKey      The decryption key.
   * @param  array          $blacklist     Blacklisted decryption algorithms.
   * @return DOMElement     The decrypted element.
   * @throws Exception
   */
  public static function decryptElement(DOMElement $encryptedData, XMLSecurityKey $inputKey, array $blacklist = array(), XMLSecurityKey $alternateKey = NULL)
  {
    try {
      return self::doDecryptElement($encryptedData, $inputKey, $blacklist);
    } catch (Exception $e) {
      //Try with alternate key
      try {
        return self::doDecryptElement($encryptedData, $alternateKey, $blacklist);
      } catch(Exception $t) {

      }
      /*
       * Something went wrong during decryption, but for security
       * reasons we cannot tell the user what failed.
       */

      exit;
    }
  }

  /**
   * Generates the metadata of the SP based on the settings
   *
   * @param string    $sp            The SP data
   * @param string    $authnsign     authnRequestsSigned attribute
   * @param string    $wsign         wantAssertionsSigned attribute
   * @param DateTime  $validUntil    Metadata's valid time
   * @param Timestamp $cacheDuration Duration of the cache in seconds
   * @param array     $contacts      Contacts info
   * @param array     $organization  Organization ingo
   *
   * @return string SAML Metadata XML
   */
  /*public static function metadata_builder($siteUrl)
  {
  $xml = new DOMDocument();
  $url = plugins_url().'/miniorange-saml-20-single-sign-on/sp-metadata.xml';

  $xml->load($url);

  $xpath = new DOMXPath($xml);
  $elements = $xpath->query('//md:EntityDescriptor[@entityID="http://{path-to-your-site}/wp-content/plugins/miniorange-saml-20-single-sign-on/"]');

   if ($elements->length >= 1) {
      $element = $elements->item(0);
      $element->setAttribute('entityID', $siteUrl.'/wp-content/plugins/miniorange-saml-20-single-sign-on/');
  }

  $elements = $xpath->query('//md:AssertionConsumerService[@Location="http://{path-to-your-site}"]');
  if ($elements->length >= 1) {
      $element = $elements->item(0);
      $element->setAttribute('Location', $siteUrl.'/');
  }

  //re-save
  $xml->save(plugins_url()."/miniorange-saml-20-single-sign-on/sp-metadata.xml");
  }*/

  /*public static function get_mapped_groups( $saml_params, $saml_groups )
  {
      $groups = array();

    if (!empty($saml_groups)) {
      $saml_mapped_groups = array();
      $i=1;
      while ($i < 10) {
        $saml_mapped_groups_value = $saml_params->get('group'.$i.'_map');

        $saml_mapped_groups[$i] = explode(';', $saml_mapped_groups_value);
        $i++;
      }
    }

    foreach ($saml_groups as $saml_group) {
      if (!empty($saml_group)) {
        $i = 0;
        $found = false;

        while ($i < 9 && !$found) {
          if (!empty($saml_mapped_groups[$i]) && in_array($saml_group, $saml_mapped_groups[$i])) {
            $groups[] = $saml_params->get('group'.$i);
            $found = true;
          }
          $i++;
        }
      }
    }

    return array_unique($groups);
  }*/


  public static function getEncryptionAlgorithm($method){
    switch($method){
      case 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc':
        return XMLSecurityKey::TRIPLEDES_CBC;
        break;

      case 'http://www.w3.org/2001/04/xmlenc#aes128-cbc':
        return XMLSecurityKey::AES128_CBC;

      case 'http://www.w3.org/2001/04/xmlenc#aes192-cbc':
        return XMLSecurityKey::AES192_CBC;
        break;

      case 'http://www.w3.org/2001/04/xmlenc#aes256-cbc':
        return XMLSecurityKey::AES256_CBC;
        break;

      case 'http://www.w3.org/2001/04/xmlenc#rsa-1_5':
        return XMLSecurityKey::RSA_1_5;
        break;

      case 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p':
        return XMLSecurityKey::RSA_OAEP_MGF1P;
        break;

      case 'http://www.w3.org/2000/09/xmldsig#dsa-sha1':
        return XMLSecurityKey::DSA_SHA1;
        break;

      case 'http://www.w3.org/2000/09/xmldsig#rsa-sha1':
        return XMLSecurityKey::RSA_SHA1;
        break;

      case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256':
        return XMLSecurityKey::RSA_SHA256;
        break;

      case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384':
        return XMLSecurityKey::RSA_SHA384;
        break;

      case 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512':
        return XMLSecurityKey::RSA_SHA512;
        break;

      default:
        echo sprintf('Invalid Encryption Method: '.$method);
        exit;
        break;
    }
  }

  public static function sanitize_certificate( $certificate ) {
    if (!empty($certificate)) {
      $certificate = preg_replace("/[\r\n]+/", "", $certificate);
      $certificate = str_replace( "-", "", $certificate );
      $certificate = str_replace( "BEGIN CERTIFICATE", "", $certificate );
      $certificate = str_replace( "END CERTIFICATE", "", $certificate );
      $certificate = str_replace( " ", "", $certificate );
      $certificate = chunk_split($certificate, 64, "\r\n");
      $certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . "-----END CERTIFICATE-----";
      return $certificate;
    }
  }

  /*public static function desanitize_certificate( $certificate ) {
    $certificate = preg_replace("/[\r\n]+/", "", $certificate);
    //$certificate = str_replace( "-", "", $certificate );
    $certificate = str_replace( "-----BEGIN CERTIFICATE-----", "", $certificate );
    $certificate = str_replace( "-----END CERTIFICATE-----", "", $certificate );
    $certificate = str_replace( " ", "", $certificate );
    //$certificate = chunk_split($certificate, 64, "\r\n");
    //$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . "-----END CERTIFICATE-----";
    return $certificate;
  }*/

  public static function Print_SAML_Request($samlRequestResponceXML,$type){
    header("Content-Type: text/html");
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->loadXML($samlRequestResponceXML);
    if($type=='displaySAMLRequest')
      $show_value='SAML Request';
    else
      $show_value='SAML Response';
    $out = $doc->saveXML();
    $out1 = htmlentities($out);
    $out1 = rtrim($out1);
    $xml   = simplexml_load_string( $out );
    $js_url = drupal_get_path('module', 'miniorange_saml') . '/js/CommonJS.js';
    $url = drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css';
    echo '<link rel=\'stylesheet\' id=\'mo_saml_admin_settings_style-css\'  href=\''.$url.'\' type=\'text/css\' media=\'all\' />
			        <div class="mo-display-logs" ><p type="text"   id="SAML_type">'.$show_value.'</p></div>
			        <div type="text" id="SAML_display" class="mo-display-block"><pre class=\'brush: xml;\'>'.$out1.'</pre></div><br>
			        <div style="margin:3%;display:block;text-align:center;">
			          <div style="margin:3%;display:block;text-align:center;" >
              </div>
			        <button id="copy" onclick="copyDivToClipboard()"  class="mo-show-saml-request-copy-download-btn" >Copy</button>&nbsp;
                    <button onclick="downloadSamlRequest()" class="mo-show-saml-request-copy-download-btn" >Download</button>
			</div>
			</div>';
    ob_end_flush();?>
    <script type="text/javascript" src="<?php echo $js_url; ?>"></script>
    <?php
    exit;
  }

  public static function drupal_is_cli()
  {
    if(!isset($_SERVER['SERVER_SOFTWARE']) && (php_sapi_name() == 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0)))
      return TRUE;
    else
      return FALSE;
  }

}
?>
