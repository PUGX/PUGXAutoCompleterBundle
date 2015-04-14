PUGXAutocompleterBundle Documentation
=====================================

## Prerequisites

This version of the bundle requires Symfony 2.3 or higher


## Installation

1. Download PUGXAutocompleterBundle
2. Enable the Bundle
3. Usage

### 1. Download PUGXAutocompleterBundle

**Using composer**

Add the following lines in your composer.json:

```
{
    "require": {
        "pugx/autocompleter-bundle": "1.1.*"
    }
}

```

If you are using Symfony 2.1, please use 1.0 branch.

Now, run the composer to download the bundle:

``` bash
$ php composer.phar update pugx/autocompleter-bundle
```

### 2. Enable the bundle

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

### 3. Usage

This bundle requires [jquery](http://jquery.com/) and [select2](https://select2.github.io).
Installation and configuration of these two Javascript libraries is up to you.

In your template, include autocompleter-jquery-ui.js file:


```
{% javascripts
    '@AcmeBundle/Resources/public/js/jquery-1.8.0.min.js'
    '@AcmeBundle/Resources/public/js/select2.min.js'
    '@PUGXAutocompleterBundle/Resources/public/js/autocompleter-select2.js'
%}
```


Don't forget to include jquery UI stylesheet files.



Now suppose you have an ``Author`` entity, with a related ``Book`` entity (One-to-Many).
You want to display a ``book`` field inside a form describing you author, and you can't
use a plain ``entity`` field, since books are many thousands.
In your FormType, change field type from ``entity`` to ``autocomplete``:

``` php
<?php
// Acme/Bundle/Form/Type/AuthorFormType.php

// ...

class AuthorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book', 'autocomplete', array('class' => 'AcmeBundle:Book'))
        ;
    }
}
```

As you can see, you must pass ``class`` as option to field. The class is the name of
your entity, and it's used to retrieve your objects from the database.

Then, you'll need a couple of actions in your controller.

``` php
<?php
// Acme/Bundle/Controller/DefaultController.php

// ...

class DefaultController extends Controller
{
    public function searchBookAction(Request $request)
    {
        $q = $request->get('term');
        $results = $search->search($q);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id'=>$result->getId(),
                'text'=>$result->getData()['title'],
            ];
        }
        return $data;
    }

    public function getBookAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AcmeBundle:Book')->find($id);

        return new Response($book->getName());
    }
}
```

The first action, ``searchBookAction``, is needed to search books and to display them
inside your field. Here, a possible ``findLikeName`` repository method is used, to
search with ``LIKE`` statement (e.g. "pe" will find "War and Peace").
A possible twig template for first action :

```
[{% for book in results %}
{% spaceless %}
    {{ {id: book.id, text: book.title}|json_encode|raw }}{% if not loop.last %},{% endif %}
{% endspaceless %}
{% endfor %}]
```
The second action, ``getBookAction``, is needed to display a possible already selected value,
tipically when you display an edit form instead of a form for a new object.
In this case, the book object is searched by its id (no template is needed, just the name).

Last, in your Javascript file, you should enable the autcompleter with following code:

```
$('#book').autocompleter({url_list: '/book_search', url_get: '/book_get/'});
```

In which you must adapt both URLs to match the ones pointing to actions previously seen.
