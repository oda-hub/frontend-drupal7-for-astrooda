function copyDivToClipboard() {
  var aux = document.createElement("input");
  aux.setAttribute("value", document.getElementById("SAML_display").textContent);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
  document.getElementById('copy').textContent = "Copied";
  document.getElementById('copy').style.background = "grey";
  window.getSelection().selectAllChildren( document.getElementById( "SAML_display" ) );
}

function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:Application/octet-stream;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}

function downloadSamlRequest() {
  var filename = document.getElementById("SAML_type").textContent+".xml";
  var node = document.getElementById("SAML_display");
  htmlContent = node.innerHTML;
  text = node.textContent;
  download(filename, text);
}


/*Custom Certificate- Show custom generate certificate form*/
function show_gen_cert_form() {
  jQuery('#generate_certificate_form').show();
  jQuery('#mo_gen_cert').hide();
  jQuery('#mo_gen_tab').hide();
}

/*Hide generate custom certificate form*/
function hide_gen_cert_form() {
  jQuery('#generate_certificate_form').hide();
  jQuery('#mo_gen_cert').show();
  jQuery('#mo_gen_tab').show();
}

function click_to_upgrade_or_register(url){
  if(url.search("xecurify") == -1){
    window.location = url;
  }
  else
    window.open(url,"_blank" );
}