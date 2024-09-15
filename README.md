# Statamic Table Of Contents

> A Statamic addon that generates a table of contents from a bard or markdown field in your antlers templates using
> a modifiers.

## 🌟 Features

- ✅ Generate a table of contents from a **bard** or **markdown** field.
- ✅ Supports all heading levels (h1 - h6).
- ✅ Specify a range of heading levels to include in the table of contents.
- ✅ Render the table of contents as an ordered or unordered list.

## 🛠 How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or
run the following command from your project root:

``` bash
composer require doefom/statamic-table-of-contents
```

## 💡 How to Use

The addon provides two modifiers that you can use anywhere in your antlers templates:

1. `toc`: Generates the markup for a table of contents from a given bard or markdown field
2. `with_ids`: Adds unique IDs to the headings of the rendered html of a given bard or markdown field

If you want to learn more about Statamic modifiers in general, make sure to check out the
official documentation: https://statamic.dev/modifiers

### Basic Usage

To generate a table of contents that also links to your rendered content, we use both modifiers like this:

```html
{{ content_field | toc }}
{{ content_field | with_ids }}

------------------------------------------------------------------------

<!-- The table of contents generated from {{ content_field | toc }}  -->
<ul>
    <li><a href="#ingredients">Ingredients</a></li>
    <li><a href="#boil-the-pasta">Boil the Pasta</a></li>
    <ul>
        <li><a href="#spaghetti-only">Spaghetti only</a></li>
    </ul>
</ul>

<!-- 
The content with unique IDs added to the headings,
generated from {{ content_field | with_ids }} 
-->
<h2 id="ingredients">Ingredients</h2>
<p>...</p>
<h2 id="boil-the-pasta">Boil the Pasta</h2>
<p>...</p>
<h3 id="spaghetti-only">Spaghetti only</h3>
<p>...</p>
```

Keep in mind that you should **always use both modifiers** together to ensure that the table of contents links to the
correct headings. Using only one of the modifiers will probably not produce the desired results.

Now let's break down the two modifiers:

### The `toc` Modifier

The `toc` modifier generates a table of contents from a given bard or markdown field.

```html
{{ content_field | toc }}
```

```html

<ul>
    <li><a href="#ingredients">Ingredients</a></li>
    <li><a href="#boil-the-pasta">Boil the Pasta</a></li>
    <ul>
        <li><a href="#spaghetti-only">Spaghetti only</a></li>
    </ul>
</ul>
```

#### Signature of the `toc` Modifier

By default, the toc modifier will generate an unordered table of contents with all heading levels included. However, you
may specify which heading levels to include and whether to render the table of contents as an ordered or unordered list.

```text
{{ content_field | toc:min_level:max_level:ordered }}
```

#### Specifying a Range of Heading Levels

You can specify a range of heading levels to be represented in the table of contents (by default, all heading levels
from h1 to h6 are included):

```text
{{ content_field | toc:2:4 }}
```

This will result in a table of contents that only includes h2, h3, and h4 headings.

### Rendering as an Ordered List

If you prefer an ordered list instead of an unordered list, you can do this like so:

```text
{{ content_field | toc:1:6:true }}
```

```html

<ol>
    <li><a href="#ingredients">Ingredients</a></li>
    <li><a href="#boil-the-pasta">Boil the Pasta</a></li>
    <ol>
        <li><a href="#spaghetti-only">Spaghetti only</a></li>
    </ol>
</ol>
```

**Note:** Make sure to provide `min_level` and `max_level` as well if you want to render the table of contents as an
ordered list to maintain the correct order of the parameters.

#### Options

| Parameter   | Description                                                 | Default |
|-------------|-------------------------------------------------------------|---------|
| `min_level` | The minimum heading level to include.                       | `1`     |
| `max_level` | The maximum heading level to include.                       | `6`     |
| `ordered`   | Whether to render the table of contents as an ordered list. | `false` |

### The `with_ids` Modifier

The `with_ids` modifier adds unique IDs to each heading of the rendered html of a given bard or markdown field.

```html
{{ content_field | with_ids }}
```

```html
<h2 id="ingredients">Ingredients</h2>
<p>...</p>
<h2 id="boil-the-pasta">Boil the Pasta</h2>
<p>...</p>
<h3 id="spaghetti-only">Spaghetti only</h3>
<p>...</p>
```

#### Duplicate Headings

It also respects duplicate headings by appending a sequential number to the heading id.

```html
<h2 id="onions">Onions</h2>
<p>...</p>
<h3 id="chopping">Chopping</h3>
<p>...</p>
<h2 id="carrots">Carrots</h2>
<p>...</p>
<h3 id="chopping-1">Chopping</h3>
<p>...</p>
```

### Styling the Table of Contents

There is no built-in way to style the table of contents, and therefore it's totally up to you to style it as you see
fit.

### Using the Tailwind Typography Plugin

If you use the [Tailwind typography plugin](https://github.com/tailwindlabs/tailwindcss-typography) somewhere in your
project, you could style the table of contents by adding the `prose` class to a surrounding element:

```html

<div class="prose">
    {{ content_field | toc }}
</div>
```

### Applying Individual Styles

Of course, you can also apply individual styles to the table of contents. To do this, you'll probably want to wrap the
table of contents in a div as well, apply a class to it, and then style it in your CSS:

```html

<div class="table-of-contents">
    {{ content_field | toc }}
</div>

<style>
    .table-of-contents ul li a {
        ...
    }
</style>
```

### Using Custom Layout

The benefit of this addon is that you have two modifiers, one to generate the table of contents and another one to add
unique ids to your headings. This allows you to structure your site however you like.

```html

<div class="table-of-contents">
    {{ content_field | toc }}
</div>

<!-- Do something fancy in between -->

<div class="content">
    {{ content_field | with_ids }}
</div>
```

### Handling Bard Fields with Custom Sets

If you are using sets in you bard field, you will need to handle things a little differently.

#### Generating the Table of Contents

You should convert the bard field to HTML first, then decode the HTML entities, and finally apply the `toc` modifier.

```html
<div class="table-of-contents">
    {{ bard_with_sets | bard_html | decode | toc }}
</div>
```

#### Adding unique IDs to the Headings

When looping through the bard field, you should apply the `toc` modifier whenever you handle a text node.

```html
<div class="content">
    {{ bard_with_sets }}
        {{ if type === 'my_custom_set' }}
            ...
        {{ else }}
            {{ text | with_ids }}
        {{ /if }}
    {{ /bard_with_sets }}
</div>
```

#### In Combination

```html
<div class="table-of-contents">
    {{ bard_with_sets | bard_html | decode | toc }}
</div>

<div class="content">
    {{ bard_with_sets }}
        {{ if type === 'my_custom_set' }}
            ...
        {{ else }}
            {{ text | with_ids }}
        {{ /if }}
    {{ /bard_with_sets }}
</div>
```

## 🛟 Support

This addon is enthusiastically supported because I rely on it myself and I appreciate all feedback for features or 
issues you encounter using this addon. If you run into any problems, feel free to open an issue
on [GitHub](https://github.com/doefom/statamic-table-of-contents/issues).
