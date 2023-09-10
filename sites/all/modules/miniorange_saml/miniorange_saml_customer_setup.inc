 <?php
/**
 * @file
 * Contains form for customer setup.
 */

/**
 * Customer setup form().
 */
include "mo_saml_visualTour.php";

function miniorange_saml_customer_setup($form, &$form_state)
{
    drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));

  $current_status = variable_get('miniorange_saml_status', '');
  if ($current_status == 'VALIDATE_OTP') {
    $form['miniorange_saml_customer_otp_token'] = array(
      '#type' => 'textfield',
      '#title' => t('OTP<span class="miniorange_saml_red">*</span>'),
      '#attributes' => array('style' => 'width:45%'),
      '#prefix' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout mo_saml_container">',
      '#description' => t('Please enter the OTP that has been sent to the registered Email ID.'),
    );

    $form['miniorange_saml_customer_validate_otp_button'] = array(
      '#type' => 'submit',
      '#value' => t('Validate OTP'),
      '#submit' => array('miniorange_saml_validate_otp_submit'),
    );

    $form['miniorange_saml_customer_setup_resendotp'] = array(
      '#type' => 'submit',
      '#value' => t('Resend OTP'),
      '#submit' => array('miniorange_saml_resend_otp'),
    );

    $form['miniorange_saml_customer_setup_back'] = array(
      '#type' => 'submit',
      '#value' => t('Back'),
      '#submit' => array('miniorange_saml_back'),
      '#suffix' => '<br><br><br><br><br><br><br><br><br><br><br><br>
                    </div>',
    );

    Utilities::spConfigGuide($form, $form_state);
    Utilities::AddSupportButton($form, $form_state);

    return $form;
  }
  elseif ($current_status == 'PLUGIN_CONFIGURATION')
  {
      $form['header_top_style_1'] = array(
          '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout mo_saml_container">
                        <div class="mo_saml_welcome_message">Thank you for registering with miniOrange</div><br><br>
                        <h4>Your Profile: </h4>'
      );

    $header = array(
      'email' => array('data' => t('Customer Email')),
      'customerid' => array('data' => t('Customer ID')),
      'token' => array('data' => t('Drupal Core')),
      'apikey' => array('data' => t('PHP Version')),
    );

    $options = array();

    $options[0] = array(
      'email' => variable_get('miniorange_saml_customer_admin_email', ''),
      'customerid' => variable_get('miniorange_saml_customer_id', ''),
      'drupal_core' => VERSION,
      'php' => phpversion(),
    );

    $form['fieldset']['customerinfo'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $options,
    );

    $form['miniorange_saml_customer_Remove_Account_info'] = array(
      '#markup' => '<br><br><br><br><br><br><br><h4>Remove Account: </br></h4><p>This section will help you to remove your current logged in account without losing your current configurations.</p>'
    );

  $form['miniorange_saml_remove_account_button'] = array(
      '#type' => 'submit',
      '#value' => t('Remove Account '),
      '#submit'=> array('miniorange_saml_remove_account'),
      '#suffix' => '<br></div>'
  );

  Utilities::spConfigGuide($form, $form_state);
  Utilities::AddSupportButton($form, $form_state);

  return $form;
}

  $form['header_top_style_1'] = array(
      '#markup' => '<div class="mo_saml_table_layout_1">
                    <div class="mo_saml_table_layout mo_saml_container" id="mo_saml_vt_register"><b>
                    <span style="font-size: 19px;">Register/Login with miniOrange (Optional)</span></b><br><br><hr>
                    <div class="mo_saml_register_message">You need to <a>Register/login</a> with miniOrange before upgrade to our paid version of the module.</div>'
  );

  $form['markup_15'] = array(
    '#markup' => '<br><div class="mo_saml_highlight_background_note_1" style="width: auto"><h3>Why Should I register?</h3>You should register so that in case you need help, we can help you with step by step instructions.
                    We support all known IdPs - ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2 etc.
                    <b>You will also need a miniOrange account to upgrade to the standard, premium or enterprise versions of the modules.</b> We do not store any information except the email that you will use to register with us.</div>',
  );

  $form['mo_register'] = array(
    '#markup' => '<br><div class="mo_saml_highlight_background_note_1" style="width: auto">If you face any issues during registration then you can <a href="https://www.miniorange.com/businessfreetrial" target="_blank">click here</a> to register and use the same credentials below to login into the module.</div><br>',
  );

  $form['miniorange_saml_customer_setup_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Email<span class="miniorange_saml_red">*</span>'),
    '#attributes' => array('style' => 'width:60%'),
    '#description' => t('<b>Note:</b> Use valid Email. (We discourage the use of disposable emails)'),
  );

    $form['miniorange_saml_customer_setup_phone'] = array(
        '#type' => 'textfield',
        '#title' => t('Phone'),
        '#attributes' => array('style' => 'width:60%', 'pattern' => '[\+][0-9]{1,4}\s?[0-9]{7,12}', 'placeholder' => 'Enter your phone number with country code (+1)'),
		    '#description' => t('<b>Note:</b> We will only call if you need support.'),
    );

    $form['miniorange_saml_customer_setup_password'] = array(
    '#type' => 'password_confirm',
    '#description' => t('<b>Note:</b> Minimum Length is 6.'),
  );

  $form['miniorange_saml_customer_setup_button'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
    '#submit' => array('miniorange_saml_customer_setup_submit'),
    '#attributes' => array('class'=>array('mo_saml_save_mapping_config_button')),
    '#suffix' => '</div>',
  );

  Utilities::Two_FA_Advertisement($form, $form_state);
  Utilities::AddSupportButton($form, $form_state);

  return $form;
}

