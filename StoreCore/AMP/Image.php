<?php
namespace StoreCore\AMP;

use \StoreCore\Types\StringableInterface as StringableInterface;

/**
 * AMP Image <amp-img>
 *
 * @author    Ward van der Put <Ward.van.der.Put@storecore.org>
 * @copyright Copyright © 2017–2019 StoreCore™
 * @license   https://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\CMS
 * @see       https://amp.dev/documentation/components/amp-img
 * @see       https://amp.dev/documentation/examples/components/amp-img/
 * @see       https://amp.dev/documentation/guides-and-tutorials/start/create/include_image
 * @see       https://amp.dev/documentation/examples/style-layout/how_to_support_images_with_unknown_dimensions/
 * @version   0.1.0
 */
class Image extends \StoreCore\Image implements LayoutInterface, LightboxGalleryInterface, StringableInterface
{
    /**
     * @var string VERSION
     *   Semantic Version (SemVer).
     */
    const VERSION = '0.1.0';

    /**
     * @var \StoreCore\AMP\FallbackImage|null $Fallback
     *   Optional AMP fallback image.
     */
    private $Fallback;

    /**
     * @var string $Layout
     *   The `layout` attribute of the `<amp-img>` element.
     *   Defaults to `responsive`.
     */
    protected $Layout = LayoutInterface::LAYOUT_RESPONSIVE;

    /**
     * @var bool $Lightbox
     *   The image is part of a lightbox gallery (true) or not (default false).
     */
    protected $Lightbox = false;

    /**
     * @var array $SupportedLayouts
     *   Layouts supported by the `<amp-img>` element.
     */
    protected $SupportedLayouts = array(
        LayoutInterface::LAYOUT_FILL,
        LayoutInterface::LAYOUT_FIXED,
        LayoutInterface::LAYOUT_FIXED_HEIGHT,
        LayoutInterface::LAYOUT_FLEX_ITEM,
        LayoutInterface::LAYOUT_NODISPLAY,
        LayoutInterface::LAYOUT_RESPONSIVE,
    );

    /**
     * Get the <amp-img> AMP image element.
     *
     * @param void
     *
     * @return string
     *   Returns the AMP tag `<amp-img …>…</amp-img>` as a string.
     */
    public function __toString()
    {
        $str = '<amp-img ';

        if ($this->isLightbox()) {
            $str .= 'lightbox ';
        }

        $str .= 'alt="' . $this->getAlt() . '" layout="'. $this->getLayout()
            . '" height="' . $this->getHeight() . '" src="' . $this->getSource() . '" width="'
            . $this->getWidth() . '">';

        if ($this->Fallback !== null) {
            $str .= (string)$this->Fallback;
        }

        $str .= '</amp-img>';
        return $str;
    }

    /**
     * Get the layout attribute.
     *
     * @param void
     *
     * @return string
     *   Returns the string value of the AMP `layout` attribute.  Defaults to
     *   `responsive`: photos and other images are set to `layout="responsive"`
     *   in AMP by default for responsive web design (RWD).
     */
    public function getLayout()
    {
        return $this->Layout;
    }

    /**
     * @inheritDoc
     */
    public function isLightbox()
    {
        return $this->Lightbox;
    }

    /**
     * Add a fallback image.
     *
     * @param \StoreCore\AMP\FallbackImage $amp_fallback_image
     *   AMP fallback image.
     *
     * @return void
     */
    public function setFallback(FallbackImage $amp_fallback_image)
    {
        $this->Fallback = $amp_fallback_image;
    }

    /**
     * Set the layout attribute.
     *
     * @param string $layout
     *   String value for the AMP `layout` attribute.  Must be one of the values
     *   in the `$SupportedLayouts` array.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *   Throws an invalid argument exception if the `$layout` parameter is not
     *   a string or an unsupported layout.
     */
    public function setLayout($layout)
    {
        if (!is_string($layout)) {
            throw new \InvalidArgumentException();
        }
        $layout = strtolower($layout);

        if (!in_array($layout, $this->SupportedLayouts)) {
            throw new \InvalidArgumentException();
        }

        $this->Layout = $layout;
    }

    /**
     * @inheritDoc
     */
    public function setLightbox($lightbox = true)
    {
        $this->Lightbox = (bool)$lightbox;
    }
}
