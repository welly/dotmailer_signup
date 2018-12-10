<?php

namespace Drupal\dotmailer_signup_form_block\Form;

use Drupal\dotmailer_signup_form_block\DotmailerAPI;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use GuzzleHttp\Exception\RequestException;

/**
 * Implementing a ajax form.
 */
class DotmailerSignupForm extends FormBase {

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'dotmailer_signup_form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['field_wrapper_start'] = [
			'#type'                  => 'markup',
			'#markup'                => '<div class="form-inner form-inline">',
		];

		$form['input_group_start'] = [
			'#type'                  => 'markup',
			'#markup'                => '<div class="input-group">',
		];

    $form['blah']            = [
      '#type'                  => 'textfield',
      '#title'                 => $this->t('Name'),
      '#placeholder'           => $this->t('Name or Org'),
      '#attributes'            => [
        'class'                => ['form-control']
      ],
    ];

    $form['email']             = [
			'#type'                  => 'textfield',
			'#title'                 => $this->t('Email Address'),
			'#placeholder'           => $this->t('Enter your email address'),
			'#required'			         => true,
			'#attributes'            => [
				'class'                => ['form-control']
			],
		];

		$form['submit']           = [
			'#type'                  => 'submit',
			'#attributes'            => [
				'class'                => ['btn']
			],
			'#value'                 => $this->t('Sign-up'),
			'#ajax'                  => [
				'callback'             => '::validateCallback',
				'event'								 => 'click',
			],
		];

		$form['input_group_end']   = [
			'#type'                  => 'markup',
			'#markup'                => '</div>',
		];

		$form['field_wrapper_end'] = [
			'#type'                  => 'markup',
			'#markup'                => '</div">',
		];

		$form['message']           = [
			'#type'                  => 'markup',
			'#markup'                => '<div class="form-response"></div>',
		];

    return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state) {
		parent::validateForm($form, $form_state);
	}

	/**
	 * Setting the message in our form.
	 */
	public function validateCallback(array $form, FormStateInterface $form_state) {

		$config = \Drupal::config('dotmailer.adminsettings');

		$response = new AjaxResponse();

    \Drupal::logger('dotmailer_signup_form')->notice('Hello world validate');


    if ($form_state->getValue('email') === false) {
			$message	=	t('Please enter your email address.');
			$response->addCommand(new InvokeCommand('#edit-email', 'focus'));
		} else {
			try {
				$data = array();
				$data['email']     = $form_state->getValue('email');
				$data['emailType'] = 'Html';
				$data['optInType'] = 'Single';

				if ( $name = $form_state->getValue('name') ) {
					$data['dataFields'] = array(
						'FULLNAME' => $name,
					);
				}

				$dotmailer 	= new DotmailerAPI($config->get('username'), $config->get('password'), $config->get('api_endpoint'));
				$connect 		= $dotmailer->addContact($config->get('addressbook'), $data);

				$a = 1;
				if ( $connect->getStatusCode() === 200) {
					$message	=	t('Thanks, please check your email for confirmation.');
				}

				\Drupal::logger('dotmailer_signup_form')->notice(print_r(json_decode($connect->getBody()),true));

			} catch (RequestException $e) {
				$message	=	t('Please enter a valid email address.');
				\Drupal::logger('dotmailer_signup_form')->notice($e->getMessage());
			}
		}

		$response->addCommand(new HtmlCommand('.form-response', '<p>' . $message . '</p>'));

		return $response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::logger('dotmailer_signup_form')->notice('Hello world submit');
  }

}
