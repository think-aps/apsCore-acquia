<?php

namespace Drupal\aps_segment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Edit aps segment Settings form.
 */
class APSSegmentAdminSettingsForm extends ConfigFormBase {

  	/**
   	* {@inheritdoc}
   	*/
  	public function getFormId() {
    	return 'aps_segment_admin_settings_form';
  	}

  	/**
   	* {@inheritdoc}
   	*/
  	public function submitForm(array &$form, FormStateInterface $form_state) {
    	$this->config('aps_segment.settings')
			->set('aps_segment_job_number', $form_state->getValue('aps_segment_job_number'))
			->set('aps_segment_api_auth', $form_state->getValue('aps_segment_api_auth'))
			->set('aps_segment_write_key', $form_state->getValue('aps_segment_write_key'))
			->set('aps_segment_endpoint', $form_state->getValue('aps_segment_endpoint'))
			->save();

    	parent::submitForm($form, $form_state);
  	}

  	/**
   	* {@inheritdoc}
   	*/
  	protected function getEditableConfigNames() {
    	return [
      		'aps_segment.settings',
    	];
  	}

  	/**
   	* {@inheritdoc}
   	*/
  	public function buildForm(array $form, FormStateInterface $form_state) {
    	$config = $this->config('aps_segment.settings');
	  
	  	$form['site'] = array(
	  		'#type' => 'fieldset',
			'#title' => t('Site Information'),
			'#collapsible' => FALSE,
      		'#collapsed' => FALSE,
	  	);
		$form['site']['aps_segment_job_number'] = array(
      		'#title' => t('Job Number'),
      		'#type' => 'textfield',
      		'#default_value' => $config->get('aps_segment_job_number'),
      		'#size' => 64,
      		'#maxlength' => 20,
      		'#required' => TRUE,
    	);

    	$form['account'] = array(
      		'#type' => 'fieldset',
      		'#title' => t('Basic Settings'),
      		'#collapsible' => FALSE,
      		'#collapsed' => FALSE,
    	);
    	$form['account']['aps_segment_api_auth'] = array(
      		'#title' => t('API Authorization'),
      		'#type' => 'textfield',
      		'#default_value' => $config->get('aps_segment_api_auth'),
      		'#size' => 200,
      		'#maxlength' => 200,
      		'#required' => TRUE,
    	);
	  	$form['account']['aps_segment_write_key'] = array(
      		'#title' => t('Write Key'),
      		'#type' => 'textfield',
      		'#default_value' => $config->get('aps_segment_write_key'),
      		'#size' => 200,
      		'#maxlength' => 200,
      		'#required' => TRUE,
    	);  
		$form['account']['aps_segment_endpoint'] = array(
      		'#title' => t('Authorization endpoint'),
      		'#type' => 'textfield',
      		'#default_value' => $config->get('aps_segment_endpoint'),
      		'#size' => 200,
      		'#maxlength' => 200,
      		'#required' => TRUE,
    	);

    	return parent::buildForm($form, $form_state);
  	}
}
