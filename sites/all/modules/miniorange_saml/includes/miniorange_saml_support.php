<?php
/**
 * @file
 * Contains miniOrange Support class.
 */

/**
 * @file
 * This class represents support information for customer.
 */
class MiniorangeSamlSupport {
	public $email;
	public $phone;
	public $query;
	/**
     * Constructor.
     */
	public function __construct($email, $phone, $query,$plan='') {
      $this->email = $email;
      $this->phone = $phone;
      $this->query = $query;
      $this->plan  = $plan;
	}

	/**
	 * Send support query.
	 */
	public function sendSupportQuery() {
    if ($this->plan == 'demo') {
      $subject = "Demo request for Drupal-7 SP Module";
      $this->query = 'Demo required for - ' . $this->query;
      $content = '[Drupal-7 SP Free demo request: ]' . $this->query;
      $fields = array (
        'company' => $_SERVER ['SERVER_NAME'],
        'email' => $this->email,
        'ccEmail' => 'drupalsupport@xecurify.com',
        'phone' => $this->phone,
        'query' => $content,
        'subject' => $subject
      );
    }
    else {
      $this->query = '[Drupal-7 SAML SP Free] :' . $this->query;
      $fields = array(
        'company' => $_SERVER ['SERVER_NAME'],
        'email' => $this->email,
        'ccEmail' => 'drupalsupport@xecurify.com',
        'phone' => $this->phone,
        'query' => $this->query,
        'subject' => "Drupal-7 SAML SP Free Query",
      );
    }
    $url = MiniorangeSAMLConstants::BASE_URL . '/moas/rest/customer/contact-us';
    $customer = new MiniorangeSAMLCustomer(null, null,null,null);
    $response = $customer->callService($url, $fields, true);
    return $response === 'Query submitted.';
	}
}
