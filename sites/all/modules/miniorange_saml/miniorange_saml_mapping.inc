<?php
/**
 * @file
 * Contains Attribute and Role Mapping for miniOrange SAML Login Module.
 */

 /**
 * Showing Settings form.
 */
include "mo_saml_visualTour.php";
 function miniorange_saml_mapping($form, &$form_state)
 {
   drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));

   $pdo_exception_error = Utilities::pdo_exception_error();
   if($pdo_exception_error){
     $form['markup_reg_msg'] = array(
       '#markup' => '<div class="mo_saml_register_message" style="padding-left: 100px; padding-right: 100px;">User is trying to perform the SSO and unable to create an account. The possible cause for this error could be the Email ID is missing in the NameID attribute of IDP response. Also, Username and Email ID should be unique for different users. </div>',
     );
   }

   $form['markup_top'] = array(
     '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout mo_saml_container"><b>
                        <span style="font-size: 17px;">ATTRIBUTE MAPPING</span></b>
                        <a class="mo_saml_btn mo_saml_btn-primary btn-large mo_saml_restart_button" id="mo_saml_restart_tour_button">Take a Tour</a>
                        <br><br><hr><br/>'
   );


     $form['Configure_Attribute_Mapping_End'] = array(
         '#markup' => '<br><div class="mo_saml_highlight_background_note_1"><b>Note: Username Attribute</b>, <b>Email Attribute</b> and <b>Role Key</b> are configurable in
                        <a href="' . Utilities::getLicensePageURL() . '">Standard, Premium and Enterprise</a> versions of the module.<br>
                       </div><br>',
     );

     $form['miniorange_saml_username_attribute'] = array(
        '#type' => 'textfield',
        '#title' => t('Username Attribute'),
        '#attributes' => array('class' => array('mo_saml_textbox'),'placeholder' => 'NameID'),
	      '#disabled' => TRUE,
        '#prefix' => '<div id="miniorange_saml_vt_mapping">',
     );

     $form['miniorange_saml_email_attribute'] = array(
        '#type' => 'textfield',
        '#title' => t('Email Attribute'),
        '#attributes' => array('class' => array('mo_saml_textbox'),'placeholder' => 'NameID'),
	      '#disabled' => TRUE,
        '#suffix' => '</div>',
     );

     $form['miniorange_saml_gateway_config_atrr_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#submit' => array('miniorange_saml_save_mapping_settings'),
         '#disabled' => TRUE,
         //'#suffix' => '</div>',
     );

     //Utilities::advertiseNetworkSecurity($form, $form_state);

     $form['markup_cam_attr'] = array(
         '#markup' => '<br><br><br><h4>CUSTOM ATTRIBUTE MAPPING </h4><hr><br>
                    <div class="mo_saml_highlight_background_note"><b>Note: </b>Custom Attribute Mapping are configurable in
                    <a href="' . Utilities::getLicensePageURL() . '">Premium and Enterprise</a> versions of the module.</div>',
     );

     $form['markup_cam'] = array(
         '#markup' => '<br><div class="mo_saml_highlight_background_note_1"><p><b>NOTE: </b> Add the Drupal field attributes in the Attribute Name textfield and add the
                            IdP attibutes that you need to map with the drupal attributes in the IdP Attribute Name textfield.
                             <br> <b>Attribute Name:</b> It is the user attribute (machine name) whose value you want to set in site.
                             <br> <b>IdP Attribute Name:</b> It is the name which you want to get from your IDP. It should be unique.</p></div>
                             <p><b>For example: If the attribute name in the drupal is name then its machine name will be field_name.</b></p>',
     );

     $form['miniorange_saml_attr5_name'] = array(
	      '#type' => 'textfield',
	      '#title' => t('Attribute Name 1 '),
        '#attributes' => array('class' => array('mo_saml_textbox'),'placeholder' => 'Enter Attribute Name'),
	      '#disabled' => TRUE,
        '#prefix' => '<div id="miniorange_saml_vt_customAttr">',
     );

     $form['miniorange_saml_idp_attr5_name'] = array(
    	  '#type' => 'textfield',
    	  '#title' => t('IdP Attribute Name 1 '),
    	  '#attributes' => array('class' => array('mo_saml_textbox'),'placeholder' => 'Enter IdP Attribute Name'),
    	  '#disabled' => TRUE,
     );

     $form['markup_idp_user_attr_header'] = array(
         '#markup' => '</div><br /><br><h4> ADDITIONAL USER ATTRIBUTES (OPTIONAL)<a href="' . Utilities::getLicensePageURL() . '"> <b>[Premium and Enterprise]</b>
                        </a> <input type="button" class="mo_saml_btn mo_saml_btn-primary" style="width:3%;margin-left:2%;margin-right:2%;" value="+" onclick="add_user_attibute();" />
                        <input type="button" class="mo_saml_btn mo_saml_btn-danger" style="width:3%;" value="-" onclick="remove_user_attibute();" /></h4>',
     );

     $form['markup_idp_user_attr_note'] = array(
         '#markup' => '<div class="messages status" style="width: 74%;">Attribute Name: It is the user attribute (machine name) whose value you want to set in site.
                        <br />IdP Attribute Name: It is the name which you want to get from your IDP. It should be unique.</div><br>',
     );

     $form['miniorange_saml_gateway_config_custom_attr_submit'] = array(
         '#type' => 'submit',
         '#value' => t('Save Configuration'),
         '#disabled' => TRUE,
         '#submit' => array('miniorange_saml_save_mapping_settings'),
         '#suffix' => '</br>',
     );

     $form['mosaml_vt_checkbx']=array(
         '#markup'=>'<br><br><br><h4>ROLE MAPPING </h4>
                    <hr><br><div id="mosaml_vt_enable_chckbx">'
     );

     $form['miniorange_saml_enable_rolemapping'] = array(
         '#type' => 'checkbox',
         '#title' => t('Check this option if you want to <b>enable Role Mapping</b>'),
         '#description' => t('<b style="color: red">Note:</b> Enable this checkbox first before using any of the feature below.'),
         '#default_value' => variable_get('miniorange_saml_enable_rolemapping'),
         '#suffix' => '</div><div id="mosaml_vt_defaut_group">',
     );

     $mrole= user_roles($membersonly = TRUE);
     $drole = array_search(variable_get('miniorange_saml_default_role',''),$mrole);

     $form['miniorange_saml_default_mapping'] = array(
         '#type' => 'select',
         '#title' => t('Select default group for the new users'),
         '#description'=>t('<strong>Note: </strong>This role will be assigned to user when he uses SSO for the first time. (<em> at the time of user creation in Drupal site </em>)'),
         '#options' => $mrole,
         '#default_value' => $drole,
         '#attributes' => array('style' => 'width:81%'),
         '#suffix' => '</div><div class="mo_saml_highlight_background_note_1">',
     );

     $form['miniorange_disable_attribute'] = array(
         '#type' => 'checkbox',
         '#title' => t('Do not update existing user&#39;s role. <a href="' . Utilities::getLicensePageURL() . '">[Premium and Enterprise]</a>'),
         '#disabled' => TRUE,
     );

     $form['miniorange_saml_disable_role_update'] = array(
         '#type' => 'checkbox',
         '#title' => t('Check this option if you do not want to update user role if roles not mapped.'),
         '#disabled' => TRUE,
     );

     $form['markup_cam_attr_map1'] = array(
         '#markup' => '</div><br><br><h4>CUSTOM ROLE MAPPING </h4><hr><br>
                        <div class="mo_saml_highlight_background_note"><b>NOTE : </b>Custom Role Mapping are configurable in
                        <a href="' . Utilities::getLicensePageURL() . '">Premium and Enterprise</a> versions of the module.</div><br>',
     );

     $form['miniorange_saml_idp_attr1_name'] = array(
         '#type' => 'textfield',
         '#title' => t('Role Key'),
         '#attributes' => array('class' => array('mo_saml_textbox'),'placeholder' => 'Enter Role Attribute'),
         '#description' => '<b>Note:</b> You can find role key in test configuration window.',
         '#disabled' => TRUE,
     );

     $form['markup_idp_user_role_header'] = array(
        '#markup' => '</br><h3> Role Attributes<input type="button" class="mo_saml_btn mo_saml_btn-primary" style="width:3%;margin-left:2%;margin-right:2%;" value="+" /> </h3>',
    );

    $form['markup_idp_user_role_header1'] = array(
        '#markup' => '<div id="userSpRoleDiv"><div class="row" id="before_role_list_upa">'
        . ' <div style="width:25%;display:inline-block;"><b>Drupal Role</b></div><div style="width:30%;display:inline-block;">'
        . ' <b>&nbsp;IdP Role</b></div></div>'
    );

    $selected_string = '';

    foreach ($mrole as $key => $value) {
        $selected_string .= '<option value='.$key.'>'.$value.'</option>';
    }

    $form['markup_idp_user_role_list'] = array(
                '#markup' => '<div class="row userRole" style="padding-bottom:1%;" id="role" >
                       <div style="width:25%;display:inline-block;">
                             <select name="user_sp_role_name" class="form-control sp_role_class">'.$selected_string.'</select>
                       </div>
                       <div style="width:45%;display:inline-block;">
                           <input type="text" name="user_idp_role_name" class="form-text" placeholder="semi-colon(;) separated" disabled/>
                       </div>
                       <div style="width:25%;display:inline-block;">
                           <input type="button" class="mo_saml_btn mo_saml_btn-danger" style="width:10%;" value="-"/>
                       </div>
                     </div>'
                );


     $form['markup_cam_log_1'] = array(
        '#markup' => '<br><div id="miniorange_saml_vt_customLogin"></br><h4>CUSTOM LOGIN/LOGOUT (OPTIONAL): <input type="button" class="mo_saml_btn mo_saml_btn-primary" style="width:3%;margin-left:2%;margin-right:2%;" value="+" /></h4><hr>
                <br><div class="mo_saml_highlight_background_note_1"><b>Note: </b>Custom Login/Logout are configurable in
                <a href="' . Utilities::getLicensePageURL() . '">Premium and Enterprise</a> versions of the module.</div><br>',
     );

     $form['markup_idp_log_url_list'] = array(
        '#markup' => '<table class="table_class">
                        <tr>
                        <td style="width:25% !important;">
                        <select name="role_class" class="form-control sp_role_class">'.$selected_string.'</select>
                        </td>
                        <td style="width:28% !important;">
                        <input type="text" name="logout_url" class="form-text" placeholder="Enter Custom Logout Url" disabled/>
                        </td>
                        <td style="width:28% !important;">
                        <input type="text" name="login_url" class="form-text" placeholder="Enter Custom Login Url" disabled/>
                        </td>
                        <td style="border-right:none !important;">
                        <input type="button" style="width:15% !important;" class="mo_saml_btn mo_saml_btn-danger" style="width:10%;" value="-"/>
                        </td>
                        </tr>
                    </table>'
     );

    $form['miniorange_saml_gateway_config_custom_role_submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save Configuration'),
        '#submit' => array('miniorange_saml_save_mapping_settings'),
        '#attributes' => array('class'=>array('mo_saml_save_mapping_config_button'),),
        '#prefix' => '<br></div><br>',
        '#suffix' => '<br></div></div>',

    );

   Utilities::advertiseNetworkSecurity($form, $form_state,'SCIM');
    Utilities::AddSupportButton($form, $form_state);
    return $form;
 }

  function miniorange_saml_save_mapping_settings($form, &$form_state) {
      $mrole= user_roles($membersonly = TRUE);
      $enable_rolemapping = $form['miniorange_saml_enable_rolemapping']['#value'];
      $username_attribute = $form['miniorange_saml_username_attribute']['#value'];
      $email_attribute = $form['miniorange_saml_email_attribute']['#value'];
      $default_mapping= $form['miniorange_saml_default_mapping']['#value'];

      $enable_rolemapping_value = $enable_rolemapping == 1 ? TRUE : FALSE;
      $mo_default_role = $enable_rolemapping_value === TRUE ? $mrole[$default_mapping] : $mrole[2];
      variable_set('miniorange_saml_enable_rolemapping', $enable_rolemapping_value);
      variable_set('miniorange_saml_default_role', $mo_default_role);
      variable_set('miniorange_saml_username_attribute', $username_attribute);
      variable_set('miniorange_saml_email_attribute', $email_attribute);
      drupal_set_message(t('Mapping Configuration Settings successfully saved'));
  }
