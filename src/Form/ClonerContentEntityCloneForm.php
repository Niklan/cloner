<?php

namespace Drupal\cloner\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ClonerContentEntityCloneForm.
 *
 * This form called for route "cloner-form".
 *
 * @package Drupal\cloner\Form
 */
class ClonerContentEntityCloneForm extends ClonerCloneFormBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.cloner.content_entity'),
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cloner_content_entity_clone_form';
  }

}