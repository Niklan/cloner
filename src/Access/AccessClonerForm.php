<?php

namespace Drupal\cloner\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;

/**
 * Checks access for cloner form.
 */
final class AccessClonerForm implements AccessInterface {

  /**
   * AccessClonerForm constructor.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   */
  public function __construct(
    protected CurrentRouteMatch $currentRouteMatch
  ) {}

  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account): AccessResultInterface {
    $entity_type_id = $this
      ->currentRouteMatch
      ->getRouteObject()
      ->getOption('_cloner_entity_type_id');

    $permissions = [
      'access all entity cloner',
      "access $entity_type_id cloner",
    ];

    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR');
  }

}
