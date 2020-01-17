<?php

namespace Drupal\multi_region;

/**
 * Interface RegionInterface.
 */
interface RegionManagerInterface {

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
