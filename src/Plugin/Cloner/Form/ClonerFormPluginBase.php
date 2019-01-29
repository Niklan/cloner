<?php

namespace Drupal\cloner\Plugin\Cloner\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ClonerFormPluginBase
 *
 * @package Drupal\cloner\Plugin\Cloner
 */
abstract class ClonerFormPluginBase extends PluginBase implements ClonerFormPluginBaseInterface, ContainerFactoryPluginInterface {

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
    // By default, clone forms are available.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getClonerPluginType() {
    return $this->pluginDefinition['cloner_plugin_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getClonerPluginId() {
    return $this->pluginDefinition['cloner_plugin_id'];
  }

}
