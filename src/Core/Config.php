<?php

namespace Sedalit\JaicpUsedeskIntegration\Core;

class Config {
    protected const CONFIG_FILE = "config.json";

    public static function self() {
        return new static();
    }

    public static function config() {
        return json_decode(file_get_contents(self::CONFIG_FILE), true);
    }

    public static function tokens($key = null) {
        return self::config()['tokens'][$key] ? self::config()['tokens'][$key] : self::config()['tokens'];
    }

    public static function usedesk($key = null) {
        return self::config()['usedesk'][$key] ? self::config()['usedesk'][$key] : self::config()['usedesk'];
    }

    public static function platforms($key = null) {
        return self::config()['usedesk']['platforms'][$key] ? self::config()['usedesk']['platforms'][$key] : self::config()['usedesk']['platforms'];
    }

    public static function channels($key = null) {
        return self::config()['usedesk']['channels'][$key] ? self::config()['usedesk']['channels'][$key] : self::config()['usedesk']['channels'];
    }

    public static function operatorGroups($key = null) {
        return self::config()['usedesk']['operatorGroups'][$key] ? self::config()['usedesk']['operatorGroups'][$key] : self::config()['usedesk']['operatorGroups']['defaultOperatorGroupID'];
    }

    public static function jaicp($key = null) {
        return self::config()['jaicp'][$key] ? self::config()['jaicp'][$key] : self::config()['jaicp'];
    }
}