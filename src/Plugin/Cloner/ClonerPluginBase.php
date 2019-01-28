<?php

namespace Drupal\cloner\Plugin\Cloner;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base for all Cloner plugins.
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
abstract class ClonerPluginBase extends PluginBase implements ClonerPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The entity type id to be cloned.
   *
   * @var string
   */
  protected $entityTypeId;

  /**
   * The entity object which need to be cloned.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * ClonerPluginBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition) {

    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeId = $configuration['entity_type_id'];
    $this->entity = $configuration['entity'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(EntityTypeInterface $entity_type, EntityInterface $entity) {
    // By default, every cloner will be available.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function buildCloneForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function processClone(EntityInterface $entity_source, EntityInterface $entity_destination, array $context = []) {

  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    return $this->entity;
  }

}