/**
 * Validate OTP.
 */
function miniorange_saml_validate_otp_submit(&$form, $form_state) {

  $otp_token = $form['miniorange_saml_customer_otp_token']['#value'];
  Utilities::validate_otp_submit($otp_token);
}

/**
 * Resend OTP.
 */
function miniorange_saml_resend_otp(&$form, $form_state) {
  Utilities::saml_resend_otp();
}

/**
 * Handle submit for customer setup.
 */
function miniorange_saml_customer_setup_submit(&$form, $form_state){
  $username = $form['miniorange_saml_customer_setup_username']['#value'];
  if (!filter_var($username, FILTER_VALIDATE_EMAIL)){
    drupal_set_message('Please enter the valid Email','error');
    return;
  }
  $phone = $form['miniorange_saml_customer_setup_phone']['#value'];
  $password = $form['miniorange_saml_customer_setup_password']['#value']['pass1'];
  if(empty($username)||empty($password)){
      drupal_set_message(t('The <b>Email Address</b> and <b>Password</b> fields are mandatory.'), 'error');
      return;
  }elseif( strlen($password) < 6 ){
      drupal_set_message(t('The length of <b>Password</b> field must be atleast 6.'), 'error');
      return;
  }


  Utilities::customer_setup_submit($username, $phone, $password);
}

/**
 * Handle back button submit for customer setup.
 */
function miniorange_saml_back(&$form, $form_state) {
  variable_set('miniorange_saml_status', 'CUSTOMER_SETUP');
  variable_del('miniorange_saml_customer_admin_email');
  variable_del('miniorange_saml_customer_admin_phone');
  variable_del('miniorange_saml_tx_id');
  drupal_set_message(t('Register/Login with your miniOrange Account'));
}

 /*
  * Remove Account
  */
 function miniorange_saml_remove_account(&$form, $form_state)
 {
   global $base_url;
   if (isset($_POST['value_check']) && $_POST['value_check'] == 'True')
   {
     $username = variable_get('miniorange_saml_idp_customer_admin_email', NULL);
     $phone = variable_get('miniorange_saml_idp_customer_admin_phone', NULL);

     $current_status = 'MOIDP_CUSTOMER_SETUP';
     variable_del('miniorange_saml_customer_admin_email');
     variable_del('miniorange_saml_customer_admin_phone');
     variable_del('miniorange_saml_customer_id');
     variable_del('miniorange_saml_customer_api_key');
     variable_del('miniorange_saml_customer_admin_token');
     variable_set('miniorange_saml_status', $current_status);
     drupal_set_message(t('Your account has been removed successfully!'),'status');
     $_POST['value_check'] = 'False';
   }else {
     $myArray = array();
     $myArray = $_POST;
     $form_id = $_POST['form_id'];
     $form_token = $_POST['form_token'];
     $op = $_POST['op'];
     $build_id = $_POST['form_build_id'];
     ?>

     <html>
     <head>
       <title>Confirmation</title>
       <link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
     </head>

     <body style="font-family: 'PT Serif', serif;">
     <div style="margin: 15% auto; height:30%; width: 40%; background-color: #eaebed; text-align: center; box-shadow: 10px 5px 5px darkgray; border-radius: 2%;">
       <div style="color: #a94442; background-color:#f2dede; padding: 15px; margin-bottom: 20px; text-align:center; border:1px solid #E6B3B2; font-size:16pt; border-radius: 2%;">
         <strong>Are you sure you want to remove account..!!</strong>
       </div>

       <br/>
       <form name="f" method="post" action="" id="mo_remove_account">
         <div>
           <input type="hidden" name="op" value=<?php echo $op;?>>
           <input type="hidden" name="form_build_id" value= <?php echo $build_id;?>>
           <input type="hidden" name="form_token" value=<?php echo $form_token;?>>
           <input type="hidden" name="form_id" value= <?php echo $form_id;?>>
           <input type="hidden" name="value_check" value= 'True'>
         </div>
         <div  style="margin: auto; text-align: center;"   class="mo2f_modal-footer">
           <input type="submit" style=" padding:1%; width:100px; background: #0091CD none repeat scroll 0% 0%; cursor: pointer; font-size:15px; border-width: 1px; border-style: solid; border-radius: 3px; white-space: nowrap; box-sizing: border-box;border-color: #0073AA; box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset; color: #FFF;" name="miniorange_confirm_submit" class="button button-danger button-large" value="Confirm"/>
           <a style=" padding:1%; width:100px; background: #0091CD none repeat scroll 0% 0%; cursor: pointer; font-size:15px; border-width: 1px; border-style: solid; border-radius: 3px; white-space: nowrap; box-sizing: border-box;border-color: #0073AA; box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset; color: #FFF; text-decoration: none; " href=<?php echo($base_url."/admin/config/people/miniorange_saml/customer_setup"); ?> >Cancel</a>
         </div>
       </form>
     </div>
     </body>
     </html>
     <?php
     exit;
   }
 }
