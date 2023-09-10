<?php
function miniorange_saml_feedback()
{
    if ((isset($_POST['mo_saml_check'])) && ($_POST['mo_saml_check'] == "True")) {
        //code to send email alert
      if (isset($_POST['miniorange_saml_feedback_submit'])){
        unset($_SESSION['mo_other']);
        $reason = $_POST['deactivate_plugin'];
        $q_feedback = $_POST['query_feedback'];
        $admin_email = variable_get('miniorange_saml_customer_admin_email', '');
        if(empty($admin_email))
            $email = $_POST['miniorange_feedback_email'];
        else
            $email = $admin_email;

        $message = '<br><b>Reason: </b>' . $reason . '<br><b>Feedback:</b>' . $q_feedback;

        $url = 'https://login.xecurify.com/moas/api/notify/send';          //get_option( 'mo2f_host_name' ) . '/moas/api/notify/send';
        $ch = curl_init($url);

        if (valid_email_address($email)) {
          $customer = new MiniorangeSAMLCustomer(NULL, NULL, NULL, NULL);
          list($customerKey, $apiKey) = $customer->getCustomerDetails();
          $phone = variable_get('miniorange_saml_customer_admin_phone', '');

          $fromEmail = $email;
          $query = '[Drupal-7 SAML SP Free] ' . $message;

          $content = '<div >Hello,
                    <br><br>Company :<a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank" >' . $_SERVER['SERVER_NAME'] . '</a>
                    <br><br>Phone Number :' . $phone . '
                    <br><br>Email :<a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a>
                    <br><br><b>Query:</b> ' . $query . '</div>';

          $fields = [
            'customerKey' => $customerKey,
            'sendEmail' => TRUE,
            'email' => [
              'customerKey' => $customerKey,
              'fromEmail' => $fromEmail,
              'fromName' => 'miniOrange',
              'toEmail' => 'drupalsupport@xecurify.com',
              'toName' => 'drupalsupport@xecurify.com',
              'subject' => "Drupal-7 SAML SP Feedback",
              'content' => $content
            ],
          ];
          $response = json_decode($customer->callService($url, $fields, TRUE));
          if (is_object($response) && isset($response->statusCode)) {
            return $response;
          }
        }
      }
    } else if (($_SESSION['mo_other'] == "False")) {
        unset($_SESSION['mo_other']);
        $myArray = array();
        $myArray = $_POST;
        $form_id = $_POST['form_id'];
        $form_token = $_POST['form_token'];
        $admin_email = variable_get('miniorange_saml_customer_admin_email', '');
        ?>

        <html>
        <head>
            <title>Feedback</title>
            <link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
            <style>
                .saml_loader {
                    margin: auto;
                    display: block;
                    border: 5px solid #f3f3f3; /* Light grey */
                    border-top: 5px solid #3498db; /* Blue */
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 2s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function () {
                    if(document.getElementById('miniorange_feedback_email').value == '') {
                        document.getElementById('email_error').style.display = "none";
                        document.getElementById('submit_button').disabled = true;
                    }
                    $("#myModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('.button').click(function() {
                        document.getElementById('saml_loader').style.display = 'block';
                    });
                });

                function validateEmail(emailField) {
                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

                    if (reg.test(emailField.value) == false) {
                        document.getElementById('email_error').style.display = "block";
                        document.getElementById('submit_button').disabled = true;
                    } else {
                        document.getElementById('email_error').style.display = "none";
                        document.getElementById('submit_button').disabled = false;
                    }
                }
            </script>
        </head>
        <body>
        <div class="container">
            <div class="modal fade" id="myModal" role="dialog" style="background: rgba(0,0,0,0.1);">
                <div class="modal-dialog" style="width: 500px;">
                    <div class="modal-content" style="border-radius: 20px;">
                        <div class="modal-header"
                             style="padding: 25px; border-top-left-radius: 20px; border-top-right-radius: 20px; background-color: #8fc1e3;">
                            <h4 class="modal-title" style="color: white; text-align: center;">
                                Hey, it seems like you want to deactivate miniOrange SAML SSO Login module
                            </h4>
                            <hr>
                            <h4 style="text-align: center; color: white;">What happened?</h4>
                        </div>
                        <div class="modal-body"
                             style="font-size: 11px; padding-left: 25px; padding-right: 25px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; background-color: #ececec;">
                            <form name="f" method="post" action="" id="mo_feedback">
                                <div>
                                    <p>

                                        <br>Email: <input onblur="validateEmail(this)" class="form-control" type="email"
                                                          id="miniorange_feedback_email"
                                                          name="miniorange_feedback_email" value=<?php echo $admin_email ?> >
                                    <p style="display: none;color:red" id="email_error">Invalid Email</p>

                                    <br>
                                    <?php
                                    $deactivate_reasons = array(
                                        "Not Working",
                                        "Not receiving OTP during registration",
                                        "Does not have the features I'm looking for",
                                        "Redirecting back to login page after Authentication",
                                        "Confusing interface",
                                        "Bugs in the plugin",
                                        "Other reasons: "
                                    );
                                    foreach ($deactivate_reasons as $deactivate_reasons) {
                                        ?>
                                        <div class="radio" style="vertical-align: middle;">
                                            <label for="<?php echo $deactivate_reasons; ?>">
                                                <input type="radio" name="deactivate_plugin" id="deactivate_plugin"
                                                       value="<?php echo $deactivate_reasons; ?>" required>
                                                <?php echo $deactivate_reasons; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="mo_saml_check" value="True">
                                    <input type="hidden" name="form_token" value=<?php echo $form_token ?>>
                                    <input type="hidden" name="form_id" value= <?php echo $form_id ?>>
                                    <br>
                                    <textarea class="form-control" id="query_feedback" name="query_feedback" rows="4"
                                              cols="50" placeholder="Write your query here"></textarea>
                                    <br><br>
                                    <div class="mo2f_modal-footer">
                                        <input type="submit" id="submit_button" name="miniorange_saml_feedback_submit"
                                               class="button btn btn-primary" value="Submit and Continue"
                                               style="margin: auto; display: block; font-size: 11px;float: left"/>
                                      <input type="submit"
                                             formnovalidate="formnovalidate"
                                             style="margin: auto; display: block; font-size: 11px; float: right;"
                                             name="miniorange_saml_feedback_skip"
                                             class="btn btn-link" value="Skip"/>
                                      <br>
                                        <div class="saml_loader" id="saml_loader" style="display: none;"></div>
                                    </div>
                                    <?php
                                    foreach ($_POST as $key => $value) {
                                        hiddenSAMLFields($key, $value);
                                    }
                                    ?>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>

        <?php
        exit;
    }
}

function hiddenSAMLFields($key, $value)
{
    $hiddenSAMLField = "";
    $value2 = array();
    if (is_array($value)) {
        foreach ($value as $key2 => $value2) {
            if (is_array($value2)) {
                hiddenSAMLFields($key . "[" . $key2 . "]", $value2);
            } else {
                $hiddenSAMLField = "<input type='hidden' name='" . $key . "[" . $key2 . "]" . "' value='" . $value2 . "'>";
            }
        }
    } else {
        $hiddenSAMLField = "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
    }
    echo $hiddenSAMLField;
}

?>

