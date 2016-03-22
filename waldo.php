<?php
    /**
     * A Dynamic Responsive CSS Background Images Extension for WordPress
     * and Other PHP-Based Applications
     *
     * Adds support to WordPress themes and other PHP based applications
     * (with appropriate modifications) to dynamically generate styles
     * for background images using only CSS.
     *
     * Waldo
     *
     * @package     Waldo
     * @author      Paper Leaf <info@paper-leaf.com>
     * @copyright   2016 Paper Leaf Design <http://www.paper-leaf.com>
     * @license     https://github.com/paper-leaf/waldo/blob/master/LICENSE.txt  GNU General Public License - Version 3
     * @version     1.0
     * @link        https://github.com/paper-leaf/waldo
     *
     * Credit:
     * Thank you to Donald Allen <https://github.com/donaldallen> for presenting the
     * initial idea of PHP generated styles for use with background images.
     */
    class Waldo {

        /**
         * Constructor
         * Creates global class vars, assigns default values.
         */
        function __construct() {
            /**
             * $this->waldo_sizes
             * Sets up supported media query sizes. These should match the image sizes supported in your WordPress installation.
             * @var array
             */
            $this->waldo_sizes = array(
                'small' => '', // default size, no 'min-width' media query required
                'medium' => '420',
                'large' => '768',
                'xlarge' => '1024'
            );

            /**
             * $this->template_dir
             * Get base path of current template. Uses WP get_template_directory() by default.
             * @var string
             */
            $this->template_dir = get_template_directory();
        }

        /**
         * waldoSavedStyles
         * Retrieve and unserialize styles array from file.
         *
         * @method waldoSavedStyles
         * @author Paper Leaf <info@paper-leaf.com>
         *
         * @return array
         */
        public function waldoSavedStyles() {
            return unserialize(file_get_contents($this->template_dir .'/waldo-styles.php'));
        }

        /**
         * waldoStylesArray
         * Build responsive background image styles and add to waldo_styles array.
         *
         * @method waldoStylesArray
         * @author Paper Leaf <info @ paper-leaf.com>
         *
         * @param  array   $image          Configured to work with the Advanced Custom Field plugin image object by default.
         * @param  string  $section_label  Unique name for the array key.
         * @param  array   $waldo_styles   Array of saved styles for different sections.
         * @param  string  $image_class    Must be a single, unique class name.
         * @return array
         */
        public function waldoStylesArray($image, $section_label, $waldo_styles, $image_class){
            if ( $image ) { // check if ACF image passed to $image
                // add array with unique key to styles array
                    // when reloaded from server, this index will be overwritten (with new values, if applicable)
                foreach ( $this->waldo_sizes as $size_label => $mq_value ) {
                    $waldo_styles[$section_label][$size_label] = "
                    ." .$image_class ." {
                            background-image: url('" .$image['sizes'][$size_label] ."');
                        }
                    ";
                }
            } else { // remove image styles if the image has been removed
                unset($waldo_styles[$section_label]);
            }

            return $waldo_styles;
        }

        /**
         * waldoStyles
         * Minify and write styles to waldo.css, save waldo_styles array to waldo-styles.php.
         *
         * @method waldoStyles
         * @author Paper Leaf <info@paper-leaf.com>
         *
         * @param  array  $waldo_styles  Array of saved styles for different sections.
         * @return null
         */
        public function waldoStyles($waldo_styles){
            $waldo_style_content = ''; // build string to pass to waldo.css
            $template_dir = $this->template_dir; // locally store path to template directory

            if ( !empty($waldo_styles) ) { // ensure Waldo is being called in this template file
                foreach ( $this->waldo_sizes as $size_label => $mq_value ) {
                    if ( $mq_value == '' ) { // no 'min-width' media query required for default image size
                        foreach ( $waldo_styles as $waldo_style ) {
                            $waldo_style_content .= $waldo_style[$size_label];
                        }
                    } else {
                        $waldo_style_content .= "@media (min-width: " .$mq_value ."px) {";

                        foreach ( $waldo_styles as $waldo_style ) {
                            $waldo_style_content .= $waldo_style[$size_label];
                        }

                        $waldo_style_content .= "}";
                    }
                }

                // write styles to waldo.css
                $waldo_style_content = preg_replace('/\s+/', '', $waldo_style_content); // comment out this line to render an un-minified style sheet
                file_put_contents($template_dir .'/waldo.css', $waldo_style_content);

                // write styles array to waldo-styles.php
                file_put_contents($template_dir .'/waldo-styles.php', serialize($waldo_styles)); // serialize data - file_put_contents() has issues with multidimensional arrays
            }

            return;
        }

    }


    /**
     * init_waldo
     * Creates a new waldo object and stores to global var $waldo. Calls $waldo->waldoSavedStyles() and stores returned array to global var $waldo_styles.
     *
     * @return null
     */
    function init_waldo() {
        global $waldo, $waldo_styles;

        $waldo = new Waldo();
        $waldo_styles = $waldo->waldoSavedStyles();
    }
    add_action('wp_head', 'init_waldo', 99);

    /**
     * compile_waldo
     * Calls $waldo->waldoStyles().
     *
     * @return null
     */
    function compile_waldo() {
        global $waldo, $waldo_styles;

        $waldo->waldoStyles($waldo_styles);
    }
    add_action('wp_footer', 'compile_waldo', 1);

?>
