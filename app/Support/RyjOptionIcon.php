<?php

namespace App\Support;

/**
 * Maps option keys (facility, service, amenity, tier, etc.) to copied plugin PNGs under public/images/rent-your-jim.
 */
final class RyjOptionIcon
{
    /**
     * @var array<string, string> option key => filename in public/images/rent-your-jim
     */
    private const KEY_TO_FILE = [
        'boxing' => 'boxing.png',
        'yoga' => 'yoga.png',
        'cardio' => 'cardio.png',
        'gym' => 'gym.png',
        'kickboxing' => 'Kickboxing.png',
        'fitness' => 'fitness.png',
        'fitness_class' => 'fitness-class.png',
        'group_class' => 'group_class.png',
        'group_classes' => 'group_class.png',
        'weights_lifting' => 'weights_lifting.png',
        'personal_training' => 'personal-training.png',
        'personal-training' => 'personal-training.png',
        'crossfit' => 'fitness.png',
        'single_room' => 'single_room.png',
        'double_room' => 'double_room.png',
        'multiple_rooms' => 'multiple_rooms.png',
        'open_area' => 'open-area.png',
        'garage' => 'garage.png',
        'area_size' => 'area_size.png',
        'pets_allowed' => 'pets_allowed.png',
        'no_pets_allowed' => 'no_pets.png',
        'service_animals_only' => 'service_animals.png',
        'password_code' => 'password.png',
        'contact_host_before' => 'contact_host.png',
        'use_key' => 'key.png',
        'reception_desk' => 'reception.png',
        'silver' => 'silver.png',
        'gold' => 'gold.png',
        'platinum' => 'platinum.png',
        'bathroom' => 'bathroom.png',
        'tv' => 'tv.png',
        'wifi' => 'wifi.png',
        'water' => 'water.png',
        'refrigerator' => 'fridge.png',
        'heating' => 'heating.png',
        'security_cameras' => 'security_cameras.png',
        'smoke_alarm' => 'smoke_alarm.png',
        'carbon_monoxide_alarm' => 'co_alarm.png',
        'sofa' => 'sofa.png',
        'chair' => 'chair.png',
        'host_greets_you' => 'host_greeting.png',
        'coffee_making_machine' => 'coffee_machine.png',
        'tea_making_machine' => 'tea_machine.png',
    ];

    public static function publicUrl(?string $key): ?string
    {
        if ($key === null || $key === '') {
            return null;
        }
        $normalized = strtolower(str_replace([' ', '-'], ['_', '_'], (string) $key));
        $file = self::KEY_TO_FILE[$normalized] ?? null;
        if ($file === null) {
            return null;
        }
        $path = public_path('images/rent-your-jim'.DIRECTORY_SEPARATOR.$file);

        return is_file($path) ? asset('images/rent-your-jim/'.$file) : null;
    }
}
