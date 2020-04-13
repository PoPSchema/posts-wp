<?php

declare(strict_types=1);

namespace PoP\PostsWP\TypeAPIs;

class PostTypeAPIUtils
{
    protected static $cmsToPoPPostStatusConversion = [
        'publish' => POP_POSTSTATUS_PUBLISHED,
        'pending' => POP_POSTSTATUS_PENDING,
        'draft' => POP_POSTSTATUS_DRAFT,
        'trash' => POP_POSTSTATUS_TRASH,
    ];
    protected static $popToCMSPostStatusConversion;

    public static function init()
    {
        if (is_null(self::$popToCMSPostStatusConversion)) {
            self::$popToCMSPostStatusConversion = array_flip(self::$cmsToPoPPostStatusConversion);
        }
    }

    public static function convertPostStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return self::$cmsToPoPPostStatusConversion[$status];
    }
    public static function convertPostStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        self::init();
        return self::$popToCMSPostStatusConversion[$status];
    }
    public static function getCMSPostStatuses()
    {
        return array_keys(self::$cmsToPoPPostStatusConversion);
    }
}
