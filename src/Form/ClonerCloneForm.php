<?php declare(strict_types = 1);

namespace Drupal\cloner\Form;

use Drupal\cloner\Plugin\Cloner\ClonerClonePluginBaseInterface;
use Drupal\cloner\Plugin\Cloner\Form\ClonerFormPluginBaseInterface;
use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ClonerCloneFormBase.
 *
 * The base form for all other clone bases.
 *
 * @package Drupal\cloner\Form
 */
final class ClonerCloneForm extends FormBase {

  /**
   * The entity to be cloned.
   */
  protected EntityInterface $entity;

  /**
   * Then entity type.
   */
  protected EntityTypeInterface $entityType;

  /**
   * ClonerCloneForm constructor.
   *
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $clonerFormPluginManager
   *   The plugin manager for cloner form plugins.
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $clonerContentEntityPluginManager
   *   The plugin manager for content entity plugins.
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $clonerConfigEntityPluginManager
   *   The plugin manager for config entity plugins.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    protected ClonerPluginManager $clonerFormPluginManager,
    protected ClonerPluginManager $clonerContentEntityPluginManager,
    protected ClonerPluginManager $clonerConfigEntityPluginManager,
    protected CurrentRouteMatch $currentRouteMatch,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {

    // Trying to find entity object.
    $entity_type_id = $this->currentRouteMatch->getRouteObject()
      ->getOption('_cloner_entity_type_id');
    $this->entity = $this->currentRouteMatch->getParameter($entity_type_id);
    $this->entityType = $this->entityTypeManager->getDefinition($entity_type_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('plugin.manager.cloner.form'),
      $container->get('plugin.manager.cloner.content_entity'),
      $container->get('plugin.manager.cloner.config_entity'),
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cloner_clone_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $applicable_plugins = $this->clonerFormPluginManager->isApplicable($this->entityType, $this->entity);

    // If there are no applicable available plugins, show 404.
    if (\count($applicable_plugins) === 0) {
      throw new NotFoundHttpException();
    }

    $cloner_winner = \array_shift($applicable_plugins);

    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_winner['id'], [
      'entity' => $this->entity,
    ]);
    \assert($cloner_form_instance instanceof ClonerFormPluginBaseInterface);

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
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $cloner_plugin_id = $form_state->getValue('cloner_plugin_id');

    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_plugin_id, [
      'entity' => $this->entity,
    ]);
    \assert($cloner_form_instance instanceof ClonerFormPluginBaseInterface);

    $cloner_form_instance->validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $cloner_plugin_id = $form_state->getValue('cloner_plugin_id');

    $cloner_form_instance = $this->clonerFormPluginManager->createInstance($cloner_plugin_id, [
      'entity' => $this->entity,
    ]);
    \assert($cloner_form_instance instanceof ClonerFormPluginBaseInterface);

    $cloner_clone_plugin_id = $cloner_form_instance->getClonerPluginId();
    $cloner_clone_plugin_instance = NULL;

    switch ($cloner_form_instance->getClonerPluginType()) {
      case 'content_entity':
        $cloner_clone_plugin_instance = $this->clonerContentEntityPluginManager->createInstance($cloner_clone_plugin_id);
        break;

      case 'config_entity':
        $cloner_clone_plugin_instance = $this->clonerConfigEntityPluginManager->createInstance($cloner_clone_plugin_id);
        break;
    }

    if (!isset($cloner_clone_plugin_instance)) {
      throw new PluginNotFoundException($cloner_clone_plugin_id);
    }

    // Begin clone.
    $entity_cloned = $this->entity->createDuplicate();
    // Send form state in context. Allow to use plugins without forms.
    \assert($cloner_clone_plugin_instance instanceof ClonerClonePluginBaseInterface);
    $cloner_clone_plugin_instance->cloneEntity($this->entity, $entity_cloned, [
      'form_state' => $form_state,
    ]);
    // Save after processing.
    $entity_cloned->save();

    $form_state->setRedirect(
      $entity_cloned->toUrl()->getRouteName(),
      $entity_cloned->toUrl()->getRouteParameters(),
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
  public function cancelForm(array &$form, FormStateInterface $form_state): void {
    $entity = $this->entity;

    if ($entity && $entity->hasLinkTemplate('canonical')) {
      $form_state->setRedirect(
        $entity->toUrl()->getRouteName(),
        $entity->toUrl()->getRouteParameters(),
      );
    }
    else {
      $form_state->setRedirect('<front>');
    }
  }

}
