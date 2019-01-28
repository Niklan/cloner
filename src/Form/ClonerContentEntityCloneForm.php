<?php

namespace Drupal\cloner\Form;

use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ClonerContentEntityCloneForm.
 *
 * This form called for route "cloner-form".
 *
 * @package Drupal\cloner\Form
 */
class ClonerContentEntityCloneForm extends FormBase {

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
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Then entity type.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $entityType;

  /**
   * ClonerCloneForm constructor.
   *
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $cloner_plugin_manager
   *   The cloner plugin manager.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    ClonerPluginManager $cloner_plugin_manager,
    CurrentRouteMatch $current_route_match,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->clonerPluginManager = $cloner_plugin_manager;
    $this->entityTypeManager = $entity_type_manager;

    // Trying to find entity object.
    $entity_type_id = $current_route_match->getRouteObject()
      ->getOption('_cloner_entity_type_id');
    $this->entity = $current_route_match->getParameter($entity_type_id);
    $this->entityType = $entity_type_manager->getDefinition($entity_type_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    // @todo handle config entity.
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

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $applicable_plugins = $this->clonerPluginManager->isApplicable($this->entityType, $this->entity);
    if (empty($applicable_plugins)) {
      // @todo throw 404 or 403 if there is no suitable plugin for this particular
      // entity type \ bundle combination.
    }

    $cloner_winner = array_shift($applicable_plugins);

    // Create instance of the plugin.
    /** @var \Drupal\cloner\Plugin\Cloner\ClonerPluginInterface $cloner_instance */
    $cloner_instance = $this->clonerPluginManager->createInstance($cloner_winner['id'], [
      'entity_type_id' => $this->entityType->id(),
      'entity' => $this->entity,
    ]);

    // Get plugin form.
    $form = $cloner_instance->buildCloneForm($form, $form_state);

    // @todo maybe pass it to submit somehow.
    $form['cloner_plugin_id'] = [
      '#type' => 'hidden',
      '#value' => $cloner_winner['id'],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Clone'),
      '#button_type' => 'primary',
    ];

    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => ['::cancelForm'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cloner_plugin_id = $form_state->getValue('cloner_plugin_id');
    /** @var \Drupal\cloner\Plugin\Cloner\ClonerPluginInterface $cloner_instance */
    $cloner_instance = $this->clonerPluginManager->createInstance($cloner_plugin_id, [
      'entity_type_id' => $this->entityType->id(),
      'entity' => $this->entity,
    ]);

    // Begin clone.
    $entity_cloned = $this->entity->createDuplicate();
    // Send form state in context. Allow to use plugins without forms.
    $cloner_instance->processClone($this->entity, $entity_cloned, [
      'form_state' => $form_state,
    ]);
    // Save after processing.
    $entity_cloned->save();

    $form_state->setRedirect(
      $entity_cloned->toUrl()->getRouteName(),
      $entity_cloned->toUrl()->getRouteParameters()
    );
  }

  /**
   * Cancel cloning operation.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function cancelForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->entity;

    if ($entity && $entity->hasLinkTemplate('canonical')) {
      $form_state->setRedirect(
        $entity->toUrl()->getRouteName(),
        $entity->toUrl()->getRouteParameters());
    }
    else {
      $form_state->setRedirect('<front>');
    }
  }

}
