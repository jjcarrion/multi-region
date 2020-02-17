<?php

namespace Drupal\multi_region;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of Region entities.
 */
class ConfigurableRegionListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Region');
    $header['id'] = $this->t('Machine name');
    $header['status'] = $this->t('Status');
    $header['region_languages'] = $this->t('Region Languages');
    $header['default_language'] = $this->t('Default Language');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['status'] = $entity->isEnabled() ? 'Enabled' : 'Not enabled';
    $row['region_languages'] = implode(' | ', $entity->getRegionLanguages());
    $row['default_language'] = $entity->getDefaultLanguage();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);
    if ($entity->access('global-menu')) {
      $operations['global_menu'] = [
        'title' => $this->t('Global menu'),
        'weight' => 10,
        'url' => $this->ensureDestination(Url::fromRoute('entity.configurable_region.global_menu_form', ['configurable_region' => $entity->id()])),
      ];
    }
    return $operations;
  }

}
