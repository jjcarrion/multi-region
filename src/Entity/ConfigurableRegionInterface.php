<?php

namespace Drupal\multi_region\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Region entities.
 */
interface ConfigurableRegionInterface extends ConfigEntityInterface {

  /**
   * Status of the region.
   *
   * @return bool
   *   Is the region enabled?.
   */
  public function isEnabled();

  /**
   * Get the list of languages.
   *
   * @return array
   *   The list of languages.
   */
  public function getRegionLanguages();

  /**
   * Get the default language.
   *
   * @return string
   *   The default language.
   */
  public function getDefaultLanguage();

}
