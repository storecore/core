<?php
namespace StoreCore\FileSystem;

/**
 * IP Blacklist
 *
 * @author    Ward van der Put <Ward.van.der.Put@gmail.com>
 * @copyright Copyright (c) 2015 StoreCore
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package   StoreCore\Security
 * @version   0.1.0
 */
class Blacklist
{
    const VERSION = '0.1.0';

    /**
     * Check if an IP address is blacklisted.
     *
     * @param string $ip_address
     *   Remote IPv4 or IPv6 Internet Protocol (IP) address.
     *
     * @return bool
     *   Returns true if the IP address is blacklisted, otherwise false.  This
     *   method will also return false if the cache file does not exist,
     *   is empty, or somehow could not be processed.
     */
    public static function exists($ip_address)
    {
        $filename = STORECORE_FILESYSTEM_CACHE_DIR . 'data' . DIRECTORY_SEPARATOR . 'ip-blacklist.ini';
        if (!is_file($filename)) {
            return false;
        }

        $blacklist = file_get_contents($filename);
        if (false === $blacklist) {
            return false;
        }

        $blacklist = json_decode($blacklist);
        if (!is_array($blacklist)) {
            return false;
        }

        return in_array($ip_address, $blacklist);
    }
}
