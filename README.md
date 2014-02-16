# Twitter Bootstrap CakePHP Helper

CakePHP helper for rendering Twitter Bootstrap appropriate markup.

## Requirements

* [CakePHP 2.X](https://github.com/cakephp/cakephp)
* [Twitter Bootstrap CSS 3.0.0](http://getbootstrap.com/)
* *Optional* [Twitter Bootstrap JS](http://getbootstrap.com/)

## WARNING

This is MASTER. Until finished, no versioning. 
Once finished, [Semantic Versioning](http://semver.org/spec/v2.0.0.html) will be used.

## TO-DO

* ~~Read and re-write Form methods to match new Twitter Bootsrap form style.~~ (not much rewritten actually =P)
* ~~Fix Flash~~
* ~~Add option to add pure HTML to the navbr~~
* ~~Add method to build a [NAV][NAV] (which can be used as a sidebar)~~
* Add method to build [ACCORDION][ACCORDION] and/or COLLAPSE
* Add method to build [TAB][TAB]
* Add method to build [CAROUSEL][CAROUSEL]
* Pagination

[NAV]: http://getbootstrap.com/components/#nav
[ACCORDION]: http://getbootstrap.com/javascript/#collapse
[TAB]: http://getbootstrap.com/javascript/#tabs
[CAROUSEL]: http://getbootstrap.com/javascript/#carousel

## Installation 

Check out the the repo in the plugins directory

```
git clone git@github.com:bardiel/twitter-bootstrap-helper.git TwitterBootstrap
```

Add the plugin inclusion in the project bootstrap file

```
echo "CakePlugin::load('TwitterBootstrap');" >> Config/bootstrap.php
```

Then add helper to the $helpers array in a controller (AppController.php to use in all views)

```
public $helpers = array("Html", "Form", "TwitterBootstrap.TwitterBootstrap");
```

If you'd like to make the helper name shorter, remember the alias functionality:

```
public $helpers = array(
  "Html",
  "Form",
  "TB" => array(
    "className" => "TwitterBootstrap.TwitterBootstrap"
  )
);
```

## Configuration arrays

There are some methods which have their custom configuration array. This way you can, for instance, build configuration from within Controllers.

### ICON_ARRAY  
```
array(
    0 => String, //icon name (for example 'home')
    1 => String, //color (default is 'black')
)
```

### LABEL_ARRAY  
```
array(
    0 => String, //Text to be displayed
    1 => String, //Color (default is 'black')
    2 => Array, //options, array
)
```

### LINK_ARRAY  
There are two ways you can use LINK_ARRAYs. First one will output a `GET`:
```
array(
    0 => array(
        'icon' => ICON_ARRAY || Html, //Html = <i class="icon icon-nnnn"></i>
        'text' => String,
        'label' => LABEL_ARRAY || Html, //Html = <span class="label label-nnnn">text</span>
    ),
    1 => URL_ARRAY, //CakePHP URL Array
    2 => $ptions,
    3 => $confirm,
),
```
Also, you can configure wether it will be a `GET` or `POST` by passing the default LINK_ARRAY inside another named array like this: 
```
array(
  'post' => array(
        0 => array(
            'icon' => ICON_ARRAY || Html, //Html = <i class="icon icon-nnnn"></i>
            'text' => String,
            'label' => LABEL_ARRAY || Html, //Html = <span class="label label-nnnn">text</span>
        ),
        1 => URL_ARRAY, //CakePHP URL Array
        2 => $ptions,
        3 => $confirm,
)),
```
OR
```
array(
  'link' => array(
        0 => array(
            'icon' => ICON_ARRAY || Html, //Html = <i class="icon icon-nnnn"></i>
            'text' => String,
            'label' => LABEL_ARRAY || Html, //Html = <span class="label label-nnnn">text</span>
        ),
        1 => URL_ARRAY, //CakePHP URL Array
        2 => $ptions,
        3 => $confirm,
)),
```

### PANEL_ARRAY  
```
array(
    'icon' => ICON_ARRAY || Html,
    'title' => String,
    'style' => String, //Sets panel style. Default is 'default'. Valid are: 'default', 'primary', 'success', 'info', 'warning', 'danger'
    'label' => array( 
        LABEL_ARRAY
        ...
        ... //as many as you want
    ),
    'buttons' => array(
        LINK_ARRAY //Makes button
        array('dropdown' => DROPDOWN_ARRAY), //Makes a dropdown
        array('html' => PURE HTML, //you can pass pure HTML here
        array(), //Closes <div class="btn-group"> and opens another (acts as a separator)
        ...
        ... //as many as you want
    ),
)
```

### DROPDOWN_ARRAY  
```
array(
    'links' => array(
        LINK_ARRAY, //Makes link
        array(), //Makes a separator
        ...
        ... //as many as you want
    ),
    'size' => String, //Twitter Bootstrap sizes. Default is null. Valid are: 'xs', 'sm', 'lg'
    'style' => String, //Twitter Bootstrap styles. Default is 'default'. Valid are: 'default', 'primary', 'success', 'info', 'warning', 'danger', 'link'
    'split' => Boolean, //Set if button and caret should be splitted. Defalut is false.
    'dropup' => Boolean, //Set if it's a dropup insted a dropdown. Default is false
    'first' => array( //Sets what the dropdown button should be. Only ONE should be supplied. 
        'link' => LINK_ARRAY, //ONLY WORKS ON SPLITTED DROPDOWN
        'post' => POST_ARRAY, //ONLY WORKS ON SPLITTED DROPDOWN
        'title' => String || array('icon' => ICON_ARRAY, 'text' => String, 'label' => LABEL_ARRAY),
    )
```

### NAVBAR_ARRAY  
```
array(
    'collapsable' => Boolean, //Sets if navbar should be collapsable. Default is false.
    'inverse' => Boolean, //Sets if navbar should be inverse. Default is false
    'fixed' => Mixed, //Set which fixed layout should be set to the navbar. Default is false. Valid are: 'top', 'bottom', 'static-top'
    'id' => String //ID to be set for use with collapsable navbar. Default is: (string)time()
    'brand' => array( //Sets the Brand link
        'title' => String, 
        'href' => String,
    ),
    'container' => Boolean, //Defines if navbar content should be inside a <div class="container"> or not. Default is true.
    'buttons' => array(
        'right' => array(
            LINK_ARRAY,
            array('dropdown' => DROPDOW_ARRAY),
            array('html' => PURE HTML, //you can pass pure HTML here
            ...
            ... //as many as you want
        ),
        LINK_ARRAY,
        array('dropdown' => DROPDOWN_ARRAY),
        array('html' => PURE HTML, //you can pass pure HTML here
        ...
        ... //as many as you want
    ),
    'class' => String, //Aditional classes
);
```

There's an additional option included inside every LINK_ARRAY which is `'li_id' => String`. This options sets this ID to the `<LI>` tag where the link will be in the navbar.
This way you may set via JavaScritp the "active" class in the `<LI>`

### NAV_ARRAY
```
array(
    'style' => String, //Sets the NAV style. Valid are: 'pills', 'tabs'. Default is 'pills'
    'justified' => Boolean, //Adds Justified class for non-stacked navs. Default is false
    'stacked' => Boolean, //Adds Stacked class for non-justified navs. Default is false
    'buttons' => array(
        LINK_ARRAY, //with li_id
        array('dropdown' => DROPDOWN_ARRAY),
        array('html' => Pure HTML), //NOT RECOMENDED HERE (it uses the same method as navbar to create the LI's)
        ...
        ... //as many as you want
    ),
    'class' => String, //Aditional classes
);
```

### BREADCRUMBS_ARRAY  
An array containing **ONLY THE `DEFAULT`** LINK_ARRAY.  
*actually, it may contain de `post` or `link` array, but wont have any effect: breadcrumbs are always `GET`*  

This way, breadcrumbs can be built from within Controllers. 

## Methods

### TwitterBootstrapHelper::formCreate(mixed $model, array $options)  

Returns the same as CakePHP FormHelper::create(). This method adds one option for the `form-horizontal`. 
Opiton can be either:

+ **Boolean:** if `true` creates a form with `form-horizontal` class. Each input created inside this form will have it's label inside a `<div class="col-md-2">` and the input inside a `<div class="col-md-10">`.  
* **Integer:** if the number is greater than 0 and smaller than 12, the `col-md-` class of the label will be this number and the input `col-md-` class number will be 12 less this numbre. Else, will be the sames as if this option were a boolean `true`.

Default is boolean `false`. 

```
echo $this->TwitterBootstrap->formCreate('User', array('horizontal' => true));
```

### TwitterBootstrapHelper::input(string $field_name, array $options)

The form inputs in Twitter Bootstrap are quite different from the default html that the `FormHelper::input()` method. The `TwitterBootstrap->input()` aims to do the same type of thing that the `FormHelper::input()` would, but with the markup the bootstrap styles work with.

```
 echo $this->TwitterBootstrap->input("field_name", array(
  "input" => $this->Form->text("field_name"),
  "help_inline" => "Text to the right of the input",
  "help_block" => "Text under the input"
 ));
```

The method will handle errors and update the class on the div surrounding the input and label to show the text and input border as red.

### TwitterBootstrapHelper::basic_input(mixed $field, array $options)

While the `input()` method outputs the more verbose markup; the `basic_input()` method just outputs the minimum markup (the labal and input tags).

```
 echo $this->TwitterBootstrap->basic_input("field_name", array(
  "label" => "Custom label text"
 ));
```

### TwitterBootstrapHelper::search(string $name, array $options)

Twitter Bootstrap offers a new search specific text input field, and this method will ouput that input with its special class.

```
 echo $this->TwitterBootstrap->search();
```

### TwitterBootstrapHelper::radio(array $options)

This method will render groups of radio inputs. Internally it will call `TwitterBootstrap->input()`, so the options are the same.

```
 echo $this->TwitterBootstrap->radio("field_name", array(
  "options" => array(
   "yes" => "Yes",
   "no" => "No",
   "maybe" => "Maybe"
  )
 ));
```

### TwitterBootstrapHelper::button(string $value, array $options)

`TwitterBootstrap->button()` will render the submit button for forms.

```
echo $this->TwitterBootstrap->button("Save", array("style" => "primary", "size" => "lg"));
```

Valid styles for the button are "primary", "success", "info", and "danger". Valid sizes are "xs", "sm", "lg". If either are left out, then the default styles will apply (A grey button and a medium size). There is another option, "disabled", that takes a bool. If true it will apply the disabled styles.

### TwitterBootstrapHelper::button_dropdown(array $options)

The method will build a button dropdown menu. The dropdown js plugin is required for the dropdown functionality. The list of links for the dropdown is supplied in any array of LINK_ARRAY

See DROPDOWN_ARRAY for all options. 

### TwitterBootstrapHelper::button_link(mixed $title, mixed $url, array $options, string $confirm, string $type = 'link')

Creates a button link. This function may be used in two different ways:
To create a link using the same parameters as the **default** LINK_ARRAY with the addition of the `$type` parameter which can be: `"link"` or `"post"` (default is link):

OR you can pass a LINK_ARRAY as the first parameter. Doing so, you wont have to pass any other parameter:

Three examples that outputs the same:

*With the default LINK_ARRAY:* 
```
echo $this->TwitterBootstrap->button_link($this->TwitterBootstrap->icon('search').' aoeu', array('controller' => 'pages', 'action' => 'index'), array('style' => 'success'));
```
```
echo $this->TwitterBootstrap->button_link(array('icon' => array('search'), 'text' => 'aoeu'), array('controller' => 'pages', 'action' => 'index'), array('style' => 'success'));
```
*Full LINK_ARRAY:* 
```
echo $this->TwitterBootstrap->button_link(array('link' => array(array('icon' => array('search'), 'text' => 'aoeu'), array('controller' => 'pages', 'action' => 'index'), array('style' => 'success'))));
```

Like the `TwitterBootstrap->button()`, the "disabled" option can be passed to apply the disabled styles.

### TwitterBootstrapHelper::breadcrumbs(array $breadcrumbs)

`TwitterBootstrap->breadcrumbs()` utilizes `HtmlHelper::getCrumbs()` to build the breadcrumbs markup specific to Twitter Bootstrap.

`$breadcrumbs` is BREADCRUMS_ARRAY. See BREANDCRUMBS_ARRAY. 

### TwitterBootstrapHelper::label(string $message, string $style, array $options)

Twitter Bootstrap has some reusable label styles, and `TwitterBootstrap->label()` simply returns this small html fragment.

```
 echo $this->TwitterBootstrap->label("Recent", "warning");
```

The valid values for the second parameter are `default`, `primary`, `success`, `info`, `warning`, `danger`. Passing no second param will use the `default` (grey) style.

### TwitterBootstrapHelper::badge(string $title, array $options)

The Twitter Bootstrap badges are similar to labels, but are meant to contain a number... by default... Still it's a String

```
 echo $this->TwitterBootstrap->badge(4);
```

Style is deprecated since Bootstrap 3.0.

### TwitterBootstrapHelper::icon(string $name, array $options)

This method will output the icon markup with the proper clases.  Valid icon names are detailed on the [Twitter Bootstrap docs](http://twitter.github.com/bootstrap/base-css.html#icons). Pass the name of the icon without `icon-`.

```
 echo $this->TwitterBootstrap->icon("fire", array('class' => 'aoeu'));
```

$options are passed to the HtmlHelper::tag().  

Note params are the same as ICON_ARRAY.

### TwitterBootstrapHelper::progress(array $options)

The `progress()` method makes progress bars by specifying a width style inline. You can pass a 'width' option with a value from 0 to 100 and it will be applied as the beginning width. Passing the "striped" option will apply the striped styles, and passing the "active" option will make the progress bar animate.

```
 echo $this->TwitterBootstrap->progress(array("width" => 50, "striped" => true, "active" => true));
```

The valid values for the "style" options are `info`, `success`, `warning`, and `danger`.

### TwitterBootstrapHelper::flash(string $key, array $options)

It's now a slightly modified copy of CakePHP's SessionHelper::flash().
$options supported are the same as SessionHelper::flash() options. Refere to [SessionHelper](http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#displaying-notifications-or-flash-messages) for more information.
Added options inside 'params' key are:

```
'params' => array(
    'style' => String, //Supported Twitter Bootstrap are: 'success', 'info', 'warning', 'danger'. Default is 'warning'. There is no restriction to this defaults (in the case you make a custom style class)
    'closable' => Boolean, //Defines if alert should be closable or not. Default is false
)
```

These options might be set on Controller side:

```
 $this->Session->setFlash(__('Wrong username or password'), 'default', array('style' => 'danger', 'closable' => true));
```

Refere to [Session Component](http://book.cakephp.org/2.0/en/core-libraries/components/sessions.html#creating-notification-messages) for more info.

### TwitterBootstrapHelper::page_header(string $title, string $small)

`TwitterBootstrap->page_header()` will print a page heading TB style with a subtitle beside. If no subtitle is given, it will just output the title.

```
 echo $this->TwitterBootstrap->page_header("Page Header", "Subtitile");
```

### TwitterBootstrap->navbar(array $options)

Builds a navbar. See NAVBAR_ARRAY for `$options`.

### TwitterBootstrap->start_panel(array $options)

Starts a panel based on a PANEL_ARRAY. It will start a panel with its heading. Then you should echo any content you want.

After content is added you may add a footer `TwitterBootsrap->start_panel_footer()` or simply end it: `TwitterBootsrap->end_panel()`.

### TwitterBootsrap->start_panel_footer()

Will close a panel content div and start the footer div. This method should only be used 'inside' a panel.

### TwitterBootsrap->end_panel()

Simlpy returns `</div></div>`. This way it closes the panel content or footer div and the panel div. Should be used for closing panels only.

### TwitterBootstrap->nav(array $options)   
Creates a NAV based on $options. See NAV_ARRAY

## Deprecated Methods

These methods have been removed from the original code and or previous commits: 

### ~~TwitterBootstrapHelper::flashes(array $options)~~

Deprecated as it was a wat to workarround using $key as the alert style. Style is now hadeled through a key in the flash options, and `TwitterBootsrap::flash($key, $potions)` is consistent with `SessionHelper::flash($key, $options)`.

### ~~TwitterBootstrapHelper::block(string $message, array $links, array $options)~~

Deprecated as is too standard(???). You may use custom $element for your flashes. Refere to [SessionHelper](http://book.cakephp.org/2.0/en/core-libraries/helpers/session.html#displaying-notifications-or-flash-messages) for more information on how to make custom elements.

### ~~TwitterBootstrapHelper::button_form(string $title, mixed $url, array $options, string $confirm)~~

Deprecated since new LINK_ARRAY contemplates `POST` and `GET`. Use `TwitterBootstrapHelper->button_link()` instead.

~~The `TwitterBootstrap->button_form()` is the same as `TwitterBootstrap->button_link()`, but it uses `Form->postLink()` to create the link.~~

~~```
 echo $this->TwitterBootstrap->button_form("Delete", "/resource/delete/1", array("style" => "danger", "size" => "sm"), "Are you sure?");
```~~

~~Like the `TwitterBootstrap->button()`, the "disabled" option can be passed to apply the disabled styles.~~

~~Again, see LINK_ARRAY.~~
