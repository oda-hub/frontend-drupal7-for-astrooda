<?php

drupal_add_css( drupal_get_path('module', 'miniorange_saml'). '/css/mo-card.css' , array('group' => CSS_DEFAULT, 'every_page' => FALSE));
drupal_add_js(drupal_get_path('module', 'miniorange_saml') . '/js/dru_visual_tour.js');

$Tour_taken = variable_get('mo_saml_tourTaken_' . getPage_name(), false);

$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$request_scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : $https;

drupal_add_js(array('moTour' => array(
  'pageID' => getPage_name(),
  'tourData' => getTourData(getPage_name(), $Tour_taken),
  'tourTaken' => $Tour_taken,
  'addID' => addID(),
  'pageURL' => $request_scheme . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],

)), array('type' => 'setting'));
variable_set('mo_saml_tourTaken_' . getPage_name(), true);

if(isset($_POST['doneTour']) && isset($_POST['pageID']))
{
  variable_set('mo_saml_tourTaken_'.$_POST['pageID'], $_POST['doneTour']);
}

function getPage_name()
{
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $exploded = explode('/', $link);
  $l_url = end($exploded);
  $l_url = multiexplode(array("?","%","#",":","="),$l_url);
  $f_url = $l_url[0];
  return $f_url;
}

function multiexplode ($delimiters,$string)
{
  $ready = str_replace($delimiters, $delimiters[0], $string);
  $launch = explode($delimiters[0], $ready);
  return  $launch;
}

function mo_visual_tour()
{
  $firstTour = true;
  echo '<div id="mo_saml_restart_tour_button" class="mo-otp-help-button static" style="margin-right:10px;z-index:10">
    <button class="button button-primary button-large">
    <span class="dashicons dashicons-controls-repeat" style="margin:5% 0 0 0;"></span>
        '.mo_("Restart Tour").'
    </button>
    </div>';
}

function addID()
{
  $idArray = array(
    array(
      'selector'  =>'.tabs li:nth-of-type(1)>a',
      'newID'     =>'mo_vt_idp',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(2)>a',
      'newID'     =>'mo_vt_sp',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(3)>a',
      'newID'     =>'mo_vt_mapp',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(4)>a',
      'newID'     =>'mo_vt_signin',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(5)>a',
      'newID'     =>'mo_vt_import',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(7)>a',
      'newID'     =>'mo_vt_license',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(8)>a',
      'newID'     =>'mo_vt_acnt',
    ),
    array(
      'selector'  =>'.tabs li:nth-of-type(9)>a',
      'newID'     =>'mo_vt_query',
    ),
  );
  return $idArray;
}


