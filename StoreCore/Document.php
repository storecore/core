<?php
namespace StoreCore;

/**
 * HTML5 Document with AMP Support
 *
 * @author    Ward van der Put <Ward.van.der.Put@storecore.org>
 * @copyright Copyright © 2015–2019 StoreCore™
 * @license   https://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\Core
 * @version   0.1.0
 */
class Document implements \StoreCore\Types\StringableInterface
{
    /**
     * @var string VERSION
     *   Semantic Version (SemVer).
     */
    const VERSION = '0.1.0';

    /**
      * @var bool $AcceleratedMobilePage
      *   Create an AMP HTML document (true) or not (default false).
      */
    protected $AcceleratedMobilePage = false;

    /**
     * @var string $Direction
     * @var string $Language
     * @var null|array $Links
     * @var null|array $MetaProperties
     * @var null|array $ScriptLinks
     * @var null|array $Scripts
     * @var null|array $ScriptsDeferred
     * @var array $Sections
     * @var string $Style
     * @var string $Title
     */
    protected $Direction = 'ltr';
    protected $Language = 'en-GB';
    protected $Links;
    protected $MetaProperties;
    protected $ScriptLinks;
    protected $Scripts;
    protected $ScriptsDeferred;
    protected $Sections = array();
    protected $Style = '';
    protected $Title = 'StoreCore';

    /**
     * @var array $MetaData
     *   Key/value pairs for `<meta name="…" content="…">` meta tags.
     *   The recommended `viewport` for AMP pages is parsed first.
     *   Other meta tags are listed in alphabetical order.
     */
    protected $MetaData = array(
        'viewport' => 'width=device-width,initial-scale=1,minimum-scale=1',

        'apple-mobile-web-app-capable' => 'yes',
        'apple-mobile-web-app-status-bar-style' => 'black-translucent',
        'format-detection' => 'telephone=no',
        'generator' => 'StoreCore',
        'handheldfriendly' => 'true',
        'mobileoptimized' => '320',
        'rating' => 'general',
        'robots' => 'index,follow',
    );

    /**
     * Create an HTML document.
     *
     * @param string|null $title
     *   Title of the document to include in the HTML `<title>...</title>` tag.
     *
     * @return self
     */
    public function __construct($title = null)
    {
        if ($title !== null) {
            $this->setTitle($title);
        }
    }

    /**
     * Convert the document to an HTML string.
     *
     * @param void
     *
     * @return string
     *   Returns the document in HTML5 or AMP HTML.
     *
     * @uses getDocument()
     */
    public function __toString()
    {
        return $this->getDocument();
    }

    /**
     * Add a link to an external resource.
     *
     * @param \StoreCore\Types\Link $link
     *   Link object to add as a `<link>` to the `<head>…</head>` container.
     *
     * @return void
     */
    public function addLink(\StoreCore\Types\Link $link)
    {
        // MD5 hash key of the lowercase URL, where https:// ≡ http:// ≡ //
        $key = $link->getHref();
        $key = str_ireplace('https://', '//', $key);
        $key = str_ireplace('http://', '//', $key);
        $key = mb_strtolower($key, 'UTF-8');
        $key = md5($key);
        $this->Links[$key] = $link;
    }

    /**
     * Add meta data for a <meta name="..." content="..."> meta tag.
     *
     * @param string $name
     *   Case-insensitive name (or key) of the meta tag.
     *
     * @param string $content
     *   Content (or value) of the meta tag.
     *
     * @return void
     */
    public function addMetaData($name, $content)
    {
        $name = trim($name);
        $name = strtolower($name);
        $this->MetaData[$name] = $content;
    }

    /**
     * Add meta property data for a <meta property="..." content="..."> meta tag.
     *
     * @param string $property
     *   Case-insensitive property name (or key) of the meta property tag.
     *
     * @param string $content
     *   Content (or value) of the meta tag.
     *
     * @return void
     */
    public function addMetaProperty($property, $content)
    {
        $name = trim($property);
        $name = strtolower($property);
        $this->MetaProperties[$property] = $content;
    }

