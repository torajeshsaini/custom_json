<?php

namespace Drupal\custom_json\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $site_config = $this->config('system.site');
    $site_api_key = $site_config->get('siteapikey');
    $form = parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_api_key ?: 'No API Key yet',
      '#description' => t("Set value of Site API key"),
    ];

    if ($site_api_key) {
      $form['actions']['submit']['#value'] = t('Update Configuration');
    }

    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $api_key = trim($form_state->getValue('siteapikey'));

    if ($api_key == 'No API Key yet') {
      $api_key = '';
    }

    $config = $this->config('system.site');
    $config->set('siteapikey', $api_key)
      ->save();
    if (!empty($api_key)) {
      $messageString = "Site API key have been updated with: $api_key";
      $this->messenger()->addMessage($messageString);
    }
    parent::submitForm($form, $form_state);
  }
}
