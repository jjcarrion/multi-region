<?php

namespace Drupal\multi_region\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigurableRegionForm.
 */
class ConfigurableRegionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $configurable_region */
    $configurable_region = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $configurable_region->label(),
      '#description' => $this->t('Label for the Region.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $configurable_region->id(),
      '#machine_name' => [
        'exists' => '\Drupal\multi_region\Entity\ConfigurableRegion::load',
      ],
      '#disabled' => !$configurable_region->isNew(),
    ];

    $form['region_languages'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Region languages'),
      '#target_type' => 'configurable_language',
      '#default_value' => $this->entityTypeManager->getStorage('configurable_language')->loadMultiple($configurable_region->getRegionLanguages()),
      '#validate_reference' => TRUE,
      '#size' => '120',
      '#tags' => TRUE,
      '#maxlength' => '255',
      '#description' => $this->t('Select the languages for this region. You can select more than one, but you have to separate languages with comma.'),
      '#required' => TRUE,
    ];

    $form['default_language'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'configurable_language',
      '#default_value' => $configurable_region->getDefaultLanguage() ? $this->entityTypeManager->getStorage('configurable_language')->load($configurable_region->getDefaultLanguage()) : NULL,
      '#validate_reference' => TRUE,
      '#size' => '120',
      '#tags' => FALSE,
      '#maxlength' => '255',
      '#title' => $this->t('Default language'),
      '#description' => $this->t('Default language for the Region.'),
      '#required' => TRUE,
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => t('Region enabled'),
      '#default_value' => $configurable_region->isEnabled(),
      '#description' => $this->t('Uncheck this checkbox if the region should not be visible.'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!in_array($form_state->getValue('default_language'), array_column($form_state->getValue('region_languages'), 'target_id'), TRUE)) {
      $form_state->setErrorByName('default_language', $this->t('The default language should be one of the languages from the Region languages.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $configurable_region */
    $configurable_region = $this->entity;
    $configurable_region->set('region_languages', array_column($form_state->getValue('region_languages'), 'target_id'));
    $configurable_region->set('default_language', $form_state->getValue('default_language'));
    $status = $configurable_region->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('Created the %label Region.', [
        '%label' => $configurable_region->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('Saved the %label Region.', [
        '%label' => $configurable_region->label(),
      ]));
    }
    $form_state->setRedirectUrl($configurable_region->toUrl('collection'));
  }

}
