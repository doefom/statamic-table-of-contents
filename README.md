# Statamic Table Of Contents

> A Statamic addon that generates a table of contents from a bard field.

## Features

- ✅ Generate a table of contents from a bard field.
- ✅ Supports all heading levels (h1 - h6).
- ✅ Specify a range of heading levels to include in the table of contents.
- ✅ Render the table of contents as an ordered or unordered list.

## How to Install

TODO

[//]: # (You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:)

[//]: # ()

[//]: # (``` bash)

[//]: # (composer require doefom/statamic-table-of-contents)

[//]: # (```)

## How to Use

The addon provides a new tag called `{{ toc }}` that you can use in your templates.

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

### Generating the table of contents from a specific bard field

By default, the tag looks for a bard field named `content` in the context of the page and generates a table of contents
from the headings in that field. However, you can also specify a different field name like this:

```text
{{ toc :from="my_bard_field" }}
```

Note: Make sure to add a colon `:` before the `from` parameter to pass the value of the variable.

### Specifying a Range of Heading Levels

You can also specify a range of heading levels to be represented in the table of contents. By default, all heading
levels (h1 - h6) are included. However, you may specify a range like this:

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

If you prefer an ordered list instead of an unordered list, you can specify the `ordered` parameter like so:

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
| `from`    | The bard field to generate the table of contents from.      | `content` |
| `min`     | The minimum heading level to include.                       | `1`       |
| `max`     | The maximum heading level to include.                       | `6`       |
| `ordered` | Whether to render the table of contents as an ordered list. | `false`   |

## Support

If you encounter any issues, feel free to open an issue
on [GitHub](https://github.com/doefom/statamic-table-of-contents/issues).
