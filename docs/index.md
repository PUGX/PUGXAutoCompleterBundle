PUGXAutocompleterBundle Documentation
=====================================

## 1. Installation

``` bash
$ composer require pugx/autocompleter-bundle
```

## 2. Configuration

Bundle is automatically configured by Flex. No further action is needed.

## 3. Frontend setup

This bundle requires [jQuery](http://jquery.com/) and [jQuery UI](http://jqueryui.com/).
As alternative, you can use [Select2](https://select2.github.io/) in place of jQuery UI.
Note that Select2 version 4 is not supported.

Installation and configuration of such JavaScript libraries is up to you.

If you prefer to see real code in action, you can find it in [this sandbox project](https://github.com/garak/AutoCompleterSandbox).

Supported method is [Encore](https://symfony.com/doc/current/frontend.html).
You can use assets automatically added to your `package.json` file.

In your `assets/js/app.js` file, use:

``` js
import '@pugx/autocompleter-bundle/js/autocompleter-jqueryui';

```

or

``` js
import '@pugx/autocompleter-bundle/js/autocompleter-select2';

Don't forget to require relevant dependencies in your package.json file and to import
them in your app.js file.

## 4. Usage

Suppose you have an `Author` entity, with a related `Book` entity (One-to-Many).
You want to display an `author` field inside a form describing your book, and you can't
use a plain `entity` field, since authors are many thousands.
In your FormType, change field type from `entity` to `autocomplete`:

``` php
<?php

// ...
use App\Entity\Author;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
// ...

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', AutocompleteType::class, ['class' => Author::class])
        ;
    }
}
```

As you can see, you must pass `class` as option to field. The class is the name of
your entity, and it's used to retrieve your objects from the database.

Then, you'll need a couple of actions in your controller.

``` php
<?php
// ...

final class DefaultController extends AbstractController
{
    public function searchAuthor(Request $request): Response
    {
        $q = $request->query->get('q'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:Author')->findLikeName($q);

        return $this->render('your_template.json.twig', ['results' => $results]);
    }

    public function getAuthor($id = null): Response
    {
        $author = $this->getDoctrine()->getRepository('App:Author')->find($id);

        return $this->json($author->getName());
    }
}
```

The first action, `searchAuthor`, is needed to search authors and to display them
inside your field. Here, a possible `findLikeName` repository method is used, to
search with `LIKE` statement (e.g. "da" will find "Dante Alighieri").
A possible twig template for first action:

``` twig
[{% for author in results -%}
    {{ {id: author.id, label: author.name, value: author.name}|json_encode|raw }}
    {# use "value" instead of "id" key, if you use jquery-ui #}
    {%- if not loop.last %},{% endif -%}
{%- endfor %}]
```

The second action, `getAuthor`, is needed to display a possible already selected value,
tipically when you display an edit form instead of a form for a new object.
In this case, the author object is searched by its id (no template is needed, just the name).
Note that this action should work with or without `$id` parameter, since such parameter is just appended to URL.

Last, in your JavaScript file, you should enable the autcompleter with following code:

``` javascript
$('#book_author').autocompleter({
    url_list: '/author_search',
    url_get: '/author_get/'
});
```

In which you must adapt both URLs to match the ones pointing to actions previously seen.
A good approach to decouple your JavaScript from your routing is to put URLs for your actions inside
your template (where your form is displayed), likely inside hidden fields. Then you can easliy retrieve
such values from JavaScript using DOM (e.g. using some identifiers).

### 4.1 Select2 options

If you want to pass additional configuration options to Select2, you can use the `otherOptions` parameter.
Example:

``` javascript
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

### 4.2 jQuery UI options

Available options (with defaults):

``` javascript
var options = {
    url_list: '',
    url_get: '',
    min_length: 2,
    on_select_callback: null  // you can use a function ($this, event, ui) here
};
```

### 4.3 Filter

If you use [LexikFormFilterBundle](https://github.com/lexik/LexikFormFilterBundle), you can also use a
`filter_autocomplete` type in your filter form.
Example:

``` php
<?php

// ...
use App\Entity\Book;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType;
// ...

class AuthorFormFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book', AutocompleteFilterType::class, ['class' => Book::class])
        ;
    }
}
```
