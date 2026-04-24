<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fallback logos (public site + dashboard)
    |--------------------------------------------------------------------------
    |
    | When no custom logo is chosen in Admin → Settings → Branding, these
    | paths (relative to the `public` directory) are used. `asset()` builds
    | the full URL using APP_URL on each deploy, so logos stay correct in
    | production without hardcoding the domain.
    |
    | Set BRANDING_FALLBACK_HEADER_LOGO / BRANDING_FALLBACK_FOOTER_LOGO in .env
    | to point at your own files under public/ (or override footer only).
    |
    */

    'fallback_header_logo' => env('BRANDING_FALLBACK_HEADER_LOGO', 'images/branding/spotmee-default.svg'),

    'fallback_footer_logo' => env('BRANDING_FALLBACK_FOOTER_LOGO'),

    /*
    |--------------------------------------------------------------------------
    | Default social profile URLs (footer + contact “Follow us”)
    |--------------------------------------------------------------------------
    |
    | Used when a platform’s URL is not set in Admin. Override any key via
    | .env (e.g. SOCIAL_INSTAGRAM_URL) or replace in Application Settings.
    |
    */

    'default_social_urls' => [
        'instagram' => env('SOCIAL_INSTAGRAM_URL', 'https://www.instagram.com/spotmeehere?igsh=MzRlODBiNWFlZA%3D%3D'),
        'facebook' => env('SOCIAL_FACEBOOK_URL', 'https://www.facebook.com/people/Spotmee-Spotmee/61585694499124/?mibextid=ZbWKwL'),
        'snapchat' => env('SOCIAL_SNAPCHAT_URL', 'https://www.snapchat.com/@spotmeehere?share_id=b7BO8JtlDx0&locale=en-US'),
        // Public profile/company URL only (not linkedin.com/uas/login-submit). Override with SOCIAL_LINKEDIN_URL.
        'linkedin' => env('SOCIAL_LINKEDIN_URL', 'https://www.linkedin.com/in/spotmeehere'),
        'tiktok' => env('SOCIAL_TIKTOK_URL', 'https://www.tiktok.com/@spotmeehere?_r=1&_t=ZP-93EeCi4LGmc'),
    ],

];
