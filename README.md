Metabox Sanity
==============

**Contributors:** Thomas Griffin (@jthomasgriffin / thomasgriffinmedia.com)  
**Version:** 1.0.0  
**Requires at least:** 3.0.0  
**Tested up to:** 3.9-beta1  

## Description ##

Metabox Sanity provides WordPress theme and plugin authors a way to benevolently dictate how other plugins and themes can interact with their custom post type interfaces in the form of metaboxes.

Up until now, if you wanted to customize your custom post type interface, you had to go on the offensive by **blacklisting** certain metaboxes from appearing on your screen. This is an ineffective way of curating interfaces because blacklisting is not exhaustive.

With Metabox Sanity, the control is put directly into your hands. You now have the power to **whitelist** what metaboxes can display on your pages, allowing you complete control over what your custom post type interface looks like. Don't want those pesky SEO metaboxes (which have no relevance to your post type) appearing on screen? No problem. You now dictate what is displayed on your custom post type interface.

## Installation ##

1. Drop the class file somewhere in your theme hierarchy (the example below uses the root of the theme).
2. Add the following code to your theme's `functions.php` file, modifying the config settings as needed. *I encourage you to use your own custom namespace for the function.*

``` php
add_action( 'add_meta_boxes', 'tgm_metabox_sanity', 999 );
function tgm_metabox_sanity() {

    if ( ! class_exists( 'TGM_Metabox_Sanity' ) ) {
        require get_stylesheet_directory() . '/class-tgm-metabox-sanity.php';
    }

    $config = array(
        'post' => array(
            'whitelist'  => array( 'submitdiv', 'postimagediv' ),
            'contexts'   => array( 'normal', 'advanced', 'side' ),
            'priorities' => array( 'high', 'core', 'default', 'low' )
        ),
        'page' => array(
            'whitelist'  => array( 'submitdiv' ),
            'contexts'   => array( 'normal', 'advanced', 'side' ),
            'priorities' => array( 'high', 'core', 'default', 'low' )
        )
    );
    $tgm_metabox_sanity = new TGM_Metabox_Sanity( $config );
    $tgm_metabox_sanity->init();

}
```

## Config Settings ##

Metabox Sanity provides very granular control over each post type display. The `$config` variable above is a multidimensional array of data. Each key in the main array references the post type slug to target. This keyholds an array of data about how to handle metaboxes within that post type. This array of data has three important keys: `whitelist`, `contexts` and `priorities`.

The `whitelist` key is an array of metabox IDs that you want to whitelist for that particular post type. **Remember, you are in control at this point, so if you do not whitelist the ID, it absolutely will not appear on the screen.** The `submitdiv` ID should *almost always* be included as it is the main metabox that allows you to publish and modify your post types. The metabox ID is the very first parameter passed in the function `add_meta_box`. You should use this in your whitelist array. To help, here is a small list of core metabox IDs and what they pertain to:

* `submitdiv` - the metabox that controls publishing content.
* `slugdiv` - the metabox that outputs the slug editing field for the post.
* `postimagediv` - the metabox that allows you to upload featured images to the post.
* 'formatdiv` - the metabox that controls post formats.
* `categorydiv` - the metabox that controls category selection for the post.
* `tagsdiv-post_tag` - the metabox that controls the tag selection for the post.
* `revisionsdiv` - the metabox that displays the revision data about the post.
* `postcustom` - the metabox that allows you to manage custom fields for the post.
* `commentsdiv` - the metabox that allows to you manage comments for the post.
* `authordiv` - the metabox that allows you to manage the authors for the post.
* `postexcerpt` - the metabox that controls the post excerpt for the post.
* `trackbacksdiv` - the metabox that controls trackbacks/pingbacks for the post.

The `contexts` key is an array of metabox contexts to target. The available contexts are `normal`, `advanced`, `side`. This is useful if you only want to remove metaboxes from a specific area of the screen.

The `priorities` key is an array of metabox priorities to target. The available priorities are `high`, `core`, `default`, and `low`.

## Considerations ##

**Please use this responsibly.** This class is very powerful and gives you a lot of control (finally!) over your post type screens. Please use it with care and only in areas where you are certain you have full control.

## Feedback ##

See https://github.com/thomasgriffin/metabox-sanity/issues for current issues and for reporting bugs and enhancements.

## Changelog ##

### 1.0.0 ###

* Initial release.