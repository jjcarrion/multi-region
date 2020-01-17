<?php

namespace Drupal\multi_region\Plugin\Block;

use Drupal\multi_region\RegionManagerInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RegionLanguageSwitcher' block.
 *
 * @Block(
 *  id = "region_language_switcher",
 *  admin_label = @Translation("Region language switcher"),
 * )
 */
class RegionLanguageSwitcherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Language\LanguageManagerInterface definition.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  private $languageManager;

  /**
   * Drupal\multi_region\RegionManagerInterface definition.
   *
   * @var \Drupal\multi_region\RegionManagerInterface
   */
  private $regionManager;

  /**
   * Drupal\Core\Routing\CurrentRouteMatch definition.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  private $currentRouteMatch;

  /**
   * Constructs a new RegionLanguageSwitcherBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager.
   * @param \Drupal\multi_region\RegionManagerInterface $regionManager
   *   The region manager.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LanguageManagerInterface $languageManager, RegionManagerInterface $regionManager, CurrentRouteMatch $currentRouteMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->languageManager = $languageManager;
    $this->regionManager = $regionManager;
    $this->currentRouteMatch = $currentRouteMatch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager'),
      $container->get('multi_region.region_manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $access = $this->languageManager->isMultilingual() && $this->regionManager->isRegionReady() ? AccessResult::allowed() : AccessResult::forbidden();
    $access->addCacheContexts(['languages:language_interface']);
    $access->addCacheTags(['config:configurable_language_list']);
    $access->addCacheTags(['config:configurable_region_list']);
    $access->setCacheMaxAge(Cache::PERMANENT);
    return $access;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      'region' => [
        '#theme' => 'item_list',
        '#list_type' => 'ul',
        '#items' => $this->regionManager->getRegionLinks(),
        '#cache' => [
          'contexts' => $this->getCacheContexts(),
          'tags' => $this->getCacheTags(),
        ],
      ],
      'languages' => [
        '#theme' => 'item_list',
        '#list_type' => 'ul',
        '#items' => $this->regionManager->getLanguageLinksForCurrentRegion(),
      ],
      '#cache' => [
        'contexts' => $this->getCacheContexts(),
        'tags' => $this->getCacheTags(),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $tags = [
      'config:configurable_language_list',
      'config:configurable_region_list',
    ];
    /** @var \Drupal\node\Entity\Node $node */
    if ($this->currentRouteMatch->getRouteName() === 'entity.node.canonical' && $node = $this->currentRouteMatch->getParameter('node')) {
      $tags = Cache::mergeTags($tags, $node->getCacheTags());
    }
    return Cache::mergeTags($tags, parent::getCacheTags());
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = [
      'languages:language_interface',
    ];
    return Cache::mergeContexts($contexts, parent::getCacheContexts());
  }

}
