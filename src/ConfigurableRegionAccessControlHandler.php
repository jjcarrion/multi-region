<?php

namespace Drupal\multi_region;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Configurable Region entity.
 */
class ConfigurableRegionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'update':
        if ($account->hasPermission('edit configurable region entities')) {
          return AccessResult::allowed();
        }
        if ($account->hasPermission('edit own configurable region entities')) {
          return AccessResult::allowedIf($this->checkIfUserBelongsToRegion($entity, $account));
        }
        break;

      case 'delete':
        if ($account->hasPermission('delete configurable region entities')) {
          return AccessResult::allowed();
        }
        break;

      case 'global-menu':
        if ($account->hasPermission('edit global menu configurable region entities')) {
          return AccessResult::allowed();
        }
        if ($account->hasPermission('edit own global menu configurable region entities')) {
          return AccessResult::allowedIf($this->checkIfUserBelongsToRegion($entity, $account));
        }
        break;


    }
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add evaluation entities');
  }

  /**
   * Check if the user belongs to the region.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The region entity.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The logged in account.
   *
   * @return bool
   *   The result of the check.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function checkIfUserBelongsToRegion(EntityInterface $entity, AccountInterface $account) {
    /** @var \Drupal\user\Entity\User $user */
    $user = \Drupal::entityTypeManager()->getStorage('user')->load($account->id());
    foreach ($user->allowed_languages->getIterator() as $language) {
      if (in_array($language->entity->id(), $entity->getRegionLanguages(), TRUE)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
