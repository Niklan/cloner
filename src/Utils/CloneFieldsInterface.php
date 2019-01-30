<?php

namespace Drupal\cloner\Utils;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Utility class with helpers for cloning fields.
 *
 * @package Drupal\cloner\Utils
 */
interface CloneFieldsInterface {

  /**
   * Gets field definition.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to check fields.
   * @param string $field_name
   *   The field name.
   *
   * @return bool|\Drupal\Core\Field\FieldDefinitionInterface
   *   Return field definition if found, FALSE otherwise.
   */
  public static function getFieldDefinition(EntityInterface $entity, $field_name);

  /**
   * Check is field is clonable.
   *
   * By clonable means that field contains references to another entities. In
   * some cases you need to create cloned entities for referenced entities, such
   * as paragraphs entities.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return bool
   *   TRUE is clonable, FALSE otherwise.
   */
  public static function isFieldClonable(FieldDefinitionInterface $field_definition);

}
