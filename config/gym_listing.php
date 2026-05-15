<?php

return [

    'weekday_keys' => [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ],

    'weekday_labels' => [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ],

    'default_availability_row' => [
        'closed' => false,
        'start' => '09:00',
        'end' => '17:00',
        'slot_minutes' => 60,
    ],

    'default_personal_training_availability_row' => [
        'closed' => false,
        'start' => '09:00',
        'end' => '17:00',
        'slot_minutes' => 60,
    ],

    'states' => [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
        'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District of Columbia', 'FL' => 'Florida',
        'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
        'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
        'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
        'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
        'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
        'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming',
    ],

    'facility_types' => [
        'single_room' => 'Single Room',
        'double_room' => 'Double Room',
        'multiple_rooms' => 'Multiple Rooms',
        'open_area' => 'Open Area',
        'garage' => 'Garage',
    ],

    'area_sizes' => [
        'sq_50_100' => '50-100 sq m',
        'sq_100_200' => '100-200 sq m',
        'sq_200_500' => '200-500 sq m',
        'sq_500_plus' => '500+ sq m',
    ],

    'pets_policies' => [
        'pets_allowed' => 'Pets Allowed',
        'no_pets_allowed' => 'No Pets Allowed',
        'service_animals_only' => 'Service Animals Only',
    ],

    'check_in_methods' => [
        'password_code' => 'By Password/Code',
        'contact_host_before' => 'Contact Host Before Arrival',
        'use_key' => 'Use Key',
        'reception_desk' => 'Reception Desk',
    ],

    'service_options' => [
        'gym' => 'Gym',
        'yoga' => 'Yoga',
        'kickboxing' => 'Kickboxing',
        'boxing' => 'Boxing',
        'fitness_class' => 'Fitness class',
        'group_class' => 'Group class',
        'cardio' => 'Cardio',
        'weights_lifting' => 'Weights lifting',
    ],

    'amenities' => [
        'bathroom' => 'Bathroom',
        'tv' => 'TV',
        'air_conditioning' => 'Air conditioning',
        'wifi' => 'Wifi',
        'security_cameras' => 'Security cameras',
        'water' => 'Water',
        'tea_making_machine' => 'Tea making machine',
        'coffee_making_machine' => 'Coffee making machine',
        'refrigerator' => 'Refrigerator',
        'heating' => 'Heating',
        'carbon_monoxide_alarm' => 'Carbon monoxide alarm',
        'smoke_alarm' => 'Smoke alarm',
        'sofa' => 'Sofa',
        'chair' => 'Chair',
        'host_greets_you' => 'Host greets you',
    ],

];
