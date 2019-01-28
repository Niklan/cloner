<?php

namespace Drupal\cloner\Form;

use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ClonerClonerForm.
 *
 * This form called for route "cloner-form".
 *
 * @todo split form to different type. For content and config.
 *
 * @package Drupal\cloner\Form
 */
class ClonerCloneForm extends FormBase {

  /**
   * The cloner plugin manager.
   *
   * @var \Drupal\cloner\Plugin\ClonerPluginManager
   */
  protected $clonerPluginManager;

  /**
   * The entity to be cloned.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * ClonerCloneForm constructor.
   *
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $cloner_plugin_manager
   *   The cloner plugin manager.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match.
   */
  public function __construct(ClonerPluginManager $cloner_plugin_manager, CurrentRouteMatch $current_route_match) {
    $this->clonerPluginManager = $cloner_plugin_manager;

    // Trying to find entity object.
    $entity_type_id = $current_route_match->getRouteObject()
      ->getOption('_cloner_entity_type_id');
    $this->entity = $current_route_match->getParameter($entity_type_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    // @todo handle config entity.
      $container->get('plugin.manager.cloner.content_entity'),
      $container->get('current_route_match')
    );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'cloner_clone_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // @todo throw 404 or 403 if there is no suitable plugin for this particular
    // entity type \ bundle combination.
    $content_entity_definitions = $this->clonerPluginManager->getDefinitions();
    $applicable_plugins = [];

    foreach ($content_entity_definitions as $plugin_id => $plugin_definition) {
      $callback = [
        $plugin_definition['class'],
        'isApplicable',
      ];

      // @todo pass entity definition and think about entity type...
      if (forward_static_call($callback, $this->entity)) {
        $applicable_plugins[] = $plugin_definition;
      }
    }
    dump($applicable_plugins);
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}