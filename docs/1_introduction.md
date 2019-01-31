# Introduction

**Cloner** let you clone any entity on Drupal site, and add forms for this as well.

The obvious question that can appears, whats the difference between Cloner and [Entity Clone](https://www.drupal.org/project/entity_clone)?

Cloner is inspired by **Entity Clone**, but uses different approach to achieve the result. There is list of things you need to know, why it exists, and what's make it different:

 * Cloner is targeted primarily **for developers**.
 * Cloner is utilizing powerful Drupal 8 Plugin system.
 * Cloner **do not** add clone support for any entity type on site. You must write it manually.
 * Cloner **do not** add operations to entities, unless you create ClonerForm plugin and ask for it.
 * Cloner have separate plugin for UI, and clone possibilities, to expose it to content managers.
 * With Cloner you can create as many Cloner plugins, as you want, even for the same entity type and bundle combination.
 * With Cloner you write how entity must be cloned and all behavior, there is now predefined cloners.
 * You can call Cloner clone plugins directly in the code and utilize them.

So, this means Entity Clone is more user-friendly module, which works with zero-configuration, but have drawbacks, if you have very complex entities and fields in it. Cloner in the other hand, will not do anything, before developer do something. This requires time to write the clone process, as reward, you will get full control over cloning, and every complex entity type can be handled easily.
