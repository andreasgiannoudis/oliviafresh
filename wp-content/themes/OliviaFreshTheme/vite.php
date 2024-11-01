<?php

class Vite
{
    /**
     * Flag to determine whether hot server is active.
     * Calculated when Vite::initialise() is called.
     *
     * @var bool
     */
    private static bool $isHot = false;

    /**
     * The URI to the hot server. Calculated when
     * Vite::initialise() is called.
     *
     * @var string
     */
    private static string $server;

    /**
     * The path where compiled assets will go.
     *
     * @var string
     */
    private static string $buildPath = 'build';

    /**
     * Manifest file contents. Initialised
     * when Vite::initialise() is called.
     *
     * @var array
     */
    private static array $manifest = [];

    /**
     * To be run in the header.php file, will check for the presence of a hot file.
     *
     * @param  string|null  $buildPath
     * @param  bool  $output  Whether to output the Vite client.
     *
     * @return string|null
     * @throws Exception
     */
    public static function init(string $buildPath = null, bool $output = true): string|null
    {
        static::$isHot = file_exists(static::hotFilePath());

        // have we got a build path override?
        if ($buildPath) {
            static::$buildPath = $buildPath;
        }

        // are we running hot?
        if (static::$isHot) {
            static::$server = file_get_contents(static::hotFilePath());
            $client = static::$server . '/@vite/client';

            // if output
            if ($output) {
                printf(
                /** @lang text */
                '<script type="module" src="%s"></script>', $client);
            }

            return $client;
        }

        // we must have a manifest file...
        if (!file_exists($manifestPath = static::buildPath() . '/manifest.json')) {
            throw new \Exception('No Vite Manifest exists. Should hot server be running?');
        }

        // store our manifest contents.
        static::$manifest = json_decode(file_get_contents($manifestPath), true);

        return null;
    }

    /**
     * Enqueue the module
     *
     * @param string|null $buildPath
     *
     * @return void
     * @throws Exception
     */
    public static function enqueue_module(string $buildPath = null): void
    {
        // we only want to continue if we have a client.
        if (!$client = Vite::init($buildPath, false)) {
            return;
        }
        // enqueue our client script
        wp_enqueue_script('vite-client', $client, [], null);

        // update html script type to module wp hack
        Vite::script_type_module('vite-client');
    }

    /**
     * Return URI path to an asset.
     *
     * @param $asset
     *
     * @return string
     * @throws Exception
     */
    public static function asset($asset): string
    {
        if (static::$isHot) {
            return static::$server . '/' . ltrim($asset, '/');
        }

        if (!array_key_exists($asset, static::$manifest)) {
            throw new \Exception('Unknown Vite build asset: ' . $asset);
        }

        return implode('/', [get_stylesheet_directory_uri(), static::$buildPath, static::$manifest[$asset]['file']]);
    }

    /**
     * Internal method to determine hotFilePath.
     *
     * @return string
     */
    private static function hotFilePath(): string
    {
        return implode('/', [static::buildPath(), 'hot']);
    }

    /**
     * Internal method to determine buildPath.
     *
     * @return string
     */
    private static function buildPath(): string
    {
        return implode('/', [get_stylesheet_directory(), static::$buildPath]);
    }

    /**
     * Return URI path to an image.
     *
     * @param $img
     *
     * @return string|null
     * @throws Exception
     */
    public static function img($img): ?string
    {
        try {

            // set the asset path to the image.
            $asset = 'resources/img/' . ltrim($img, '/');

            // if we're not running hot, return the asset.
            return static::asset($asset);
        } catch (\Exception $e) {

            // handle the exception here or log it if needed.
            // you can also return a default image or null in case of an error.
            return $e->getMessage(); // optionally, you can retrieve the error message

        }
    }

    /**
     * Update html script type to module wp hack.
     *
     * @param $scriptHandle bool|string
     * @return mixed
     */
    public static function script_type_module(bool|string $scriptHandle = false): string
    {
        // change the script type to module
        add_filter('script_loader_tag', function ($tag, $handle, $src) use ($scriptHandle) {

            if ($scriptHandle !== $handle) {
                return $tag;
            }

            // return the new script module type tag
            return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '-js"></script>';
        }, 10, 3);

        // return false
        return false;
    }
}



function vite_enqueue()
{
     // enqueue the Vite module
     Vite::enqueue_module();

     // register theme-style-css
     $filename = Vite::asset('resources/scss/app.scss');

     // enqueue theme-style-css into our head
     wp_enqueue_style('theme-styles', $filename, [], null, 'screen');

     // register theme-script-js
     $filename = Vite::asset('resources/js/app.js');

     // enqueue theme-script-js into our head (change false to true for footer)
     wp_enqueue_script('theme-scripts', $filename, [], null, false);

     // update html script type to module wp hack
     Vite::script_type_module('theme-scripts');
}

add_action('wp_enqueue_scripts', 'vite_enqueue');

function vite_enqueue_admin()
{
    // Enqueue the Vite module for admin
    Vite::enqueue_module();

    // Register admin styles
    $css_filename = Vite::asset('resources/scss/admin/admin.scss');
    wp_enqueue_style('admin-theme-styles', $css_filename, [], null, 'screen');

    wp_enqueue_media();

    // Register admin scripts if needed
    $js_filename = Vite::asset('resources/js/admin/admin.js');
    wp_enqueue_script('admin-theme-scripts', $js_filename, [], null, false);

    // Update HTML script type to module for admin scripts
    Vite::script_type_module('admin-theme-scripts');
}
add_action('admin_enqueue_scripts', 'vite_enqueue_admin');