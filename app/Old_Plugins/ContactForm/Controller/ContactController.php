<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

/**
 * Class ContactForm itemsController
 *
 * @property SettingValue $SettingValue
 * @property ContactForm $ContactForm
 */
class ContactController extends AppController
{
    /**
     * Name of the Controller, 'Contact'
     */
    public $name = 'Contact';

	/**
	 * @var array
	 */
	public $uses = array('ContactForm.ContactForm');

	/**
	 * Index Method
	 *
	 * @return void
	 */
	public function index()
	{
		if (!$this->Auth->user('id') && Configure::read('ContactForm.captcha_for_guests'))
		{
			$captcha = true;
			$this->set('captcha', $captcha);
		}

		if (!empty($this->request->data)) {
			$this->ContactForm->set($this->request->data);
			if ($this->ContactForm->validates()) {
				$this->request->data['ContactForm']['message'] = $this->ContactForm->safeHtml($this->request->data['ContactForm']['message']);

				if (!empty($captcha))
				{

					if (empty($this->request->data['captcha']) || !$this->checkCaptcha($this->request->data['captcha']))
					{
						$this->Session->setFlash('Incorrect captcha entred. Please try again.', 'error');
						$error = true;
					}
				}

				if (empty($error)) {
					try {
						$this->request->data['ContactForm']['created'] = date('Y-m-d H:i:s');

						$this->loadModel('SettingValue');

						$sitename = $this->SettingValue->findByTitle('Site Name');
						$webmaster_email = $this->SettingValue->findByTitle('Webmaster Email');

						$email = new CakeEmail();

						$email->to(Configure::read('ContactForm.submissions_sent_to'));
						$email->from(array(
							$webmaster_email['SettingValue']['data'] => $sitename['SettingValue']['data']
						));
						$email->replyTo(
							$this->request->data['ContactForm']['email'],
							$this->request->data['ContactForm']['name']
						);
						$email->subject(Configure::read('ContactForm.email_subject'));
						$email->emailFormat('html');
						$email->template('ContactForm.contact');
						$email->viewVars(array(
							'data' => $this->request->data['ContactForm']
						));
						$email->send();

						$this->Session->setFlash(Configure::read('ContactForm.success_message'), 'success');
						$this->redirect('/');
					} catch(Exception $e) {
						$this->Session->setFlash($e->getMessage(), 'error');
					}
				}
			} else {
				$this->Session->setFlash('Please fix the form errors below.', 'error');
			}
		}

		$this->set('page_name', Configure::read('ContactForm.name_of_page'));
	}
}