<?php

namespace WGA\Busting;

/**
 * This class configures the google analytics cache
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>, Github: https://github.com/alexkovalevv
 * @copyright (c) 2017 Webraftic Ltd
 * @version       1.0
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

class Facebook_SDK {

	/**
	 * Facebook SDK URL.
	 * %s is a locale like "en_US".
	 *
	 * @var    string
	 * @since  3.2.0
	 */
	protected $url = 'https://connect.facebook.net/%s/sdk.js';

	/**
	 * Filename for the cache busting file.
	 * %s is a locale like "en_US".
	 *
	 * @var    string
	 * @since  3.2.0
	 */
	protected $filename = 'fbsdk-%s.js';

	/**
	 * Flag to track the replacement.
	 *
	 * @var    bool
	 * @since  3.2.0
	 */
	protected $is_replaced = false;

	/**
	 * Filesystem object.
	 *
	 * @var    object
	 * @since  3.2.0
	 */
	protected $filesystem = false;

	/**
	 * Constructor.
	 *
	 * @param string $busting_path Path to the busting directory.
	 * @param string $busting_url URL of the busting directory.
	 * @since  3.2.0
	 *
	 */
	public function __construct($busting_path, $busting_url)
	{
		/** Warning: the file name and script URL are dynamic, and must be run through sprintf(). */
		$this->busting_path = $busting_path . 'facebook-tracking/';
		$this->busting_url = $busting_url . 'facebook-tracking/';

		/*
	    * Define the timeouts for the connections. Only available after the constructor is called
	    * to allow for per-transport overriding of the default.
	    */
		if( !defined('FS_CONNECT_TIMEOUT') ) {
			define('FS_CONNECT_TIMEOUT', 30);
		}
		if( !defined('FS_TIMEOUT') ) {
			define('FS_TIMEOUT', 30);
		}

		// Set the permission constants if not already set.
		if( !defined('FS_CHMOD_DIR') ) {
			define('FS_CHMOD_DIR', (fileperms(ABSPATH) & 0777 | 0755));
		}
		if( !defined('FS_CHMOD_FILE') ) {
			define('FS_CHMOD_FILE', (fileperms(ABSPATH . 'index.php') & 0777 | 0644));
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		$this->filesystem = new \WP_Filesystem_Direct(new \StdClass());
	}

	/**
	 * Perform the URL replacement process.
	 *
	 * @param string $html HTML contents.
	 * @return string       HTML contents.
	 * @since  3.2.0
	 *
	 */
	public function replace_url($html)
	{
		$this->is_replaced = false;

		$tag = $this->find('<script[^>]*?>(.*)<\/script>', $html);

		if( !$tag ) {
			return $html;
		}

		\WGA_Plugin::app()->logger->info('FACEBOOK SDK CACHING PROCESS STARTED. Tag ' . $tag);

		$locale = $this->get_locale_from_url($tag);
		$remote_url = $this->get_url($locale);

		if( !$this->save($remote_url) ) {
			return $html;
		}

		$file_url = $this->get_busting_file_url($locale);
		$replace_tag = preg_replace('@(?:https?:)?//connect\.facebook\.net/[a-zA-Z_-]+/sdk\.js@i', $file_url, $tag, -1, $count);

		if( !$count || false === strpos($html, $tag) ) {
			\WGA_Plugin::app()->logger->error('The facebook sdk local file URL could not be replaced in the page contents.');

			return $html;
		}

		$html = str_replace($tag, $replace_tag, $html);
		$file_path = $this->get_busting_file_path($locale);
		$xfbml = $this->get_xfbml_from_url($tag); // Default value should be set to false.
		$app_id = $this->get_appId_from_url($tag); // APP_ID is the only required value.
		$url_version = $this->get_version_from_url($tag);
		$version = false === $url_version ? 'v5.0' : $url_version; // If version is not available set it to the latest: v.5.0.

		if( false !== $app_id ) {
			// Add FB async init.
			$fb_async_script = '<script>window.fbAsyncInit = function fbAsyncInit () {FB.init({appId: \'' . $app_id . '\',xfbml: ' . $xfbml . ',version: \'' . $version . '\'})}</script>';
			$html = str_replace('</body>', $fb_async_script . '</body>', $html);
		}

		$this->is_replaced = true;

		\WGA_Plugin::app()->logger->info('Facebook SDK caching process succeeded. File ' . $file_path);

		return $html;
	}

	/**
	 * Tell if the replacement was sucessful or not.
	 *
	 * @return bool
	 * @since  3.2.0
	 *
	 */
	public function is_replaced()
	{
		return $this->is_replaced;
	}

	/**
	 * Search for an element in the DOM.
	 *
	 * @param string $pattern Pattern to match.
	 * @param string $html HTML contents.
	 * @return string|bool     The matched HTML on success. False if nothing is found.
	 * @since  3.2.0
	 *
	 */
	protected function find($pattern, $html)
	{
		preg_match_all('/' . $pattern . '/Umsi', $html, $matches, PREG_SET_ORDER);

		if( empty($matches) ) {
			return false;
		}

		foreach($matches as $match) {
			if( trim($match[1]) && preg_match('@//connect\.facebook\.net/[a-zA-Z_-]+/sdk\.js@i', $match[1]) ) {
				return $match[0];
			}
		}

		return false;
	}

	/**
	 * Save the contents of a URL into a local file if it doesn't exist yet.
	 *
	 * @param string $url URL to get the contents from.
	 * @return bool        True on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	public function save($url)
	{
		$locale = $this->get_locale_from_url($url);
		$path = $this->get_busting_file_path($locale);

		if( $this->filesystem->exists($path) ) {
			// If a previous version is present, keep it.
			return true;
		}

		return $this->refresh_save($url);
	}

	/**
	 * Save the contents of a URL into a local file.
	 *
	 * @param string $url URL to get the contents from.
	 * @return bool        True on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	public function refresh_save($url)
	{
		$content = $this->get_file_content($url);

		if( !$content ) {
			// Error, we couldn't fetch the file contents.
			return false;
		}

		$locale = $this->get_locale_from_url($url);
		$path = $this->get_busting_file_path($locale);

		return (bool)$this->update_file_contents($path, $content);
	}

	/**
	 * Add new contents to a file. If the file doesn't exist, it is created.
	 *
	 * @param string $file_path Path to the file to update.
	 * @param string $file_contents New contents.
	 * @return string|bool           The file contents on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	private function update_file_contents($file_path, $file_contents)
	{
		if( !$this->filesystem->exists($this->busting_path) ) {
			\rocket_mkdir_p($this->busting_path);
		}

		if( !\rocket_put_content($file_path, $file_contents) ) {
			\WGA_Plugin::app()->logger->error('Contents could not be written into file. File path ' . $file_path);

			return false;
		}

		return $file_contents;
	}

	/**
	 * Look for existing local files and update their contents if there's a new version available.
	 * Actually, if a more recent version exists on the FB side, it will delete all local files and hit the home page to recreate them.
	 *
	 * @return bool True on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	public function refresh()
	{
		$files = $this->get_files();

		if( !$files ) {
			// No files (or there's an error).
			return false !== $files;
		}

		$error_paths = [];
		$pattern = $this->escape_file_name($this->filename);
		$pattern = sprintf($pattern, '(?<locale>[a-zA-Z_-]+)');

		foreach($files as $file) {
			preg_match('/^' . $pattern . '$/', $file, $matches);

			$remote_url = $this->get_url($matches['locale']);

			if( !$this->refresh_save($remote_url) ) {
				$error_paths[] = $this->get_busting_file_path($matches['locale']);
			}
		}

		if( $error_paths ) {
			\WGA_Plugin::app()->logger->error('Local file(s) could not be updated. Paths ' . $error_paths);
		}

		return !$error_paths;
	}

	/**
	 * Delete all Facebook SDK busting files.
	 *
	 * @return bool True on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	public function delete()
	{
		$filesystem = $this->filesystem;
		$files = $this->get_files();

		if( !$files ) {
			// No files (or there's an error).
			return false !== $files;
		}

		$error_paths = [];

		foreach($files as $file_name) {
			if( !$filesystem->delete($this->busting_path . $file_name, false, 'f') ) {
				$error_paths[] = $this->busting_path . $file_name;
			}
		}

		if( $error_paths ) {
			\WGA_Plugin::app()->logger->error('Local file(s) could not be deleted. Paths ' . $error_paths);
		}

		return !$error_paths;
	}

	/**
	 * Get all cached files in the directory.
	 *
	 * @return array|bool A list of file names. False on failure.
	 * @since  3.2.0
	 *
	 */
	private function get_files()
	{
		$filesystem = $this->filesystem;
		$dir_path = rtrim($this->busting_path, '\\/');

		if( !$filesystem->exists($dir_path) ) {
			return [];
		}

		if( !$filesystem->is_writable($dir_path) ) {
			\WGA_Plugin::app()->logger->error('Facebook sdk: Directory is not writable. Path ' . $dir_path);

			return false;
		}

		$dir = $filesystem->dirlist($dir_path);

		if( false === $dir ) {
			\WGA_Plugin::app()->logger->error('Facebook sdk: Could not get the directory contents. Path ' . $dir_path);

			return false;
		}

		if( !$dir ) {
			return [];
		}

		$list = [];
		$pattern = $this->escape_file_name($this->filename);
		$pattern = sprintf($pattern, '[a-zA-Z_-]+');

		foreach($dir as $entry) {
			if( 'f' !== $entry['type'] ) {
				continue;
			}
			if( preg_match('/^' . $pattern . '$/', $entry['name'], $matches) ) {
				$list[$entry['name']] = $entry['name'];
			}
		}

		return $list;
	}

	/**
	 * Get the remote Facebook SDK URL.
	 *
	 * @param string $locale A locale string, like 'en_US'.
	 * @return string
	 * @since  3.2.0
	 *
	 */
	public function get_url($locale)
	{
		return sprintf($this->url, $locale);
	}

	/**
	 * Extract the locale from a URL to bust.
	 *
	 * @param string $url Any string containing the URL to bust.
	 * @return string|bool The locale on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	private function get_locale_from_url($url)
	{
		$pattern = '@//connect\.facebook\.net/(?<locale>[a-zA-Z_-]+)/sdk\.js@i';

		if( !preg_match($pattern, $url, $matches) ) {
			return false;
		}

		return $matches['locale'];
	}

	/**
	 * Extract XFBML from a URL to bust.
	 *
	 * @param string $url Any string containing the URL to bust.
	 * @return string|bool The XFBML on success. False on failure.
	 * @since  3.4.3
	 *
	 */
	private function get_xfbml_from_url($url)
	{
		$pattern = '@//connect\.facebook\.net/(?<locale>[a-zA-Z_-]+)/sdk\.js#(?:.+&)?xfbml=(?<xfbml>[0-9]+)@i';

		if( !preg_match($pattern, $url, $matches) ) {
			return false;
		}

		return $matches['xfbml'];
	}

	/**
	 * Extract appId from a URL to bust.
	 *
	 * @param string $url Any string containing the URL to bust.
	 * @return string|bool The appId on success. False on failure.
	 * @since  3.4.3
	 *
	 */
	private function get_appId_from_url($url)
	{
		$pattern = '@//connect\.facebook\.net/(?<locale>[a-zA-Z_-]+)/sdk\.js#(?:.+&)?appId=(?<appId>[0-9]+)@i';

		if( !preg_match($pattern, $url, $matches) ) {
			return false;
		}

		return $matches['appId'];
	}

	/**
	 * Extract version from a URL to bust.
	 *
	 * @param string $url Any string containing the URL to bust.
	 * @return string|bool The version on success. False on failure.
	 * @since  3.4.3
	 *
	 */
	private function get_version_from_url($url)
	{
		$pattern = '@//connect\.facebook\.net/(?<locale>[a-zA-Z_-]+)/sdk\.js#(?:.+&)?version=(?<version>[a-zA-Z0-9.]+)@i';

		if( !preg_match($pattern, $url, $matches) ) {
			return false;
		}

		return $matches['version'];
	}

	/**
	 * Get the local Facebook SDK URL.
	 *
	 * @param string $locale A locale string, like 'en_US'.
	 * @return string
	 * @since  3.2.0
	 *
	 */
	private function get_busting_file_url($locale)
	{
		$filename = $this->get_busting_file_name($locale);

		return $this->busting_url . $filename;
	}

	/**
	 * Get the local Facebook SDK file name.
	 *
	 * @param string $locale A locale string, like 'en_US'.
	 * @return string
	 * @since  3.2.0
	 *
	 */
	private function get_busting_file_name($locale)
	{
		return sprintf($this->filename, $locale);
	}

	/**
	 * Get the local Facebook SDK file path.
	 *
	 * @param string $locale A locale string, like 'en_US'.
	 * @return string
	 * @since  3.2.0
	 *
	 */
	private function get_busting_file_path($locale)
	{
		return $this->busting_path . $this->get_busting_file_name($locale);
	}

	/**
	 * Get the contents of a URL.
	 *
	 * @param string $url The URL to request.
	 * @return string|bool The contents on success. False on failure.
	 * @since  3.2.0
	 *
	 */
	protected function get_file_content($url)
	{
		try {
			$response = wp_remote_get($url);
		} catch( \Exception $e ) {
			\WGA_Plugin::app()->logger->error('Remote file could not be fetched. Response ' . $e->getMessage());

			return false;
		}

		if( is_wp_error($response) ) {
			\WGA_Plugin::app()->logger->error('Remote file could not be fetched. Response ' . $response->get_error_message());

			return false;
		}

		$contents = wp_remote_retrieve_body($response);

		if( !$contents ) {
			\WGA_Plugin::app()->logger->error('Remote file could not be fetched. Response ' . $response->get_error_message());

			return false;
		}

		return $contents;
	}

	/**
	 * Escape a file name, to be used in a regex pattern (delimiter is `/`).
	 * `%s` conversion specifications are protected.
	 *
	 * @param string $file_name The file name.
	 * @return string
	 * @since  3.2.0
	 *
	 */
	private function escape_file_name($file_name)
	{
		$file_name = explode('%s', $file_name);
		$file_name = array_map('preg_quote', $file_name);

		return implode('%s', $file_name);
	}
}
