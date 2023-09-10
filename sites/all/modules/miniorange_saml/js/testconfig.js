function testConfig(testUrl) {
	var myWindow = window.open(testUrl, "TEST SAML IDP", "scrollbars=1 width=800, height=600");
}
function showSAMLrequest(SAMLrequestUrl) {
	var myWindow = window.open(SAMLrequestUrl, "TEST SAML IDP", "scrollbars=1 width=800, height=600");
}
function showSAMLresponse(SAMLresponseUrl) {
	var myWindow = window.open(SAMLresponseUrl, "TEST SAML IDP", "scrollbars=1 width=800, height=600");
}
function exportConfiguration() {

}

function show_metadata_form() {
	jQuery('#upload_metadata_form').show();
	jQuery('#idpdata').hide();
	jQuery('#tabhead').hide();

}

function hide_metadata_form() {
	jQuery('#upload_metadata_form').hide();
	jQuery('#idpdata').show();
	jQuery('#tabhead').show();
}

function display(c) {

 if(c.value=="miniorange") {
 	jQuery("#mo_miniorange_idp").show();
 	jQuery("#mo_miniorange_broker").hide();
 	jQuery("#mo_other_idp").hide();
 }
 if(c.value=="true") {
 	jQuery("#mo_miniorange_idp").hide();
 	jQuery("#mo_miniorange_broker").show();
 	jQuery("#mo_other_idp").hide();
 }
 if(c.value=="false") {
 	jQuery("#mo_miniorange_idp").hide();
 	jQuery("#mo_miniorange_broker").hide();
 	jQuery("#mo_other_idp").show();
 }

}

function altdisplay(s) {
	var pos = (s.href).indexOf("#");
	var t = (s.href).slice(pos);
	jQuery(t).toggle();
}


