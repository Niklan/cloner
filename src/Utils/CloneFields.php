<?php

namespace Drupal\cloner\Utils;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Utility class with helpers for cloning fields.
 *
 * @package Drupal\cloner\Utils
 */
class CloneFields implements CloneFieldsInterface {

  /**
   * {@inheritdoc}
   */
  public static function getFieldDefinition(EntityInterface $entity, $field_name) {
    if ($entity instanceof FieldableEntityInterface) {
      $field_definitions = $entity->getFieldDefinitions();

      if (isset($field_definitions[$field_name])) {
        return $field_definitions[$field_name];
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function isFieldClonable(FieldDefinitionInterface $field_definition) {
    $clonable_field_types = [
      'entity_reference',
      'entity_reference_revisions',
    ];

    return in_array($field_definition->getType(), $clonable_field_types, TRUE);
  }

}
