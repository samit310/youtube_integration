<?php

namespace Drupal\youtube_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class YoutubeSettings.
 */
class YoutubeSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'youtube_integration.youtubesettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'youtube_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('youtube_integration.youtubesettings');
    $form['youtube_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Youtube API URL'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('youtube_api_url') ?: 'https://www.googleapis.com/youtube/v3/channels?',
    ];
    $form['google_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google API key'),
      '#description' => $this->t('Google API key to access youtube APIs, Check ') . '<a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>',
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('google_api_key'),
    ];
    $videos_range = range(1, 50);
    $form['no_of_videos'] = [
      '#type' => 'select',
      '#title' => $this->t('MAX No of videos.'),
      '#options' => array_combine($videos_range, $videos_range),
      '#description' => $this->t(''),
      '#default_value' => $config->get('no_of_videos') ?: 10,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('youtube_integration.youtubesettings')
        ->set('youtube_api_url', $form_state->getValue('youtube_api_url'))
        ->set('google_api_key', $form_state->getValue('google_api_key'))
        ->set('no_of_videos', $form_state->getValue('no_of_videos'))
        ->save();
  }

}
