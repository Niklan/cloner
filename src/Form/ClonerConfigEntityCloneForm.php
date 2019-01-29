<?php

namespace Drupal\cloner\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ClonerConfigEntityCloneForm.
 *
 * This form called for route "cloner-form".
 *
 * @package Drupal\cloner\Form
 */
class ClonerConfigEntityCloneForm extends ClonerCloneFormBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.cloner.config_entity'),
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cloner_config_entity_clone_form';
  }

}
