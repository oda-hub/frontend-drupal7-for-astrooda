<?php
/**
 * @file
 * Contains Licensing information for miniOrange SAML Login Module.
 */

 /**
 * Showing Licensing form info.
 */
 function miniorange_saml_licensing() {

    drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/style_settings.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));
    drupal_add_js(drupal_get_path('module', 'miniorange_saml') . '/js/CommonJS.js');
   drupal_add_js( drupal_get_path('module', 'miniorange_saml'). '/js/dru_visual_tour.js' , array('group' => JS_DEFAULT, 'every_page' => FALSE));

     $b_url = Utilities::miniorange_get_baseURL();
     $form['header_top_style_2'] = array(
         '#markup' => '<div class="mo_saml_table_layout_1"><div class="mo_saml_table_layout">
                      <br><b><span style="font-size: 17px;">UPGRADE PLANS</span></b><hr>'
     );

     $form['markup_free'] = array(
         '#markup' => '<html lang="en">
        <body>
        <!-- Pricing Table Section -->
        <section id="mo_saml_pricing-table">
            <div class="mo_saml_mo_container_1">
                <div class="row">
                    <div class="mo_saml_pricing">
                        <div>
                            <div class="mo_saml_pricing-table mo_saml_class_inline_1">
                                <div class="mo_saml_pricing-header">
                                    <h2 class="mo_saml_pricing-title">Features / Plans</h2>
                                </div>
                                <div class="mo_saml_pricing-list">
                                    <ul>
                                        <li>Unlimited Authentications via IdP</li>
                                        <li>Configure SP Using Metadata XML File</li>
                                        <li>Configure SP Using Metadata URL</li>
                                        <li>Basic Attribute Mapping</li>
                                        <li>Basic Role Mapping</li>
                                        <li>Step-By-Step Guide to Setup IdP</li>
                                        <li>Export Configuration</li>
                                        <li>Options to select SAML Request Binding Type</li>
                                        <li>Signed SSO and SLO requests</li>
                                        <li>Import Configuration</li>
                                        <li>Protect your whole site</li>
                                        <li>Force authentication on each login attempt</li>
                                        <li>Custom backdoor login URL</li>
                                        <li>Default Redirect Url after Login</li>
                                        <li>Default Redirect Url after Logout</li>
                                        <li>End to End Identity Provider Configuration **</li>
                                        <li>Integrated Windows Authentication(With ADFS)***</li>
                                        <li>SAML Single Logout</li>
                                        <li>Custom Attribute Mapping</li>
                                        <li>Custom Role Mapping</li>
                                        <li>Auto-sync IdP Configuration from metadata</li>
                                        <li>Generate Custom SP Certificate</li>
                                        <li>Signed requests using different algorithm</li>
                                        <li>Support multiple certificates of IDP</li>
                                        <li>Domain Restriction</li>
                                        <li>Multiple IDP\'s Supported ****</li>
                                    </ul>
                                </div>
                        </div>
                        <div class="mo_saml_pricing-table mo_saml_class_inline">
                            <div class="mo_saml_pricing-header">
                                <p class="mo_saml_pricing-title">Free</p>
                                <p class="mo_saml_pricing-rate"><sup>$</sup> 0</p>
                                <div class="mo_saml_filler-class"></div>
                                 <a class="mo_saml_btn mo_saml_btn-danger mo_saml_btn-sm mo_btn_note" style="background-color: #337ab7 !important; border-color: #337ab7 !important;display: block;">You are on this plan</a>
                            </div>
                            <div class="mo_saml_pricing-list">
                                <ul>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>

                        <div class="mo_saml_pricing-table mo_saml_class_inline">
                            <div class="mo_saml_pricing-header">
                                <p class="mo_saml_pricing-title">Standard<br> <span>(Auto-Redirect to IdP)</span></p>
                                <p class="mo_saml_pricing-rate"><sup>$</sup> 249<sup>*</sup></p>
                                <div class="mo_saml_filler-class"></div>
                                <a class="mo_saml_btn mo_saml_btn-custom mo_saml_btn-danger mo_saml_btn-sm" onclick="click_to_upgrade_or_register(\'' . miniorange_saml_standard_button() . '\');" style="background-color: #337ab7 !important; border-color: #337ab7 !important;display: block;">Click to Upgrade</a>


                            </div>
                            <div class="mo_saml_pricing-list">
                                <ul>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>

                        <div class="mo_saml_pricing-table mo_saml_class_inline">
                            <div class="mo_saml_pricing-header">
                                <p class="mo_saml_pricing-title">Premium<br><span>(Attribute & Role Mapping)</span></p>
                                <p class="mo_saml_pricing-rate"><sup>$</sup> 399<sup>*</sup></p>
                                <a class="mo_saml_btn mo_saml_btn-custom mo_saml_btn-danger mo_saml_btn-sm" onclick="click_to_upgrade_or_register(\'' . miniorange_saml_premium_button() . '\');" style="background-color: #337ab7 !important; border-color: #337ab7 !important;display: block;">Click to Upgrade</a>
                            </div>
                            <div class="mo_saml_pricing-list">
                                <ul>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mo_saml_pricing-table mo_saml_class_inline">
                            <div class="mo_saml_pricing-header">
                                <p class="mo_saml_pricing-title">Enterprise <br><span>(Multiple IdP & Auto-sync IdP metadata)</span></p>
                                <p class="mo_saml_pricing-rate"><sup>$</sup> 449<sup>*</sup></p>
                                <a class="mo_saml_btn mo_saml_btn-custom mo_saml_btn-danger mo_saml_btn-sm" onclick="click_to_upgrade_or_register(\'' . miniorange_saml_enterprise_button() . '\');" style="background-color: #337ab7 !important; border-color: #337ab7 !important;display: block;">Click to Upgrade</a>
                            </div>
                            <div class="mo_saml_pricing-list">
                                <ul>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing Table Section End -->
    </body>
    </html>',
     );

     $form['markup_4'] = array(
         '#markup' => '<div class="mo_saml_space"><h3>We support all the SAML Compliant Identity Providers like :</h3>'
             . 'Google Apps, ADFS, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Azure AD, AuthAnvil, Auth0, Centrify, PingOne, RSA'
             . ', IBM, Oracle, OneLogin, Jboss Keycloak, Bitium, WSO2, CA Identity, PingFederate, NetIQ, miniOrange Identity Provider etc.'
     );

     $form['markup_5'] = array(
         '#markup' => '<h3>Steps to upgrade to module</h3>'
             . '<ol><li>You will be redirected to miniOrange Login Console. Enter your password with which you created an'
             . ' account with us. After that you will be redirected to payment page.</li>'
             . '<li>Enter your card details and complete the payment. On successful payment completion, you will see the '
             . 'link to download the premium module.</li>'
             . 'Once you download the premium module, just unzip it and replace the folder with existing module.'
             . '<li>After the module is installed clear the Drupal cache by clicking on <b><i>Clear all caches</i></b> <a href = "' . $b_url . '/admin/config/development/performance" target = "_blank">here.</a></li>
                </ol>'
     );
     $form['markup_6'] = array(
        '#markup' => '<br><b>* Cost applicable for one instance only.</b> Licenses are perpetual and the Support Plan includes 12 months of maintenance (support and version updates). You can renew maintenance after 12 months at 50% of the current license cost.'
    );

     $form['markup_7'] = array(
         '#markup' => '<h3>** End to End Identity Provider Integration (Additional charges may apply)</h3>'
             . 'We will setup a Conference Call / Gotomeeting and do end to end configuration for you for IDP '
             . 'as well as module. We provide services to do the configuration on your behalf. (Extra charges applicable at $60/hr)<br>
            If you have any doubts regarding the licensing plans, you can mail us at <a href="mailto:drupalsupport@xecurify.com"><i>drupalsupport@xecurify.com</i>
            </a> or submit a query using the support form <b>(support form available on each tab).</b>'
     );

     $form['markup_8'] = array(
        '#markup' => '<h3>***Integrated Windows Authentication</h3>'
            . 'With Integrated windows authentication, if the user comes to your Drupal Site from a domain joined machine'
            . ' then he will not even have to re-enter his credentials because <br>he already did that when he unlocked his computer.'
    );

	$form['markup_9'] = array(
        '#markup' => '<h3>****Multiple IDP Support</h3>'
            . 'If you want users from different Identity Providers to SSO into your site then you can configure the module with multiple IDPs.'
            . ' Additional charges will be applicable based on the number of Identity Providers you wish to configure.'
    );


   $form['markup_11']=array(
     '#markup'=>"<br><br><b>Return Policy -</b><br>At miniOrange, we want to ensure 100% customer satisfaction with our product/module purchase. If the module you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved, we will refund the whole amount given that you have raised a refund request within the first 10 days of the purchase. For any queries regarding the return policy, please email us at <a href='mailto:drupalsupport@xecurify.com' >drupalsupport@xecurify.com</a>.<br>"
   );

   Utilities::AddrfdButton($form,$form_state);

     $form['markup_10'] = array(
         '#markup' => '<br><br></div></div></div>'
     );

     return $form;

 }

 /**
 * Send remove license key.
 */
