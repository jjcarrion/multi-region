<?php

namespace Drupal\multi_region;

use Drupal\afp_catalog\CatalogQueryManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Url;
use Drupal\multi_region\Entity\ConfigurableRegion;

/**
 * Class Region.
 */
class RegionManager implements RegionManagerInterface {

  /**
   * EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * LanguageManagerInterface definition.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  private $languageManager;

  /**
   * EntityRepositoryInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  private $entityRepository;

  /**
   * Drupal\Core\Routing\CurrentRouteMatch definition.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  private $currentRouteMatch;

  /**
   * Drupal\Core\Path\PathMatcherInterface definition.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  private $pathMatcher;

  /**
   * Constructs a new Region object.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager interface.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entityRepository
   *   The entity repository.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   * @param \Drupal\Core\Path\PathMatcherInterface $pathMatcher
   *   The path matcher.
   */
  public function __construct(LanguageManagerInterface $languageManager, EntityTypeManagerInterface $entityTypeManager, EntityRepositoryInterface $entityRepository, CurrentRouteMatch $currentRouteMatch, PathMatcherInterface $pathMatcher) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageManager = $languageManager;
    $this->entityRepository = $entityRepository;
    $this->currentRouteMatch = $currentRouteMatch;
    $this->pathMatcher = $pathMatcher;
  }

  /**
   * Get the current region config entity.
   *
   * @return \Drupal\multi_region\Entity\ConfigurableRegion|null
   *   The region config entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getCurrentRegion() {
    $selected_region = NULL;
    $current_language = $this->languageManager->getCurrentLanguage();
    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $region */
    foreach ($this->getRegions() as $region) {
      foreach ($region->getRegionLanguages() as $language) {
        if ($current_language->getId() === $language) {
          $selected_region = $region;
          break;
        }
      }
    }
    return $selected_region;
  }

  /**
   * Get current region Id.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getCurrentRegionId(): string {
    if ($this->getCurrentRegion() instanceof ConfigurableRegion) {
      return $this->getCurrentRegion()->id();
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getLanguageLinksForCurrentRegion() {
    $configurable_region = $this->getCurrentRegion();
    $links = [];
    if (!$configurable_region) {
      return $links;
    }

    $current_language = [];
    foreach ($configurable_region->getRegionLanguages() as $langcode) {
      $language = $this->languageManager->getLanguage($langcode);
      if (!$language) {
        return $links;
      }
      if ($language->getId() === $this->languageManager->getCurrentLanguage()
          ->getId()) {
        $current_language[$language->getId()] = $language->getName();
      }
      else {
        $route_name = '<front>';
        if ($this->currentRouteMatch->getRouteName() === 'entity.node.canonical') {
          /** @var \Drupal\node\Entity\Node $node */
          $node = $this->currentRouteMatch->getParameter('node');
          if ($node && $node->hasTranslation($language->getId())) {
            $route_name = '<current>';
            parse_str(\Drupal::requestStack()
              ->getCurrentRequest()
              ->getQueryString(), $query);
            $options['query'] = $query;
          }
        }
        $options['language'] = $language;
        $links[$language->getId()] = Link::fromTextAndUrl($language->getName(), Url::fromRoute($route_name, [], $options));
      }
    }
    return array_merge($current_language, $links);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function isRegionReady(): bool {
    return !empty($this->getLanguageLinksForCurrentRegion());
  }

  /**
   * Get regions.
   *
   * @return array
   *   The region config entities list.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getRegions(): array {
    $configurable_regions = $this->entityTypeManager->getStorage('configurable_region')
      ->loadMultiple();
    $regions = [];
    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $configurable_region */
    foreach ($configurable_regions as $configurable_region) {
      if ($configurable_region->isEnabled()) {
        $regions[] = $configurable_region;
      }
    }
    return $regions;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getRegionLinks() {
    $links = [];
    $current_region = [];
    /** @var \Drupal\multi_region\Entity\ConfigurableRegion $region */
    foreach ($this->getRegions() as $region) {
      $region = $this->entityRepository->getTranslationFromContext($region);
      if ($region->id() === $this->getCurrentRegionId()) {
        $current_region[$region->id()] = $region->label();
      }
      else {
        $options['language'] = $this->languageManager->getLanguage($region->getDefaultLanguage());
        $links[$region->id()] = Link::fromTextAndUrl($region->label(), Url::fromRoute('<front>', [], $options));
      }
    }
    return array_merge($current_region, $links);
  }

}