function getTourData($pageID,$Tour_Taken)
{
  $tourData = array();

  if($Tour_Taken == FALSE)
    $tab_index = 'miniorange_saml';
  else $tab_index = 'idp_tab';

  $tourData['miniorange_saml'] = array(
    0 =>    array(
      'targetE'       =>  'mo_saml_base_url',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Base URL/Site URL</h1>',
      'contentHTML'   =>  'You can change your Base/ Site URL here',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    1 =>    array(
      'targetE'       =>  'mo_saml_issuer_id',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>SP Issuer ID/ Entity ID</h1>',
      'contentHTML'   =>  'You can change your Issuer ID/ Entity ID here',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    2 =>    array(
      'targetE'       =>  'mo_saml_vt_metadata',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata URL</h1>',
      'contentHTML'   =>  'Provide this Metadata URL to configure your IDP',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    3 =>    array(
      'targetE'       =>  'miniorange_download_metadata',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata file</h1>',
      'contentHTML'   =>  'You can download matadata xml file from here.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    4 =>    array(
      'targetE'       =>  'mo_saml_vt_id',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata URLs</h1>',
      'contentHTML'   =>  'You can manually configure your IDP using the information given here.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    5 =>    array(
      'targetE'       =>  'mosaml-feedback-form',
      'pointToSide'   =>  'right',
      'titleHTML'     =>  '<h1>Need help?</h1>',
      'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),
  );
  $tourData[$tab_index] = array(
    0 =>    array(
      'targetE'       =>  'mosaml-feedback-form',
      'pointToSide'   =>  'right',
      'titleHTML'     =>  '<h1>Need help?</h1>',
      'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    1 =>    array(
      'targetE'       =>  'mo_vt_idp',
      'pointToSide'   =>  'up',
      'titleHTML'     =>  '<h1>Service Provider Metadata</h1>',
      'contentHTML'   =>  'This tab provides details to <b>configure your IDP</b>.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    2 =>    array(
      'targetE'       =>  'mo_vt_sp',
      'pointToSide'   =>  'up',
      'titleHTML'     =>  '<h1>Service Provider Setup</h1>',
      'contentHTML'   =>  'Configure your IdP using the information which you get from IDP-Metadata XML',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    3 =>    array(
      'targetE'       =>  'mo_vt_mapp',
      'pointToSide'   =>  'up',
      'titleHTML'     =>  '<h1>Mapping</h1>',
      'contentHTML'   =>  'In this tab you can find <b>attribute mapping, role mapping</b> and more.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    4 =>    array(
      'targetE'       =>  'mo_vt_license',
      'pointToSide'   =>  'up',
      'titleHTML'     =>  '<h1>Upgrade here</h1>',
      'contentHTML'   =>  'You can find <b>Standard, Premium and Enterprise features</b>.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    5 =>    array(
      'targetE'       =>  'mo_saml_base_url',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Base URL/Site URL</h1>',
      'contentHTML'   =>  'You can change your Base/ Site URL here',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    6 =>    array(
      'targetE'       =>  'mo_saml_issuer_id',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>SP Issuer ID/ Entity ID</h1>',
      'contentHTML'   =>  'You can change your Issuer ID/ Entity ID here',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    7 =>    array(
      'targetE'       =>  'mo_saml_vt_metadata',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata URL</h1>',
      'contentHTML'   =>  'Provide this Metadata URL to configure your IDP',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    8 =>    array(
      'targetE'       =>  'miniorange_download_metadata',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata file</h1>',
      'contentHTML'   =>  'You can download matadata xml file from here.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
    9 =>    array(
      'targetE'       =>  'mo_saml_vt_id',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Service provider metadata URLs</h1>',
      'contentHTML'   =>  'You can manually configure your IDP using the information given here.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
    ),
  );

  $tourData['sp_setup'] = array(
    0 =>    array(
      'targetE'       =>  'mosaml_upload',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Upload your metadata</h1>',
      'contentHTML'   =>  'If you have a metadata URL or file provided by your IDP, click on the button.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    1 =>    array(
      'targetE'       =>  'mosaml_vt_name',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>IdP Name</h1>',
      'contentHTML'   =>  'Enter your <b>Identity Provider Name.</b> <br>For example : ADFS, One Login, etc..',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    2 =>    array(
      'targetE'       =>  'mosaml_vt_issuer',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>IdP Entity ID</h1>',
      'contentHTML'   =>  'You can find the <b>EntityID</b> in Your IdP-Metadata XML file enclosed in EntityDescriptor tag having attribute as entityID',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'largemedium',
      'action'        =>  '',
    ),
    3 =>    array(
      'targetE'       =>  'mosaml_vt_loginUrl',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Login URL</h1>',
      'contentHTML'   =>  'You can find the <b>SAML Login URL</b> in Your IdP-Metadata XML file enclosed in SingleSignOnService tag (Binding type: HTTP-Redirect)',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'largemedium',
      'action'        =>  '',
    ),
    4 =>    array(
      'targetE'       =>  'mosaml_vt_x509Cert',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>x.509 Certificate</h1>',
      'contentHTML'   =>  'Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'big',
      'action'        =>  '',
    ),
    5 =>    array(
      'targetE'       =>  'mosaml_vt_enableLogin',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Enable login with SAML</h1>',
      'contentHTML'   =>  'Enable the checkbox if you want to login with SSO/IdP credentials.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    6 =>    array(
      'targetE'       =>  'mosaml-feedback-form',
      'pointToSide'   =>  'right',
      'titleHTML'     =>  '<h1>Need help?</h1>',
      'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),
  );

  $tourData['signon_settings'] = array(

    0 =>    array(
      'targetE'       =>  'mosaml_redirect_chckbx',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Sign In Features</h1>',
      'contentHTML'   =>  'Protect your website, auto redirect the user to IdP and backdoor login features.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    1 =>    array(
      'targetE'       =>  'vs_default_relaystate',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Default Relaystate</h1>',
      'contentHTML'   =>  'Provide the url where you want the users to be redirected after login.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    2 =>    array(
      'targetE'       =>  'vs_default_relaystate_logout',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Default Redirect Logout URL</h1>',
      'contentHTML'   =>  'Provide the url where you want the users to be redirected after logout.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    3 =>    array(
      'targetE'       =>  'vs_domain_restriction',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Domain Restriction</h1>',
      'contentHTML'   =>  'Provide the domain names which you want to allow/block during SSO.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),

  );

  $tourData['mapping_config'] = array(

    0 =>    array(
      'targetE'       =>  'miniorange_saml_vt_mapping',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Configure Attribute Mapping</h1>',
      'contentHTML'   =>  'While auto registering the users in your Drupal site these attributes will automatically get mapped to your Drupal user details',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'big',
      'action'        =>  '',
    ),
    1 =>    array(
      'targetE'       =>  'miniorange_saml_vt_customAttr',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Premium Feature</h1>',
      'contentHTML'   =>  'This feature allows mapping of IdP attributes to your SP attributes.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    2 =>    array(
      'targetE'       =>  'mosaml_vt_enable_chckbx',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Role mapping</h1>',
      'contentHTML'   =>  'Check this option if you want to enable <b>Role Mapping</b>',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    4 =>    array(
      'targetE'       =>  'mosaml_vt_defaut_group',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Default group</h1>',
      'contentHTML'   =>  'Select default group for the new users.You can select any role.',
      'ifNext'        =>  true,
      'buttonText'    =>  'Next',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
    ),
    5 =>    array(
      'targetE'       =>  'mosaml-feedback-form',
      'pointToSide'   =>  'right',
      'titleHTML'     =>  '<h1>Need help?</h1>',
      'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),
  );

  $tourData['export_config'] = array(
    0 =>    array(
      'targetE'       =>  'mosaml_vt_impexp',
      'pointToSide'   =>  'left',
      'titleHTML'     =>  '<h1>Download Configuration</h1>',
      'contentHTML'   =>  'If you are having trouble setting up the module, Export the configurations and mail us at drupalsupport@xecurify.com.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'largemedium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),


  );

  $tourData['customer_setup'] = array(
    0 =>    array(
      'targetE'       =>  'mosaml-feedback-form',
      'pointToSide'   =>  'right',
      'titleHTML'     =>  '<h1>Need help?</h1>',
      'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
      'ifNext'        =>  true,
      'buttonText'    =>  'End Tour',
      'img'           =>  array(),
      'cardSize'      =>  'medium',
      'action'        =>  '',
      'ifskip'        =>  'hidden',
    ),
  );
  return isset($tourData[$pageID]) ? $tourData[$pageID] : 0;
}

/*
                            ********************************
                                    array terms :
                            ********************************
pageID              -   your Page ID, contains array of popups
0                   -   Popup/card number, goes from zero to n. For next Tab card use 'nextCard' instead of number
targetE             -   Element to target to. Has to be element ID without #. If no ID, add one. Empty For none, shows in centre of screen if empty
pointToSide         -   Direction of arrow to point to (up,down,left,right), for no arrow-keep empty (places at center keep targetE empty) //look at this fix
titleHTML           -   Title of card, can be HTML code
contentHTML         -   Content of card, can be HTML code
ifNext              -   if to show(true) Next Button or not(false), Keep False for Card Number('nextTab')
buttonText          -   Next Button Text
img                 -   image(icon) attributes ('src' should not be 'empty' with 'visible' true)
                        src     -   url of image(best for ico/transparent png) icon(https://visualpharm.com/assets/262/Comments-595b40b65ba036ed117d3e48.svg)
                        visible -   to show image or not, true or false
cardSize            -   Card has 3 difined sizes- big, medium and small. Recomended not to use image with small
nextTab             -   This is special card used if you want user to move to next tab during tour, disabled during restart tour

 */