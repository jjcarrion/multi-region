<?php

namespace Drupal\multi_region\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Region entities.
 */
interface ConfigurableRegionInterface extends ConfigEntityInterface {

  /**
   * Get the list of languages.
   *
   * @return array
   *   The list of languages.
   */
  public function getLanguages();

  /**
   * Get the default language.
   *
   * @return string
   *   The default language.
   */
  public function getDefaultLanguage();

}
