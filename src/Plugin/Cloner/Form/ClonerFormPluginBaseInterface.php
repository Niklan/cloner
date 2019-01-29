<?php

namespace Drupal\cloner\Plugin\Cloner\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Interface ClonerFormPluginBaseInterface
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
interface ClonerFormPluginBaseInterface {

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
  public function buildForm(array $form, FormStateInterface $form_state);

  /**
   * Validates form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function validateForm(array &$form, FormStateInterface $form_state);

  /**
   * Gets entity which is will be cloned.
   *
   * @return EntityInterface
   *   The cloned entity.
   */
  public function getEntity();

  /**
   * Gets cloner plugin type.
   *
   * @return string
   *   The cloner plugin type.
   */
  public function getClonerPluginType();

  /**
   * Gets cloner plugin id.
   *
   * @return string
   *   The cloner plugin id.
   */
  public function getClonerPluginId();

}