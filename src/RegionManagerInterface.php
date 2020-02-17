<?php

namespace Drupal\multi_region;

/**
 * Interface RegionInterface.
 */
interface RegionManagerInterface {

  /**
   * Get the current region config entity.
   *
   * @return \Drupal\multi_region\Entity\ConfigurableRegion|null
   *   The region config entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getCurrentRegion();

  /**
   * Get language links for current region.
   *
   * @return object
   *   The language links.
   */
  public function getLanguageLinksForCurrentRegion();

  /**
   * Check if we have a region with languages.
   *
   * @return bool
   *   Minimum region set up.
   */
  public function isRegionReady();

  /**
   * Get region links.
   *
   * @return array
   *   The region links.
   */
  public function getRegionLinks();

}
