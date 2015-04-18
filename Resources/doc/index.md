PUGXAutocompleterBundle Documentation
=====================================

## 1. Installation

``` bash
$ composer require pugx/autocompleter-bundle
```

## 2. Configuration

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new PUGX\AutocompleterBundle\PUGXAutocompleterBundle(),
    );
}
```

## 3. Usage

This bundle requires [jQuery](http://jquery.com/) and [jQuery UI](http://jqueryui.com/).
As alternative, you can use [Select2](https://select2.github.io/) in place of jQuery UI.

Installation and configuration of these JavaScript libraries is up to you.

In your template, include autocompleter.js file:

```
{% javascripts
    'js/jquery.js'
    'js/jquery-ui.js'
    '@PUGXAutocompleterBundle/Resources/public/js/autocompleter-jqueryui.js'
%}
```

Or, if you prefer Select2:

```
{% javascripts
    'js/jquery.js'
    'js/select2.js'
    '@PUGXAutocompleterBundle/Resources/public/js/autocompleter-select2.js'
%}
```

Don't forget to include your stylesheet files.

Now, suppose you have an ``Author`` entity, with a related ``Book`` entity (One-to-Many).
You want to display a ``book`` field inside a form describing you author, and you can't
use a plain ``entity`` field, since books are many thousands.
In your FormType, change field type from ``entity`` to ``autocomplete``:

``` php
<?php
// AppBundle/Form/Type/AuthorFormType.php

// ...

class AuthorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book', 'autocomplete', array('class' => 'AppBundle:Book'))
        ;
    }
}
```

As you can see, you must pass ``class`` as option to field. The class is the name of
your entity, and it's used to retrieve your objects from the database.

Then, you'll need a couple of actions in your controller.

``` php
<?php
// AppBundle/Controller/DefaultController.php

// ...

class DefaultController extends Controller
{
    public function searchBookAction(Request $request)
    {
        $q = $request->get('term');
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Book')->findLikeName($q);

        return array('results' => $results);
    }

    public function getBookAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        return new Response($book->getName());
    }
}
```

The first action, ``searchBookAction``, is needed to search books and to display them
inside your field. Here, a possible ``findLikeName`` repository method is used, to
search with ``LIKE`` statement (e.g. "pe" will find "War and Peace").
A possible twig template for first action:

```
[{% for book in results -%}
    {{ {id: book.id, label: book.name, value: book.name}|json_encode|raw }}
    {%- if not loop.last %},{% endif -%}
{%- endfor %}]
```

The second action, ``getBookAction``, is needed to display a possible already selected value,
tipically when you display an edit form instead of a form for a new object.
In this case, the book object is searched by its id (no template is needed, just the name).

Last, in your Javascript file, you should enable the autcompleter with following code:

```
$('#book').autocompleter({url_list: '/book_search', url_get: '/book_get/'});
```

In which you must adapt both URLs to match the ones pointing to actions previously seen.
