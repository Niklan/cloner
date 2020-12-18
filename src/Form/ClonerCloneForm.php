<?php

namespace Drupal\cloner\Form;

use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ClonerCloneFormBase.
 *
 * The base form for all other clone base.
 *
 * @package Drupal\cloner\Form
 */
class ClonerCloneForm extends FormBase {

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
   * The plugin manager for cloner form plugins.
   *
   * @var \Drupal\cloner\Plugin\ClonerPluginManager
   */
  protected $clonerFormPluginManager;

  /**
   * The plugin manager for content entity plugins.
   *
   * @var \Drupal\cloner\Plugin\ClonerPluginManager
   */
  protected $clonerContentEntityPluginManager;

  /**
   * The plugin manager for config entity plugins.
   *
   * @var \Drupal\cloner\Plugin\ClonerPluginManager
   */
  protected $clonerConfigEntityPluginManager;

  /**
   * ClonerCloneForm constructor.
   *
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $cloner_form_plugin_manager
   *   The plugin manager for cloner form plugins.
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $cloner_content_entity_plugin_manager
   *   The plugin manager for content entity plugins.
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $cloner_config_entity_plugin_manager
   *   The plugin manager for config entity plugins.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    ClonerPluginManager $cloner_form_plugin_manager,
    ClonerPluginManager $cloner_content_entity_plugin_manager,
    ClonerPluginManager $cloner_config_entity_plugin_manager,
    CurrentRouteMatch $current_route_match,
    EntityTypeManagerInterface $entity_type_manager
  ) {

    $this->clonerFormPluginManager = $cloner_form_plugin_manager;
    $this->clonerContentEntityPluginManager = $cloner_content_entity_plugin_manager;
    $this->clonerConfigEntityPluginManager = $cloner_config_entity_plugin_manager;
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
      $container->get('plugin.manager.cloner.form'),
      $container->get('plugin.manager.cloner.content_entity'),
      $container->get('plugin.manager.cloner.config_entity'),
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cloner_clone_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $applicable_plugins = $this->clonerFormPluginManager->isApplicable($this->entityType, $this->entity);

    // If there is no applicable available plugins, show 404.
    if (empty($applicable_plugins)) {
      throw new NotFoundHttpException();
    }

    $cloner_winner = array_shift($applicable_plugins);

    // Create instance of the plugin.
    /** @var \Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBaseInterface $cloner_form_instance */
    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_winner['id'], [
      'entity' => $this->entity,
    ]);

    // Get plugin form.
    $form = $cloner_form_instance->buildForm($form, $form_state);

    $form['cloner_plugin_id'] = [
      '#type' => 'hidden',
      '#value' => $cloner_winner['id'],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Clone'),
      '#button_type' => 'primary',
    ];

    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => ['::cancelForm'],
      '#limit_validation_errors' => [],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $cloner_plugin_id = $form_state->getValue('cloner_plugin_id');

    /** @var \Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBaseInterface $cloner_form_instance */
    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_plugin_id, [
      'entity' => $this->entity,
    ]);

    $cloner_form_instance->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $cloner_plugin_id = $form_state->getValue('cloner_plugin_id');

    /** @var \Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBaseInterface $cloner_form_instance */
    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_plugin_id, [
      'entity' => $this->entity,
    ]);

    $cloner_clone_plugin_id = $cloner_form_instance->getClonerPluginId();
    /** @var \Drupal\cloner\Plugin\Cloner\ClonerClonePluginBaseInterface $cloner_clone_plugin_instance */
    $cloner_clone_plugin_instance = NULL;

    switch ($cloner_form_instance->getClonerPluginType()) {
      case 'content_entity':
        $cloner_clone_plugin_instance = $this->clonerContentEntityPluginManager->createInstance($cloner_clone_plugin_id);
        break;

      case 'config_entity':
        $cloner_clone_plugin_instance = $this->clonerConfigEntityPluginManager->createInstance($cloner_clone_plugin_id);
        break;
    }

    if (!$cloner_clone_plugin_instance) {
      throw new PluginNotFoundException($cloner_clone_plugin_id);
    }

    // Begin clone.
    $entity_cloned = $this->entity->createDuplicate();
    // Send form state in context. Allow to use plugins without forms.
    $cloner_clone_plugin_instance->cloneEntity($this->entity, $entity_cloned, [
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
        $entity->toUrl()->getRouteParameters()
      );
    }
    else {
      $form_state->setRedirect('<front>');
    }
  }

}
