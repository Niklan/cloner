<?php

namespace Drupal\cloner\Plugin\Cloner;

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

    $this->entityTypeId = $plugin_definition['entity_type_id'];
    $this->entity = $plugin_definition['entity'];
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
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *
   * @return bool
   */
  public static function isApplicable(EntityTypeInterface $entity_type) {
    // By default, every cloner will be available.
    return TRUE;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function cloneForm(array $form, FormStateInterface $form_state) {
    return [];
  }

}
