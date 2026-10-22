<?php

return [
    'name' => "King's Cross Driving School",
    'tagline' => 'Safe, friendly and professional driving lessons designed to help you build confidence and prepare for your driving test.',
    'price_line' => '2 hours a lesson £80. Test and Pass Plus by negotiation.',
    'phone_display' => '07908 726875',
    'phone_tel' => '07908726875',
    'email' => 'kingscrossds@gmail.com',
    'address' => '26 Stranraer Way, London N1 0DR',
    'hours_label' => 'Monday to Sunday, 6:00am to 10:00pm',
    'whatsapp' => '447908726875',
    'transmission' => 'Manual',

    'hours' => [
        'days' => [1, 2, 3, 4, 5, 6, 7],
        'open' => '06:00',
        'close' => '22:00',
        'interval_minutes' => 60,
        'min_notice_hours' => 3,
        'horizon_days' => 60,
        'label' => 'Monday to Sunday, 6:00am to 10:00pm',
    ],

    'hold_minutes' => 40,

    // Charged online via PayPal. The rest of the lesson price is paid to the instructor before the lesson starts.
    'online_deposit_pence' => 1000,

    'postcodes' => [
        'WC1', 'EC1', 'N1C', 'N1', 'N2', 'N4', 'N5', 'N6', 'N7', 'N8',
        'N10', 'N15', 'N16', 'N19', 'NW1', 'NW3', 'NW5', 'NW6', 'NW8', 'E1', 'E2', 'E5', 'E8',
    ],

    'test_centres' => [
        'Wood Green',
        'Tottenham',
        'Mill Hill',
        'Hendon',
        'Barnet',
        'Goodmayes',
        'Wanstead',
        'Chingford',
        'Enfield',
        'Borehamwood',
        'Pinner',
        'Greenford',
        'Southall',
    ],

    'packages' => [
        'lesson' => [
            'name' => 'Driving lesson',
            'short' => 'A 2-hour manual lesson. This is the shortest session.',
            'details' => 'You learn in a manual car with your instructor, from the first controls through to test-ready driving. Each booked session is 2 hours, and you are picked up inside the covered postcodes.',
            'hours' => 2,
            'price' => 8000,
            'highlight' => true,
            'badge' => 'Standard lesson',
            'icon' => 'car',
            'tone' => 'rose',
            'points' => [
                '2 hours behind the wheel',
                'Manual car',
                'Pickup inside covered postcodes',
                'Shortest session is 2 hours',
            ],
        ],
        'test' => [
            'name' => 'Practical test',
            'short' => '3 hours on test day, including the drive to the centre.',
            'details' => 'The practical test package is 3 hours for £200. Choose the test centre when you book. Your instructor covers the London centres listed on the areas page. This price can be negotiated by phone.',
            'hours' => 3,
            'price' => 20000,
            'highlight' => false,
            'badge' => 'Test day',
            'icon' => 'road',
            'tone' => 'blue',
            'points' => [
                '3 hours in total',
                'Use of the lesson car',
                'Choose a covered test centre',
                '£10 online to book, balance on the day',
            ],
        ],
        'pass_plus' => [
            'name' => 'Pass Plus',
            'short' => '6 hours of post-test training, with the DVSA certificate.',
            'details' => 'Pass Plus is 6 hours for £300 and includes the DVSA certificate. It is for drivers who have passed the practical test and want structured time on a wider range of roads. This price can be negotiated by phone.',
            'hours' => 6,
            'price' => 30000,
            'highlight' => false,
            'badge' => 'After you pass',
            'icon' => 'trophy',
            'tone' => 'green',
            'points' => [
                '6 hours of lessons',
                'DVSA certificate included',
                'Manual car',
                'Booked as one session',
            ],
        ],
    ],

    // Homepage marketing cards still use the design layout, mapped to the priced packages.
    'lessons' => [
        [
            'slug' => 'lesson',
            'name' => 'Driving lesson',
            'short' => 'A 2-hour manual lesson for £80. This is the shortest session.',
            'icon' => 'car',
            'tone' => 'rose',
            'price' => 8000,
            'hours' => 2,
        ],
        [
            'slug' => 'test',
            'name' => 'Practical test',
            'short' => '3 hours on test day for £200, including the drive to the centre.',
            'icon' => 'road',
            'tone' => 'blue',
            'price' => 20000,
            'hours' => 3,
        ],
        [
            'slug' => 'pass_plus',
            'name' => 'Pass Plus',
            'short' => '6 hours of post-test training for £300, with the DVSA certificate.',
            'icon' => 'trophy',
            'tone' => 'green',
            'price' => 30000,
            'hours' => 6,
        ],
    ],

    'benefits' => [
        [
            'title' => 'High Pass Rate',
            'text' => 'Get on the road with confidence.',
            'icon' => 'trophy',
        ],
        [
            'title' => 'DVSA Approved',
            'text' => 'Qualified & experienced instructors.',
            'icon' => 'user',
        ],
        [
            'title' => 'Flexible Lessons',
            'text' => 'Weekdays & weekends available.',
            'icon' => 'clock',
        ],
        [
            'title' => 'Local Coverage',
            'text' => "King's Cross & surrounding areas.",
            'icon' => 'map-pin',
        ],
    ],

    'about_points' => [
        'Patient and friendly instructors',
        'Local knowledge of test routes',
        'Flexible lesson times to suit you',
        'Block booking discounts available',
        'Manual driving lessons',
        'High success rate',
    ],

    'about_intro' => "King's Cross Driving School helps learners build safe, confident driving habits on real London roads. Lessons are one-to-one with DVSA-approved instructors, clear feedback and a calm pace that suits beginners and returning drivers.",

    'faqs' => [
        [
            'q' => 'How much does a driving lesson cost?',
            'a' => 'A driving lesson is 2 hours for £80. That is the shortest session. The practical test package is 3 hours for £200. Pass Plus is 6 hours for £300 and includes the DVSA certificate. Test and Pass Plus can be negotiated by phone.',
        ],
        [
            'q' => 'Do you offer automatic lessons?',
            'a' => 'Lessons are in a manual car. Automatic lessons are not offered.',
        ],
        [
            'q' => 'Where do you pick pupils up?',
            'a' => 'Pickup is inside these London postcodes: WC1, EC1, N1, N1C, N2, N4, N5, N6, N7, N8, N10, N15, N16, N19, NW1, NW3, NW5, NW6, NW8, E1, E2, E5 and E8. You can enter the area, such as WC1, or the full postcode.',
        ],
        [
            'q' => 'Which practical test centres do you cover?',
            'a' => 'Wood Green, Tottenham, Mill Hill, Hendon, Barnet, Goodmayes, Wanstead, Chingford, Enfield, Borehamwood, Pinner, Greenford and Southall.',
        ],
        [
            'q' => 'How do I pay and keep a time?',
            'a' => 'Choose a free slot and pay a £10 deposit online with PayPal. The time is held while you pay, and it is confirmed when the deposit goes through. Pay the remaining lesson price to your instructor before the lesson starts. Call 07908 726875 if you would rather arrange the lesson by phone.',
        ],
        [
            'q' => 'What are the lesson hours?',
            'a' => 'Lessons are available Monday to Sunday, 6:00am to 10:00pm. A lesson must finish by 10:00pm, so a 6-hour Pass Plus session starts no later than 4:00pm.',
        ],
    ],

    'testimonials' => [
        [
            'name' => 'Sean Kendall',
            'result' => 'Passed first time',
            'quote' => 'Good instruction and clear assessment of readiness made sure that I was prepared to pass on my first try. Gopal worked with my schedule and made sure I was well prepared.',
        ],
        [
            'name' => 'Anton Prokhorov',
            'result' => 'Passed first time',
            'quote' => 'I passed my driving test today on my first attempt and had a great experience throughout the whole learning process. I highly recommend Kings Cross Driving School!',
        ],
        [
            'name' => 'Kai Yang',
            'result' => '5 star Google review',
            'quote' => 'Very good instructor.',
        ],
    ],
];
