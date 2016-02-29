# README #

##What is Waldo?##
Waldo is a dynamic responsive CSS background images extension for WordPress and other PHP-based applications that dynamically generates styles for full-cover background images using only CSS.

##Why Responsive Images?##
Mobile-first web development is the industry standard these days, and images are one of the leading causes of page bloat and slow loading times, especially on mobile devices and cellular networks. When compressed and appropriately sized images are served, page size and load time decrease drastically.

##Why Waldo?##
The state of handling responsive images with regards to web development is in flux, and there is no definitive solution to serving responsive full-cover background images. Waldo presents a solution to this problem, and it does so without utilizing JavaScript or (invalid) inline-styles.

##Dependencies##
The default configuration and usage guidelines for Waldo are based on WordPress 4.4+ with Advanced Custom Fields 5.3+.

##How to Use Waldo##
1. Copy the contents of **waldo-master** to the root directory of your WordPress theme.
2. Include *waldo.php* somewhere in your *functions.php* file.
3. Enqueue *waldo.css* in your *functions.php* file. Ensure the root path of this file is the same as the root path of the theme directory.
4. Integrate Waldo into your template files. For each template that is to utilize Waldo:
    1. After WP *get\_header();* create a new Waldo object.
        ```php
        get_header();

        $waldo = new Waldo;
        ```
    2. Declare global variable *$waldo\_styles* and assign the function *waldoSavedStyles()*. This fetches the saved styles array and stores them to the global variable.
        ```php
        get_header();

        $waldo = new Waldo;

        global $waldo_styles;
        $waldo_styles = $waldo->waldoSavedStyles();
        ```
    3. Before each instance where Waldo is to generate background image styles, get the Advanced Custom Field image object, and store to a variable. Build styles and save to array by calling the function *waldoStylesArray()*. Pass in the ACF image object, a unique name (string), the saved styles array, and a unique class name (string) for this section.
        ```php
        $image = get_field('acf_image_field_name');

        $waldo_styles = $waldo->waldoStylesArray($image, 'unique-section-name', $waldo_styles, 'unique-section-class-name');
        ```
    4. Before WP *get\_footer();* call the function *waldoStyles()* to generate and save the updated *waldo.css* file.
        ```php
        $waldo->waldoStyles($waldo_styles);

        get_footer();
        ```

##Follow Us!##
Follow [@paper_leaf](https://twitter.com/paper_leaf) on Twitter.

##Copyright & License##
*© 2016 Paper Leaf Design*

*License: [GNU General Public License - Version 3](https://github.com/paper-leaf/waldo/blob/master/LICENSE.txt)*

##But Why *'Waldo'*?##
Who's the character most well known for always being in the background?

So the question here really should be: "Where's Waldo?" Get it? :D
