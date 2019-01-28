<?php

namespace Drupal\cloner\Plugin\Cloner;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * The base interface for all Clone plugin instances.
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
interface ClonerPluginInterface extends PluginInspectionInterface {

  /**
   * Checks is this plugin need to be activated.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   *
   * @return bool
   *   The status of plugin. TRUE if active, FALSE otherwise.
   */
  public static function isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity);

  /**
   * Build clone form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The form structure.
   */
  public function buildCloneForm(array $form, FormStateInterface $form_state);

  /**
   * Gets the source entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  public function getEntity();

  /**
   * Processing cloning.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity_source
   *   The original entity.
   * @param \Drupal\Core\Entity\EntityInterface $entity_destination
   *   The destination entity.
   * @param array $context
   *   An array with context of clone. Can contain:
   *   - form_state: An instance of $form_state if called from form.
   */
  public function processClone(EntityInterface $entity_source, EntityInterface $entity_destination, array $context);

}
