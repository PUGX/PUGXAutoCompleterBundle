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

```jinja
{% javascripts
    'js/jquery.js'
    'js/jquery-ui.js'
    '@PUGXAutocompleterBundle/Resources/public/js/autocompleter-jqueryui.js'
%}
```

Or, if you prefer Select2:

```jinja
{% javascripts
    'js/jquery.js'
    'js/select2.js'
    '@PUGXAutocompleterBundle/Resources/public/js/autocompleter-select2.js'
%}
```

Don't forget to include your stylesheet files.
Using Assetic is not mandatory. Feel free to recall your assets directly.

Now, suppose you have an `Author` entity, with a related `Book` entity (One-to-Many).
You want to display a `book` field inside a form describing your author, and you can't
use a plain `entity` field, since books are many thousands.
In your FormType, change field type from `entity` to `autocomplete`:

``` php
<?php
// AppBundle/Form/Type/AuthorFormType.php

// ...

class AuthorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // for Symfony 2, use 'autocomplete' as second argument
            ->add('book', 'PUGX\AutocompleterBundle\Form\Type\AutocompleteType', array('class' => 'AppBundle:Book'))
        ;
    }
}
```

As you can see, you must pass `class` as option to field. The class is the name of
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
        $q = $request->query->get('q');
        $results = $this->getDoctrine()->getRepository('AppBundle:Book')->findLikeName($q);

        return $this->render('your_template.html.twig', array('results' => $results));
    }

    public function getBookAction($id = null)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);

        return new Response($book->getName());
    }
}
```

The first action, `searchBookAction`, is needed to search books and to display them
inside your field. Here, a possible `findLikeName` repository method is used, to
search with `LIKE` statement (e.g. "pe" will find "War and Peace").
A possible twig template for first action:

```jinja
[{% for book in results -%}
    {{ {id: book.id, label: book.name, value: book.name}|json_encode|raw }}
    {%- if not loop.last %},{% endif -%}
{%- endfor %}]
```

The second action, `getBookAction`, is needed to display a possible already selected value,
tipically when you display an edit form instead of a form for a new object.
In this case, the book object is searched by its id (no template is needed, just the name).
Note that this action should work with or without `$id` parameter, since such parameter is just appended to URL.

Last, in your JavaScript file, you should enable the autcompleter with following code:

```
$('#book').autocompleter({
    url_list: '/book_search',
    url_get: '/book_get/'
});
```

In which you must adapt both URLs to match the ones pointing to actions previously seen.

### 3.1 Select2 options

If you want to pass additional configuration options to Select2, you can use the `otherOptions` parameter.
Example:

```
var opzioni = {
    url_list: $('#url-list').attr('href'),
    url_get: $('#url-get').attr('href'),
    otherOptions: {
        minimumInputLength: 3,
        formatNoMatches: 'Nessuna impresa trovata.',
        formatSearching: 'Ricerca...',
        formatInputTooShort: 'Inserire almeno 3 caratteri'
    }
};
$('#book').autocompleter({
    url_list: '/book_search',
    url_get: '/book_get/',
    otherOptions: {
        minimumInputLength: 5,
        formatNoMatches: 'No book found.',
        formatSearching: 'Searching books...',
        formatInputTooShort: 'Insert at least 5 characters!'
    }
});
```

### 3.2 Filter

If you use [LexikFormFilterBundle](https://github.com/lexik/LexikFormFilterBundle), you can also use a
`filter_autocomplete` type in your filter form.
Example:

``` php
<?php
// AppBundle/Form/Type/AuthorFormFilterType.php

// ...

class AuthorFormFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // for Symfony 2, use 'filter_autocomplete' as second argument
            ->add('book', 'PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType', array('class' => 'AppBundle:Book'))
        ;
    }
}
```
