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
      '#description' => $this->t("Label for the Region."),
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

    $form['languages'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Languages'),
      '#target_type' => 'configurable_language',
//      '#default_value' => $configurable_region->getLanguages(),
      '#validate_reference' => TRUE,
      '#size' => '60',
      '#maxlength' => '60',
      '#description' => $this->t('Select the languages for this region.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $configurable_region = $this->entity;
    $status = $configurable_region->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Region.', [
          '%label' => $configurable_region->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Region.', [
          '%label' => $configurable_region->label(),
        ]));
    }
    $form_state->setRedirectUrl($configurable_region->toUrl('collection'));
  }

}
