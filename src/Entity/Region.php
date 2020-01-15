<?php

namespace Drupal\multi_region\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Region entity.
 *
 * @ConfigEntityType(
 *   id = "region",
 *   label = @Translation("Region"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\multi_region\RegionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\multi_region\Form\RegionForm",
 *       "edit" = "Drupal\multi_region\Form\RegionForm",
 *       "delete" = "Drupal\multi_region\Form\RegionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\multi_region\RegionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "region",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/regional/region/{region}",
 *     "add-form" = "/admin/config/regional/region/add",
 *     "edit-form" = "/admin/config/regional/region/{region}/edit",
 *     "delete-form" = "/admin/config/regional/region/{region}/delete",
 *     "collection" = "/admin/config/regional/region/region"
 *   }
 * )
 */
class Region extends ConfigEntityBase implements RegionInterface {

  /**
   * The Region ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Region label.
   *
   * @var string
   */
  protected $label;

}
