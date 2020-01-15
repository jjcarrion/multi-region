<?php

namespace Drupal\multi_region\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RegionForm.
 */
class RegionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $region = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $region->label(),
      '#description' => $this->t("Label for the Region."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $region->id(),
      '#machine_name' => [
        'exists' => '\Drupal\multi_region\Entity\Region::load',
      ],
      '#disabled' => !$region->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $region = $this->entity;
    $status = $region->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Region.', [
          '%label' => $region->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Region.', [
          '%label' => $region->label(),
        ]));
    }
    $form_state->setRedirectUrl($region->toUrl('collection'));
  }

}