    /**
     * Add inline JavaScript.
     *
     * @param string $script
     *   Inline JavaScript, without the enclosing <script>...</script> tags.
     *
     * @param bool $defer
     *   If set to true (default), JavaScript execution is deferred by moving
     *   the script to the end of the HTML document.  This RECOMMENDED setting
     *   usually speeds op client-side page rendering.
     *
     * @return void
     */
    public function addScript($script, $defer = true)
    {
        if ($defer !== false) {
            $this->ScriptsDeferred[] = $script;
        } else {
            $this->Scripts[] = $script;
        }
    }

    /**
     * Add a link to an external client-side script.
     *
     * @param string $src
     *   Absolute or relative URL of the script source file for the `src`
     *   attribute in a `<script src="...">` tag.
     *
     * @param bool $defer
     *   Adds the `defer` attribute (default true) or omits it (false).
     *
     * @param bool $async
     *   Adds the `async` attribute (true) or omits it (default false).
     *   If the `$defer` and `$async` parameters are both set to true, the
     *   `$async` parameter is ignored (and the `async` attribute is reset
     *   to the default value false).
     *
     * @return void
     */
    public function addScriptLink($src, $defer = true, $async = false)
    {
        $src = trim($src);
        $key = mb_strtolower($src, 'UTF-8');
        $key = md5($key);

        if ($defer == true) {
            $async = false;
        }

        $this->ScriptLinks[$key] = array(
            'src'   => $src,
            'defer' => $defer,
            'async' => $async,
        );
    }

    /**
     * Add a section to the document body.
     *
     * @param string $content
     *   Content for a new HTML container.  Please note that multiple sections
     *   are parsed and displayed in the order they are added.
     *
     * @param string|false|null $container
     *   Enclosing parent container for the new content.  Defaults to `section`
     *   for a generic `<section>...</section>` container.  This parameter MAY
     *   be set to null, to false or to an empty string if the parent container
     *   is to be omitted.
     *
     * @return void
     */
    public function addSection($content, $container = 'section')
    {
        if (empty($container) || $container === false) {
            $container = null;
        } else {
            $container = trim($container);
            $container = strtolower($container);
            $container = ltrim($container, '<');
            $container = rtrim($container, '>');
        }

        if ($container === null) {
            $this->Sections[] = $content;
        } else {
            $this->Sections[] = '<' . $container . '>' . $content . '</' . $container . '>';
        }
    }

    /**
     * Add internal (embedded) CSS code.
     *
     * @param string $css
     *
     * @return void
     */
    public function addStyle($css)
    {
        $css = strip_tags($css);
        $css = trim($css);
        $css = str_ireplace("\r\n", null, $css);
        $css = str_ireplace("\n", null, $css);
        $css = str_ireplace(' {', '{', $css);
        $css = str_ireplace('{ ', '{', $css);
        $css = str_ireplace('} ', '}', $css);
        $css = str_ireplace(': ', ':', $css);
        $css = str_ireplace('; ', ';', $css);
        $css = str_ireplace(';}', '}', $css);
        $this->Style .= $css;
    }

    /**
     * Enable AMP HTML.
     *
     * @param bool $use_amp_html
     *   Use Google AMP HTML for Accelerated Mobile Pages (default true) or not
     *   (false).
     *
     * @return void
     */
    public function amplify($use_amp_html = true)
    {
        $this->AcceleratedMobilePage = (bool)$use_amp_html;
    }

    /**
     * Get the document <body> container.
     *
     * @param void
     *
     * @return string
     *   Returns the `<body>...</body>` container as a string.
     */
    public function getBody()
    {
        return '<body>' . implode($this->Sections) . '</body>';
    }

