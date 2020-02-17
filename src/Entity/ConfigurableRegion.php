<?php

namespace Drupal\multi_region\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the Region entity.
 *
 * @ConfigEntityType(
 *   id = "configurable_region",
 *   label = @Translation("Region"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\multi_region\ConfigurableRegionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\multi_region\Form\ConfigurableRegionForm",
 *       "edit" = "Drupal\multi_region\Form\ConfigurableRegionForm",
 *       "global-menu" = "Drupal\multi_region\Form\ConfigurableRegionGlobalMenuForm",
 *       "delete" = "Drupal\multi_region\Form\ConfigurableRegionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\multi_region\ConfigurableRegionHtmlRouteProvider",
 *     },
 *    "access" = "Drupal\multi_region\ConfigurableRegionAccessControlHandler",
 *   },
 *   config_prefix = "configurable_region",
 *   admin_permission = "administer configurable region",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/regional/region/{configurable_region}",
 *     "add-form" = "/admin/config/regional/region/add",
 *     "edit-form" = "/admin/config/regional/region/{configurable_region}/edit",
 *     "global-menu-form" = "/admin/config/regional/region/{configurable_region}/global-menu",
 *     "delete-form" = "/admin/config/regional/region/{configurable_region}/delete",
 *     "collection" = "/admin/config/regional/region"
 *   }
 * )
 */
class ConfigurableRegion extends ConfigEntityBase implements ConfigurableRegionInterface {

  /**
   * The region ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The region label.
   *
   * @var string
   */
  protected $label;

  /**
   * The status of the region.
   *
   * @var bool
   */
  protected $status;

  /**
   * {@inheritDoc}
   */
  public function isEnabled() {
    return $this->status;
  }

  /**
   * The languages for that region.
   *
   * @var array
   */
  protected $region_languages = [];

  /**
   * {@inheritDoc}
   */
  public function getRegionLanguages() {
    return $this->region_languages;
  }

  /**
   * The default language for that region.
   *
   * @var string
   */
  protected $default_language;

  /**
   * {@inheritDoc}
   */
  public function getDefaultLanguage() {
    return $this->default_language;
  }

}
