# User Cloner plugin programmatically

It is possible to call every cloner plugin directly for you needs. For example, you can call cloner plugin, inside another plugin!

You need only two services:

 * `plugin.manager.cloner.content_entity`: For plugins for content entity clone.
 * `plugin.manager.cloner.config_entity`: For plugins for config entity clone.

Example

```php
/**
 * Implements hook_form_FORM_ID_alter() for node_article_edit_form.
 *
 * Alters node edit form for bundle "article". We add new button "Clone" which
 * execute cloner programmatically. This example shows that @ClonerContentEntity
 * and @ClonerConfigEntity plugins is standalone.
 */
function cloner_examples_form_node_article_edit_form_alter(array &$form, FormStateInterface $form_state, $form_id) {
  $current_user = \Drupal::currentUser();
  $clone_access = $current_user->hasPermission('access all entity cloner') || $current_user->hasPermission('access node cloner');

  $form['actions']['clone'] = [
    '#type' => 'submit',
    '#value' => t('Clone'),
    '#weight' => 20,
    '#access' => $clone_access,
    '#submit' => [
      '_cloner_example_clone_node_article_submit',
    ],
  ];
}

/**
 * Callback for clone button on node article edit form.
 */
function _cloner_example_clone_node_article_submit(array &$form, FormStateInterface $form_state) {
  // Node is content entity type, so call specific manager.
  /** @var \Drupal\cloner\Plugin\ClonerPluginManager $cloner_content_entity_plugin_manager */
  $cloner_content_entity_plugin_manager = \Drupal::service('plugin.manager.cloner.content_entity');

  // We knew that our plugin for node article cloning has id
  // "cloner_examples_node_article", so we call it.
  /** @var \Drupal\cloner\Plugin\Cloner\ContentEntity\ClonerContentEntityClonePluginBaseInterface $cloner_plugin_instance */
  $cloner_plugin_instance = $cloner_content_entity_plugin_manager->createInstance('cloner_examples_node_article');
  // We need manually prepare duplicate of entity and pass it to plugin.
  /** @var \Drupal\node\NodeInterface $node */
  $node = $form_state->getFormObject()->getEntity();
  $node_cloned = $node->createDuplicate();
  // Than call plugin to process it and make alterations. You can also pass some
  // data in context if you want.
  $cloner_plugin_instance->cloneEntity($node, $node_cloned);
  // After plugin do it's job, we save cloned entity.
  $node_cloned->save();

  // Also we can set redirect to our newly created entity.
  $form_state->setRedirect(
    $node_cloned->toUrl()->getRouteName(),
    $node_cloned->toUrl()->getRouteParameters()
  );
}
```