    /**
     * Get the full HTML document.
     *
     * @param void
     *
     * @return string
     *   Returns the full `<html>...</html>` container with a `DOCTYPE`
     *   declaration as a string.
     *
     * @uses getBody()
     *
     * @uses getHead()
     */
    public function getDocument()
    {
        $html  = '<!DOCTYPE html>';

        $html .= '<html';
        if ($this->AcceleratedMobilePage) {
            $html .= ' amp';
        }
        $html .= ' dir="' . $this->Direction . '" lang="' . $this->Language . '">';

        $html .= $this->getHead();
        $html .= $this->getBody();

        if (!$this->AcceleratedMobilePage && $this->ScriptsDeferred !== null) {
            $html .= '<script>';
            $html .= implode($this->ScriptsDeferred);
            $html .= '</script>';
        }

        $html .= '</html>';
        return $html;
    }

    /**
     * Get the document <head> container.
     *
     * @param void
     *
     * @return string
     *   Returns the `<head>…</head>` container as a string.
     */
    public function getHead()
    {
        $head  = '<head>';

        // The first tag should be the `meta charset` tag, followed by any remaining `meta` tags.
        $head .= '<meta charset="utf-8">';
        foreach ($this->MetaData as $name => $content) {
            $head .= '<meta name="' . $name . '" content="' . $content . '">';
        }

        if ($this->AcceleratedMobilePage) {
            $head .= '<link rel="preload" as="script" href="https://cdn.ampproject.org/v0.js">';
            $head .= '<link rel="preconnect dns-prefetch" href="https://fonts.gstatic.com/" crossorigin>';
            $head .= '<script async src="https://cdn.ampproject.org/v0.js"></script>';
        }

        if ($this->Links !== null) {
            foreach ($this->Links as $link) {
                $head .= (string)$link;
            }
        }

        $head .= '<title>' . $this->Title . '</title>';

        if ($this->ScriptLinks !== null) {
            foreach ($this->ScriptLinks as $link) {
                if ($link['async'] === true) {
                    $head .= '<script async';
                } elseif ($link['defer'] === false) {
                    $head .= '<script';
                } else {
                    $head .= '<script defer';
                }
                $head .= ' src="' . $link['src'] . '"></script>';
            }
        }

        if (!empty($this->Style)) {
            if ($this->AcceleratedMobilePage) {
                $head .= '<style amp-custom>';
            } else {
                $head .= '<style>';
            }
            $head .= $this->Style;
            $head .= '</style>';
        }
        if ($this->AcceleratedMobilePage) {
            $head .= '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
        }

        if ($this->MetaProperties !== null) {
            foreach ($this->MetaProperties as $property => $content) {
                $head .= '<meta property="' . $property . '" content="' . $content . '">';
            }
        }

        if ($this->Scripts !== null) {
            $head .= '<script>';
            $head .= implode($this->Scripts);
            $head .= '</script>';
        }

        $head .= '</head>';
        return $head;
    }

    /**
     * Add a document description.
     *
     * @param string $description
     *   Short description of the document.
     *
     * @return void
     *
     * @uses \StoreCore\Document::addMetaData()
     *
     * @uses \StoreCore\Document::addMetaProperty()
     */
    public function setDescription($description)
    {
        $description = trim($description);
        $this->addMetaData('description', $description);
        $this->addMetaProperty('og:description', $description);
    }

    /**
     * Set the document language.
     *
     * @param string $language_code
     *   BCP 47 language tag as a string, for example 'de' for German or
     *   'en-US' for American English.
     *
     * @return void
     */
    public function setLanguage($language_code)
    {
        $language_code = str_ireplace('_', '-', $language_code);
        $language_codes = explode('-', $language_code);
        if (count($language_codes) === 2) {
            $language_code = strtolower($language_codes[0]) . '-' . strtoupper($language_codes[1]);
        }
        $this->Language = $language_code;
    }

    /**
     * Set the theme color.
     *
     * @param string $color
     *   Color definition as a string.
     *
     * @return void
     */
    public function setThemeColor($color)
    {
        $this->addMetaData('msapplication-navbutton-color', $color);
        $this->addMetaData('theme-color', $color);
    }

    /**
     * Set the document title.
     *
     * @param string $title
     *   Title of the document as a string.
     *
     * @return void
     */
    public function setTitle($title)
    {
        $title = trim($title);
        $this->Title = $title;
        $this->addMetaProperty('og:title', $title);
    }
}
