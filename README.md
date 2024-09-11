# Statamic Table Of Contents

> A Statamic addon that generates a table of contents from a bard or markdown field in your antlers templates using
> a single tag.

## Features

- ✅ Generate a table of contents from a **bard** or **markdown** field.
- ✅ Supports all heading levels (h1 - h6).
- ✅ Specify a range of heading levels to include in the table of contents.
- ✅ Render the table of contents as an ordered or unordered list.

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or
run the following command from your project root:

``` bash
composer require doefom/statamic-table-of-contents
```

## How to Use

The addon provides a new tag called `{{ toc }}` that you can use anywhere in your antlers templates.

```html
{{ toc }}

------------------------

<ul>
    <li><a href="#ingredients">Ingredients</a></li>
    <li><a href="#boil-the-pasta">Boil the Pasta</a></li>
    <ul>
        <li><a href="#spaghetti-only">Spaghetti only</a></li>
    </ul>
</ul>
```

### Generating the table of contents from a specific field

By default, the tag looks for a field named `content` in the context of the page and generates a table of contents
from the headings in it. However, you can also specify a different field name like this:

```text
{{ toc :from="my_custom_field" }}
```

Note: Make sure to add a colon `:` before the `from` parameter to pass the value of the variable.

### Specifying a Range of Heading Levels

You can also specify a range of heading levels to be represented in the table of contents (by default, all heading
levels from h1 to h6 are included):

```text
{{ toc min="2" max="4" }}
```

You may also specify only the minimum or maximum level:

```text
{{ toc min="2" }}
```

```text
{{ toc max="4" }}
```

### Rendering as an Ordered List

If you prefer an ordered list instead of an unordered list, you can pass the `ordered` parameter like so:

```html
{{ toc ordered="true" }}

------------------------

<ol>
    <li><a href="#ingredients">Ingredients</a></li>
    <li><a href="#boil-the-pasta">Boil the Pasta</a></li>
    <ol>
        <li><a href="#spaghetti-only">Spaghetti only</a></li>
    </ol>
</ol>
```

### Options

| Parameter | Description                                                 | Default   |
|-----------|-------------------------------------------------------------|-----------|
| `from`    | The field to generate the table of contents from.           | `content` |
| `min`     | The minimum heading level to include.                       | `1`       |
| `max`     | The maximum heading level to include.                       | `6`       |
| `ordered` | Whether to render the table of contents as an ordered list. | `false`   |

## Styling the Table of Contents

There is no built-in way to style the table of contents, and therefore it's totally up to you to style it as you see
fit.

### Using the Tailwind Typography Plugin

If you use the [Tailwind typography plugin](https://github.com/tailwindlabs/tailwindcss-typography) somewhere in your
project, you can style the table of contents by adding the `prose` class to a surrounding element:

```html

<div class="prose">
    {{ toc }}
</div>
```

### Applying Individual Styles

Or if you prefer your individual styles, you may wrap the `{{ toc }}` tag in another element as well, add your own class
and apply the styles:

```html

<div class="table-of-contents">
    {{ toc }}
</div>

<style>
    .table-of-contents ul li a {
        ...
    }
</style>
```

## Support

This addon is supported. If you encounter any issues, feel free to open an issue
on [GitHub](https://github.com/doefom/statamic-table-of-contents/issues).
