<?php
namespace StoreCore\Admin;

class Configurator
{
    /**
     * @var array $DefaultSettings
     *   Default settings from the global config.ini configuration file.
     */
    private $DefaultSettings = array(
        'StoreCore\\Database\\DRIVER'           => 'mysql',
        'StoreCore\\Database\\DEFAULT_HOST'     => 'localhost',
        'StoreCore\\Database\\DEFAULT_DATABASE' => 'test',
        'StoreCore\\Database\\DEFAULT_USERNAME' => 'root',
        'StoreCore\\Database\\DEFAULT_PASSWORD' => '',
    );

    /**
     * @var array $IgnoredSettings
     *   Settings that MAY be set manually in the config.ini configuration file
     *   and settings that SHOULD NOT be defined by config.php.
     */
    private $IgnoredSettings = array(
        'StoreCore\\KILL_SWITCH'      => true,
        'StoreCore\\MAINTENANCE_MODE' => true,
        'StoreCore\\NULL_LOGGER'      => true,
        'StoreCore\\STATISTICS'       => true,
    
        'StoreCore\\FileSystem\\CACHE_DIR'           => true,
        'StoreCore\\FileSystem\\LIBRARY_ROOT_DIR'    => true,
        'StoreCore\\FileSystem\\LOGS_DIR'            => true,
        'StoreCore\\FileSystem\\STOREFRONT_ROOT_DIR' => true,

        'StoreCore\\VERSION'       => true,
        'StoreCore\\MAJOR_VERSION' => true,
        'StoreCore\\MINOR_VERSION' => true,
        'StoreCore\\PATCH_VERSION' => true,
    );

    /**
     * @var array $Settings
     */
    private $Settings = array();

    /**
     * @param void
     * @return void
     */
    public function __construct()
    {
        $defined_constants = get_defined_constants(true);
        if (array_key_exists('user', $defined_constants)) {
            $defined_constants = $defined_constants['user'];
            foreach ($defined_constants as $name => $value) {
                $name = strtoupper($name);
                if (
                    strpos($name, 'StoreCore\\', 0) === 0
                    && strpos($name, 'StoreCore\\I18N\\', 0) !== 0
                ) {
                    if (
                        array_key_exists($name, $this->DefaultSettings) === false
                        || $this->DefaultSettings[$name] !== $value
                    ) {
                        $this->set($name, $value);
                    }
                }
            }
        }
    }

    /**
     * @uses \StoreCore\Admin\Configurator::set()
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Save the config.php configuration file.
     *
     * @param void
     * @return bool
     */
    public function save()
    {
        $file = '<?php' . "\n";
        foreach ($this->Settings as $name => $value)
        {
            /*
             * Namespace constants like \Foo\BAR_BAZ with a trailing backslash
             * must be defined without the trailing backslash in a PHP define:
             * define('Foo\\BAR_BAZ', 1).
             */
            $name = ltrim($name, '\\');
            $name = str_ireplace('\\', '\\\\', $name);
            
            if (is_numeric($value)) {
                $file .= "define('{$name}', {$value});";
            } else {
                if (is_array($value)) {
                    $value = json_encode($value);
                } elseif (is_object($value)) {
                    $value = serialize($value);
                }
                $value = str_ireplace('\\', '\\\\', $value);
                $file .= "define('{$name}', '{$value}');";
            }
            $file .= "\n";
        }

        $return = file_put_contents(\StoreCore\FileSystem\STOREFRONT_ROOT_DIR . 'config.php', $file, LOCK_EX);
        if ($return === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set($name, $value)
    {
        if (!array_key_exists($name, $this->IgnoredSettings)) {
            $this->Settings[$name] = $value;
        }
    }

    /**
     * Write a single setting to the config.php configuration file.
     *
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public static function write($name, $value)
    {
        $writer = new \StoreCore\Admin\Configurator();
        $writer->set($name, $value);
        return $writer->save();
    }
}
