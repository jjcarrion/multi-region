<?php

namespace Drupal\multi_region;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

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
    $row['region_languages'] = implode(' | ', $entity->getRegionLanguages());
    $row['default_language'] = $entity->getDefaultLanguage();
    return $row + parent::buildRow($entity);
  }

}