function miniorange_saml_standard_button(){
    $admin_email =  variable_get('miniorange_saml_customer_admin_email');
    $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
    $URL_Redirect_std = "https://login.xecurify.com/moas/login?username=".$admin_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal_miniorange_saml_standard_plan";
    variable_set('redirect_plan_after_registration_standard',$URL_Redirect_std);

    return return_url($URL_Redirect_std, 'standard');
}

function miniorange_saml_enterprise_button(){
    $admin_email =  variable_get('miniorange_saml_customer_admin_email');
    $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
    $URL_Redirect_enter = "https://login.xecurify.com/moas/login?username=".$admin_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal_miniorange_saml_enterprise_plan";
    variable_set('redirect_plan_after_registration_enterprise',$URL_Redirect_enter);

    return return_url($URL_Redirect_enter, 'enterprise');
}

function miniorange_saml_premium_button(){
    $admin_email =  variable_get('miniorange_saml_customer_admin_email');
    $admin_email = (isset($admin_email) && !empty($admin_email)) ? $admin_email : 'none';
    $URL_Redirect_prem = "https://login.xecurify.com/moas/login?username=".$admin_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=drupal_miniorange_saml_premium_plan";
    variable_set('redirect_plan_after_registration_premium',$URL_Redirect_prem);
    return return_url($URL_Redirect_prem, 'premium');
}

function return_url($url, $payment_plan){
    if(!Utilities::isCustomerRegistered()){
        return $url;
    }else{
        $b_url = Utilities::miniorange_get_baseURL();
        $SAMLrequestUrl = $b_url . '/?q=register_user&payment_plan=' . $payment_plan;
        return $SAMLrequestUrl;
    }
}

function send_rfd_query(&$form, $form_state) {
  $email = trim($form['customer_email']['#value']);
  $demo_plan = $form['demo_plan']['#value'];
  $description_doubt = trim($form['description_doubt']['#value']);
  $query = $demo_plan.' ->'.$description_doubt;

  if(valid_email_address($email))
    Utilities::send_demo_query($email, $query, $description_doubt);
  else {
    drupal_set_message('Please enter valid Email ID', 'error');
    return;
  }
}