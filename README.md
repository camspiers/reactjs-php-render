# ReactJS PHP Render

This library aims to provide multiple options for rendering React from PHP.

## Experimental

The API is experimental and is likely to change.

## Concepts

* Renderer (`ReactJS\Renderer\RendererInterface`)
 * This interface is implemented by mutiple renderers to provide different potential rendering options (HTTP Server, V8Js etc)
* RuntimeFragmentProvider (`ReactJS\RuntimeFragmentProvider\ProviderInterface`)
 * This interface is implemented to provider different environment support (CommonJS, Globals etc)

## Usage

Renderers can be used directly to generate either "mountable" React HTML (including checksums and ids), or to generate static markup.

The React class (`ReactJS\React`) can be used to generate mountable React HTML along with JavaScript that will automatically mount the browser React component into the generated server rendered markup.

### Node

When using a node process, users are required to provide source file(s) in an appropriate format for node to execute, these source file(s) need include:

* React
* The component you are attempting to render

```php
$node = ReactJS\ReactFactory::createUsingNode(
	'/usr/bin/nodejs',
	['bundle.js'] // bundle.js is a browserified bundle with React and TestComponent
);

echo $node->renderAutoMountingComponent('./TestComponent');
```

### V8

When using the V8Js php extension, users are required to provide source file(s) in the appropriate format for V8 to execute, these source file(s) need include:

* React
* The component you are attempting to render

```php
$v8 = ReactJS\ReactFactory::createUsingV8(
	['bundle.js'] // bundle.js is a browserified bundle with React and TestComponent
);

echo $v8->renderAutoMountingComponent('./TestComponent');
```

The result:

```html
<div id="53998c3f85044">
	<span data-reactid=".fn8fq9jb40" data-react-checksum="2066486547">Some testing content</span>
</div>
<script>
	require('react').renderComponent(
		require(".\/TestComponent")(null),
		document.getElementById("53998c3f85044")
	)
</script>
```

## Installation (with composer)

	$ composer require camspiers/reactjs-php-render:dev-master

