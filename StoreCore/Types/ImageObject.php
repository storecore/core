<?php
namespace StoreCore\Types;

/**
 * Schema.org Image Object
 *
 * @author    Ward van der Put <Ward.van.der.Put@storecore.org>
 * @copyright Copyright (c) 2016 StoreCore
 * @license   https://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\Core
 * @see       https://schema.org/ImageObject
 * @version   0.1.0
 */
class ImageObject extends MediaObject
{
    const VERSION = '0.1.0';

    /**
     * @inheritDoc
     */
    protected $SupportedTypes = array(
        'barcode' => 'Barcode',
        'imageobject' => 'ImageObject',
    );

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->setType('ImageObject');
    }
}
