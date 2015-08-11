# Renderkit

Renderkit is a compositional framework for the generation and processing of Drupal render arrays.

It is designed as an alternative or addition to Display suite, panels, and other ways to display entities.

This module by itself is just about code. But there are / will be other modules that expose a lot of these classes as plugins that can be controlled from the UI.

The most relevant group of classes, "entity display handlers", implementing [EntityDisplayInterface](src/EntityDisplay/EntityDisplayInterface.php), are objects that build render arrays from entities. This may be for a specific field, an entity title link, or an entire entity displayed in a view mode with entity_view().

Some entity display handler classes take one or more other handlers as constructor arguments, e.g. to concatenate the output, or wrap it in a container element.

Besides entity display handlers, there are also classes and interfaces for lists, image derivatives, and more. All of them deal with render arrays somehow.

*Example*  
(all examples without the `use` statements for namespaced classes)

```php
$titleLinkDisplay = new EntityTitleLink();
$titleLinkBuild = $titleLinkDisplay->buildEntity('node', $node);
assert($titleLinkBuild === array(
  '#theme' => 'link',
  '#text' => 'Dolphins smarter than you think?',
  '#path' => 'node/123',
  '#options' => array(),
));
$titleLinkHtml = drupal_render($titleLinkBuild);
assert($titleLinkHtml === '<a href="/node/123">Dolphins smarter than you think?</a>');
```

## Composition

The nice thing is that you can build composite entity display handlers (or other types) from smaller components.

Let's start with a simple [EntityImageField](src/EntityImage/EntityImageField.php), that builds an image render array from the entity based on an image field. (Omitting all the namespace use statements).

```php
$imageDisplay = new EntityImageField('field_teaser_image');
$imageBuild = $imageDisplay->buildEntity('node', $node);
assert($imageBuild === array(
  '#theme' => 'image',
  '#path' => 'public://dolphins.jpg',
  '#width' => 1920,
  '#height' => 1028,
  '#alt' => 'Happy dolphins jumping',
));
```

Now let's run this image through an image style, to cut it down in size. To do this, we use an image style processor.

```php
$imageStyleProcessor = new ImageStyleImageProcessor('small');
$imageStyleBuild = $imageStyleProcessor->processImage($imageBuild);
assert($imageStyleBuild === array(
  '#theme' => 'image_style',
  '#path' => 'public://dolphins.jpg',
  '#width' => 1920,
  '#height' => 1028,
  '#alt' => 'Happy dolphins jumping',
  '#style_name' => 'small',
));
```

The interesting thing is that we can use the processor as a decorator, to create a combined entity display handler.

```php
$imageStyleDisplay = $imageStyleProcessor->decorate($imageDisplay);
// Run the two things at once.
$combinedImageStyleBuild = $imageStyleDisplay->buildEntity('node', $node);
// Result should be the same as before.
assert($combinedImageStyleBuild === $imageStyleBuild);
```

Likewise, the entity title link display from above can be wrapped in a `<h2 class="title">..</h2>` container.

```php
$titleLinkDisplayH2 = (new ContainerProcessor())
  ->setTagName('h2')
  ->addClass('title')
  ->decorate($titleLinkDisplay);
$titleLinkH2Build = $titleLinkDisplayH2->buildEntity('node', $node);
$titleLinkH2Html = drupal_render($titleLinkH2Build);
assert($titleLinkH2Html === '<h2 class="title"><a href="/node/123">Dolphins smarter than you think?</a></h2>');
```

We can now combine this stuff into a layout, with the image on the left, and the node title and body on the right, using display groups and layouts.

```php
$contentDisplayGroup = new EntityDisplayGroup(array(
  'title' => $titleLinkDisplayH2,
  'body' => new EntityFieldDisplay('body'),
));
// Using a theme hook defined via hook_theme().
$layoutDisplay = new EntityLayout('mymodule_two_columns_layout', array(
  'aside' => $imageStyleDisplay,
  'content' => $contentDisplayGroup,
));
```

And finally wrap this stuff into an `<article>` tag, with contextual links for privileged users.

```php
$completeDisplay = (new EntityContextualLinksProcessor())
  ->decorate($layoutDisplay);
```

## Comparison with Display suite and view modes.

The result is something similar to what we could have done with Display suite and entity view modes. The difference is:
- More fine-grained control.
- Allows deeply nested structure.
- Allows reuse of partial compositions.
- Allows variations without creating twenty view modes.
- The same display handler works across entity bundles (but you can use [switcher](src/EntityDisplay/Switcher) classes to distinguish between entity types and bundles).
- Less babysitting of features, because most of your stuff already lives in code. (except for plugins, see below)

## Plugin layer (EntDisP & friends)

There are (or will be) other modules that expose renderkit compositions as plugins. This makes entity displays and list formats available in views, and other places in Drupal.
 
Most notably this is (or will be) https://drupal.org/project/entdisp, which esposes entity display handlers as plugins. The README contains a lot of information about the plugin architecture.

More information in the README.md of the respective modules.
