<?php

namespace Drupal\dotmailer_signup_form_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class DotmailerAdminForm extends ConfigFormBase {

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'dotmailer_form';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames() {
		return [
			'dotmailer.adminsettings',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$config = $this->config('dotmailer.adminsettings');

		$form['username']            = array(
			'#type'                    => 'textfield',
			'#required' 							 => TRUE,
			'#title'                   => $this->t('Dotmailer API Username'),
			'#description'             => $this->t('The username used to connect to the dotmailer API.'),
			'#default_value'           => $config->get('username'),
		);

		$form['password']            = array(
			'#type'                    => 'textfield',
			'#required' 							 => TRUE,
			'#title'                   => $this->t('Dotmailer API Password'),
			'#description'             => $this->t('The password used to connect to the dotmailer API.'),
			'#default_value'           => $config->get('password'),
		);

		$form['addressbook']         = array(
			'#type'                    => 'textfield',
			'#required' 							 => TRUE,
			'#title'                   => $this->t('Dotmailer Address Book'),
			'#description'             => $this->t('Enter the ID of the address book to contect too.'),
			'#default_value'           => $config->get('addressbook'),
		);

    $form['api_endpoint']         = array(
      '#type'                    => 'textfield',
      '#required' 							 => TRUE,
      '#title'                   => $this->t('Dotmailer API Endpoint'),
      '#description'             => $this->t('Enter the API Endpoint'),
      '#default_value'           => $config->get('api_endpoint'),
    );

    return parent::buildForm($form, $form_state);
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
		parent::submitForm($form, $form_state);

		$this->config('dotmailer.adminsettings')
			->set('username', $form_state->getValue('username'))
			->save();

		$this->config('dotmailer.adminsettings')
			->set('password', $form_state->getValue('password'))
			->save();

		$this->config('dotmailer.adminsettings')
			->set('addressbook', $form_state->getValue('addressbook'))
			->save();

    $this->config('dotmailer.adminsettings')
      ->set('api_endpoint', $form_state->getValue('api_endpoint'))
      ->save();
	}
}
