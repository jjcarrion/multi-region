<?php

namespace Drupal\multi_region\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuTreeParameters;

/**
 * Class ConfigurableRegionForm.
 */
class ConfigurableRegionGlobalMenuForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $configurable_region */
    $configurable_region = $this->entity;

    $menu_link_tree_service = \Drupal::service('menu.link_tree');
    $parameters = new MenuTreeParameters();
    $tree = $menu_link_tree_service->load('main', $parameters);
    $options = [];

    $global_menu_config = $this->config('afp_menu.global_menu');
    $overrides = $global_menu_config->get('overrides');
    /** @var \Drupal\Core\Menu\MenuLinkTreeElement $menu_item */
    foreach ($tree as $menu_item) {
      if ($menu_item->link->isEnabled()) {
        $title = array_key_exists($menu_item->link->getPluginId(), $overrides) ? $overrides[$menu_item->link->getPluginId()] : $menu_item->link->getTitle();
        $options[$menu_item->link->getPluginId()] = $title;
      }
    }
    $form['global_menu'] = [
      '#type' => 'checkboxes',
      '#options' => $options,
      '#title' => $this->t('Select the menu links that you want to show in the Global content menu'),
      '#default_value' => $configurable_region->get('global_menu') ?: [],
    ];
    return $form;
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
    $configurable_region->set('global_menu', array_values(array_filter($form_state->getValue('global_menu'))));

    $configurable_region->save();

    $this->messenger()->addMessage($this->t('Saved the %label Region.', [
      '%label' => $configurable_region->label(),
    ]));

    $form_state->setRedirectUrl($configurable_region->toUrl('collection'));
  }

}
