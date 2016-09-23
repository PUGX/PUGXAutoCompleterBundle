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
    $bundles = [
        // ...
        new PUGX\AutocompleterBundle\PUGXAutocompleterBundle(),
    ];
}
```

## 3. Usage

This bundle requires [jQuery](http://jquery.com/) and [jQuery UI](http://jqueryui.com/).
As alternative, you can use [Select2](https://select2.github.io/) in place of jQuery UI.
Note that Select2 version 4 is not supported.

Installation and configuration of these JavaScript libraries is up to you.

If you prefer to see real code in action, you can find it in [this sandbox project](https://github.com/garak/AutoCompleterSandbox).

In your template, include autocompleter.js file:

```jinja
{% block javascripts %}
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('bundles/pugxautocompleter/js/autocompleter-jqueryui.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
{% endblock %}
```

Or, if you prefer Select2:

```jinja
{% block javascripts %}
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.js"></script>
    <script src="{{ asset('bundles/pugxautocompleter/js/autocompleter-select2.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
{% endblock %}
```

Don't forget to include your stylesheet files.

Now, suppose you have an `Author` entity, with a related `Book` entity (One-to-Many).
You want to display an `author` field inside a form describing your book, and you can't
use a plain `entity` field, since authors are many thousands.
In your FormType, change field type from `entity` to `autocomplete`:

``` php
<?php
// AppBundle/Form/BookType.php

// ...

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', 'PUGX\AutocompleterBundle\Form\Type\AutocompleteType', ['class' => 'AppBundle:Author'])
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
    public function searchAuthorAction(Request $request)
    {
        $q = $request->query->get('q');
        $results = $this->getDoctrine()->getRepository('AppBundle:Author')->findLikeName($q);

        return $this->render('your_template.html.twig', ['results' => $results]);
    }

    public function getAuthorAction($id = null)
    {
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($id);

        return new Response($author->getName());
    }
}
```

The first action, `searchAuthorAction`, is needed to search authors and to display them
inside your field. Here, a possible `findLikeName` repository method is used, to
search with `LIKE` statement (e.g. "da" will find "Dante Alighieri").
A possible twig template for first action:

```jinja
[{% for author in results -%}
    {{ {id: author.id, label: author.name, value: author.name}|json_encode|raw }}
    {%- if not loop.last %},{% endif -%}
{%- endfor %}]
```

The second action, `getAuthorAction`, is needed to display a possible already selected value,
tipically when you display an edit form instead of a form for a new object.
In this case, the author object is searched by its id (no template is needed, just the name).
Note that this action should work with or without `$id` parameter, since such parameter is just appended to URL.

Last, in your JavaScript file, you should enable the autcompleter with following code:

```
$('#book_author').autocompleter({
    url_list: '/author_search',
    url_get: '/author_get/'
});
```

In which you must adapt both URLs to match the ones pointing to actions previously seen.

### 3.1 Select2 options

If you want to pass additional configuration options to Select2, you can use the `otherOptions` parameter.
Example:

```
var options = {
    url_list: $('#url-list').attr('href'),
    url_get: $('#url-get').attr('href'),
    otherOptions: {
        minimumInputLength: 3,
        formatNoMatches: 'No author found.',
        formatSearching: 'Looking authors...',
        formatInputTooShort: 'Insert at least 3 characters'
    }
};
$('#book_author').autocompleter(options);
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
            ->add('book', 'PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType', ['class' => 'AppBundle:Book'])
        ;
    }
}
```
