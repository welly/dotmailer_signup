<?php

namespace Drupal\dotmailer_signup_form_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a "Example ajax submit form block".
 *
 * @Block(
 *   id = "dotmailer_signup_form",
 *   admin_label = @Translation("Sign-up Form")
 * )
 */
class DotmailerSignupFormBlock extends BlockBase {

	/**
	* {@inheritdoc}
	*/
	public function blockForm($form, FormStateInterface $form_state) {
		global $base_url;

		$form['markup']                    = array(
			'#type'                          => 'markup',
			'#markup'                        => t('When using this form <a href="@url" target="_blank">create a Dotmailer API user</a> and update <a href="@url2" target="_blank">API settings</a>.', array(
					'@url' => 'https://support.dotmailer.com/hc/en-gb/articles/115001718730-How-do-I-create-an-API-user',
					'@url2' => $base_url . '/admin/config/dotmailer/adminsettings'
				)
			)
		);

		$form['dotmailer_description'] 		 = array(
			'#type'                          => 'textarea',
			'#title'                         => $this->t('Description'),
			'#description'                   => $this->t('Enter a description for the form.'),
			'#default_value'                 => isset($this->configuration['dotmailer_description']) ? $this->configuration['dotmailer_description'] : '',
		);

		$form['dotmailer_show_name']       = array(
			'#type'                          => 'checkbox',
			'#title'                         => $this->t('Show the name field.'),
			'#default_value'                 => isset($this->configuration['dotmailer_show_name']) ? $this->configuration['dotmailer_show_name'] : FALSE,
		);

		return $form;
	}

	/**
	* {@inheritdoc}
	*/
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->configuration['dotmailer_username']    = $form_state->getValue('dotmailer_username');
		$this->configuration['dotmailer_password']    = $form_state->getValue('dotmailer_password');
		$this->configuration['dotmailer_show_name']   = $form_state->getValue('dotmailer_show_name');
		$this->configuration['dotmailer_description'] = $form_state->getValue('dotmailer_description');
	}

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		$form = \Drupal::formBuilder()->getForm('\Drupal\dotmailer_signup_form_block\Form\DotmailerSignupForm');

		if (!$this->configuration['dotmailer_show_name']) {
			unset($form['name']);
		}

		if ($this->configuration['dotmailer_description'] !=='') {
			$form['#markup'] = '<div class="form-description">' . $this->configuration['dotmailer_description'] . '</div>';
		}

		return $form;
	}
}
