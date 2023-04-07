<?php declare(strict_types = 1);

namespace Drupal\cloner_examples\Hook\Form;

use Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBaseInterface;
use Drupal\cloner\Plugin\ClonerPluginManager;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides node article edit form alter hook.
 *
 * @see cloner_examples_form_node_article_edit_form_alter()
 */
final class NodeArticleEditFormAlter implements ContainerInjectionInterface {

  use DependencySerializationTrait;

  /**
   * Construct NodeArticleEditFormAlter object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   * @param \Drupal\cloner\Plugin\ClonerPluginManager $clonerPluginManager
   *   The cloner plugin manager.
   */
  public function __construct(
    protected AccountProxyInterface $currentUser,
    protected ClonerPluginManager $clonerPluginManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('current_user'),
      $container->get('plugin.manager.cloner.content_entity'),
    );
  }

  /**
   * Implements hook_form_FORM_ID_alter() for node_article_edit_form.
   *
   * Alters a node edit form for bundle "article". We add a new button "Clone"
   * which executes cloner programmatically. This example shows that.
   *
   * @ClonerContentEntit and @ClonerConfigEntity plugin is standalone.
   */
  public function __invoke(array &$form, FormStateInterface $form_state, string $form_id): void {
    $access_all_clone = $this->currentUser->hasPermission('access all entity cloner');
    $access_node_clone = $this->currentUser->hasPermission('access node cloner');

    $form['actions']['clone'] = [
      '#type' => 'submit',
      '#value' => new TranslatableMarkup('Clone'),
      '#weight' => 20,
      '#access' => $access_all_clone || $access_node_clone,
      '#submit' => [[$this, 'cloneNodeArticleSubmit']],
    ];
  }

  /**
   * Clone action handler.
   *
   * @param array $form
   *   The complete form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function cloneNodeArticleSubmit(array &$form, FormStateInterface $form_state): void {
    $cloner_plugin_instance = $this->clonerPluginManager->createInstance('cloner_examples_node_article');
    \assert($cloner_plugin_instance instanceof ClonerContentEntityClonePluginBaseInterface);
    $node = $form_state->getFormObject()->getEntity();
    \assert($node instanceof NodeInterface);
    $node_cloned = $node->createDuplicate();
    // Then call plugin to process it and make alterations. You can also pass
    // some data in context if you want.
    $cloner_plugin_instance->cloneEntity($node, $node_cloned);
    // After plugin does its job, we save cloned entity.
    $node_cloned->save();

    // Also, we can set redirect to our newly created entity.
    $form_state->setRedirect(
      $node_cloned->toUrl()->getRouteName(),
      $node_cloned->toUrl()->getRouteParameters(),
    );
  }

}
