<?php

namespace Drupal\user_config\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UserConfigForm.
 */
class UserConfigForm extends EntityForm {

	/**
	 * {@inheritdoc}
	 */
	public function form(array $form, FormStateInterface $form_state) {
		$form = parent::form($form, $form_state);

		$user_config = $this->entity;
		// $form['label'] = [
		//   '#type' => 'textfield',
		//   '#title' => $this->t('Label'),
		//   '#maxlength' => 255,
			//   '#default_value' => $user_config->label(),
		//   '#description' => $this->t("Label for the User config."),
		//   '#required' => TRUE,
		// ];

		$form['id'] = [
			'#type' => 'machine_name',
			'#default_value' => !empty($user_config->id()) ? $user_config->id() : 'user_config_'.$user_config->uid,
			'#machine_name' => [
				'exists' => '\Drupal\user_config\Entity\UserConfig::load',
			],
			'#access' => FALSE
			// '#disabled' => !$user_config->isNew(),
		];

		/* You will need additional form elements for your custom properties. */

		$form['uid'] = [
			'#type' => 'hidden',
			'#title' => $this->t('User ID'),
			'#default_value' => $user_config->uid,
			'#description' => $this->t("User Id."),
			'#required' => TRUE,
		];

		$form['country'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Country'),
			'#maxlength' => 255,
			'#default_value' => $user_config->country,
			'#description' => $this->t("User's Country."),
		];

		$form['city'] = [
			'#type' => 'textfield',
			'#title' => $this->t('City'),
			'#maxlength' => 255,
			'#default_value' => $user_config->city,
			'#description' => $this->t("User's City."),
		];

		$timezone_array = [
			'America/Chicago' => 'America/Chicago',
			'America/New_York' => 'America/New_York',
			'Asia/Tokyo' => 'Asia/Tokyo',
			'Asia/Dubai' => 'Asia/Dubai',
			'Asia/Kolkata' => 'Asia/Kolkata',
			'Europe/Amsterdam' => 'Europe/Amsterdam',
			'Europe/Oslo' => 'Europe/Oslo',
			'Europe/London' => 'Europe/London'
		];
		$form['timezone'] = [
			'#type' => 'select',
			'#options' => $timezone_array,
			'#empty_option' => $this->t('Select timezone'),
			'#title' => $this->t('Timezone'),
			'#default_value' => $user_config->timezone,
			'#description' => $this->t("Label for the User config."),
		];

		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(array $form, FormStateInterface $form_state) { 
		$user_config = $this->entity;
		$user_config->set('uid', $form_state->getValue('uid'));
		$user_config->set('country', $form_state->getValue('country'));
		$user_config->set('city', $form_state->getValue('city'));
		$user_config->set('timezone', $form_state->getValue('timezone'));
		$status = $user_config->save();
		switch ($status) {
			case SAVED_NEW:
				$this->messenger()->addMessage($this->t('Created the %label User config.', [
				'%label' => $user_config->label(),
				]));
				break;

			default:
				$this->messenger()->addMessage($this->t('Saved the %label User config.', [
				'%label' => $user_config->label(),
				]));
		}
		$form_state->setRedirect('entity.user.collection');
	}

}
