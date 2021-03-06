<?php
namespace StoreCore;

/**
 * Asset Management
 *
 * @author    Ward van der Put <Ward.van.der.Put@storecore.org>
 * @copyright Copyright © 2015–2019 StoreCore™
 * @license   https://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\CMS
 * @version   0.1.0
 */
class Asset
{
    /**
     * @var string VERSION
     *   Semantic Version (SemVer).
     */
    const VERSION = '0.1.0';

    /**
     * @var string $FileName
     *   File name of the cacheable asset file.
     */
    private $FileName;

    /**
     * @var string $FileType
     *   File type of the asset file.
     */
    private $FileType;

    /**
     * @var array $Types
     *   Array matching lowercase file extensions to MIME types.
     */
    private $Types = array(
        'css'   => 'text/css; charset=utf-8',
        'eot'   => 'application/vnd.ms-fontobject',
        'gif'   => 'image/gif',
        'ico'   => 'image/x-icon',
        'jpeg'  => 'image/jpeg',
        'jpg'   => 'image/jpeg',
        'js'    => 'text/javascript; charset=utf-8',
        'otf'   => 'application/font-sfnt',
        'png'   => 'image/png',
        'svg'   => 'image/svg+xml',
        'ttc'   => 'application/font-sfnt',
        'ttf'   => 'application/font-sfnt',
        'webp'  => 'image/webp',
        'woff'  => 'application/font-woff',
        'woff2' => 'font/woff2',
    );

    /**
     * Silently publish a cacheable asset file.
     *
     * @param string $filename
     *   Name of the cached asset file.
     *
     * @param null|string $filetype
     *   Optional file extension for the cached asset.  If the file type is not
     *   provided, the constructor will try to derive the file type from the
     *   extension in the filename.
     *
     * @return self
     */
    public function __construct($filename, $filetype = null)
    {
        $this->setFileName($filename);

        if ($filetype === null) {
            $this->setFileType(pathinfo($filename, PATHINFO_EXTENSION));
        } else {
            $this->setFileType($filetype);
        }

        // Match generic favicon.ico to a domain-specific icon.
        if ($this->FileName === 'favicon.ico') {
            $this->setFileName(strip_tags($_SERVER['HTTP_HOST']) . '.ico');
            if (false === $this->fileExists()) {
                $this->setFileName('blank.ico');
            }
        }

        if ($this->fileExists()) {
            $this->fetchFile();
        }
    }

    /**
     * Check if an asset file exists.
     *
     * @param void
     *
     * @return bool
     *   Returns true if the asset file exists or false if the file does not
     *   exist or the file type is not supported.
     */
    private function fileExists()
    {
        if ($this->FileType === null) {
            return false;
        }

        return is_file(STORECORE_FILESYSTEM_STOREFRONT_ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR . $this->FileType . DIRECTORY_SEPARATOR . $this->FileName);
    }

    /**
     * Publish the asset file.
     *
     * @param void
     * @return void
     */
    private function fetchFile()
    {
        if ($this->FileType == 'css' || $this->FileType == 'js' || $this->FileType == 'svg') {
            ob_start('ob_gzhandler');
        }

        // Cache for 365 days = 31536000 seconds
        header('Cache-Control: public, max-age=31536000', true);
        header('Pragma: cache', true);
        header('Content-Type: ' . $this->Types[$this->FileType], true);
        header('X-Powered-By: StoreCore', true);

        $file = STORECORE_FILESYSTEM_STOREFRONT_ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR . $this->FileType . DIRECTORY_SEPARATOR . $this->FileName;

        $last_modified = filemtime($file);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $last_modified));

        $etag = md5_file($file, true);
        $etag = base64_encode($etag);
        $etag = rtrim($etag, '=');
        header('ETag: "' . $etag . '"');

        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $http_if_none_match = strip_tags($_SERVER['HTTP_IF_NONE_MATCH']);
            $http_if_none_match = trim($http_if_none_match);
            $http_if_none_match = trim($http_if_none_match, '"');
        } else {
            $http_if_none_match = false;
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified || $http_if_none_match == $etag) {
                header('HTTP/1.1 304 Not Modified', true);
                exit;
            }
        }

        readfile($file);
        exit;
    }

    /**
     * Set the asset filename.
     *
     * @param string $filename
     *   Filename of the cacheable asset file.
     *
     * @return void
     */
    private function setFileName($filename)
    {
        $filename = mb_strtolower($filename, 'UTF-8');
        $this->FileName = $filename;
    }

    /**
     * Set the asset filetype.
     *
     * @param string $filetype
     *   Filename extension without the leading dot.
     *
     * @return void
     */
    private function setFileType($filetype)
    {
        $filetype = strtolower($filetype);
        if (array_key_exists($filetype, $this->Types)) {
            $this->FileType = $filetype;
        }
    }
}
