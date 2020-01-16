<?php

namespace Drupal\multi_region\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

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
 *       "delete" = "Drupal\multi_region\Form\ConfigurableRegionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\multi_region\ConfigurableRegionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "configurable_region",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/regional/region/{configurable_region}",
 *     "add-form" = "/admin/config/regional/region/add",
 *     "edit-form" = "/admin/config/regional/region/{configurable_region}/edit",
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
   * The languages for that region.
   *
   * @var string
   */
  protected $languages;

  /**
   * {@inheritDoc}
   */
  public function getLanguages() {
    return $this->languages;
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
