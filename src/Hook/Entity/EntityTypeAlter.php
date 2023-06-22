<?php declare(strict_types = 1);

namespace Drupal\cloner\Hook\Entity;

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Provides entity type alter hook.
 *
 * @see cloner_entity_type_alter()
 */
final class EntityTypeAlter {

  /**
   * Implements hook_entity_type_alter().
   */
  public function __invoke(array &$entity_types): void {
    foreach ($entity_types as $entity_type_id => $entity_type) {
      \assert($entity_type instanceof EntityTypeInterface);
      $entity_type->setLinkTemplate('cloner-form', "/cloner/$entity_type_id/{{$entity_type_id}}");
    }
  }

}
