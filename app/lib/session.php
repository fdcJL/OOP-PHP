<?php
namespace App\Lib;

class Session {
    /**
     * Start the session if not already started.
     */
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Write data to the session.
     *
     * @param string $key   The session key
     * @param mixed  $value The value to store in the session
     */
    public static function write($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Read data from the session.
     *
     * @param string $key     The session key
     * @param mixed  $default Default value to return if key does not exist
     * @return mixed|null     The value from session or default if not found
     */
    public static function read($key, $default = null) {
        self::start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    /**
     * Check if a session key exists.
     *
     * @param string $key The session key
     * @return bool       True if key exists, false otherwise
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove data from the session.
     *
     * @param string $key The session key to remove
     */
    public static function delete($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Destroy all session data.
     */
    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }
}
?>