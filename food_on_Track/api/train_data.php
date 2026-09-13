<?php
/**
 * train_data.php
 * ---------------------------------------------------------
 * Bangladesh Railway train schedule used by trains.php and
 * the front-end search box.
 *
 * Each train entry:
 *   no        => train number (int)
 *   name      => train name
 *   from      => origin station
 *   departure => departure time (HH:MM) or '' if not published
 *   to        => destination station
 *   arrival   => arrival time (HH:MM) or '' if not published
 *   off_day   => weekly off day, or 'None'
 *   route     => ordered list of stoppage station names (origin -> destination)
 *
 * Times are as published by Bangladesh Railway; the "off day" is the
 * day the train does NOT run. Where the source schedule has no time ("—")
 * the value is an empty string and the UI shows a dash.
 */

function train_schedule(): array
{
    return [
        [
            'no' => 701, 'name' => 'Suborno Express',
            'from' => 'Chattogram', 'departure' => '07:00',
            'to' => 'Dhaka', 'arrival' => '11:55', 'off_day' => 'Monday',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Narsingdi', 'Dhaka'],
        ],
        [
            'no' => 702, 'name' => 'Suborno Express',
            'from' => 'Dhaka', 'departure' => '16:30',
            'to' => 'Chattogram', 'arrival' => '21:25', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Narsingdi', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 703, 'name' => 'Mahanagar Godhuli',
            'from' => 'Chattogram', 'departure' => '15:00',
            'to' => 'Dhaka', 'arrival' => '20:45', 'off_day' => 'None',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Narsingdi', 'Dhaka'],
        ],
        [
            'no' => 704, 'name' => 'Mahanagar Provati',
            'from' => 'Dhaka', 'departure' => '07:45',
            'to' => 'Chattogram', 'arrival' => '13:35', 'off_day' => 'None',
            'route' => ['Dhaka', 'Narsingdi', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 707, 'name' => 'Tista Express',
            'from' => 'Dhaka', 'departure' => '07:30',
            'to' => 'Dewanganj Bazar', 'arrival' => '12:50', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Islampur', 'Dewanganj Bazar'],
        ],
        [
            'no' => 708, 'name' => 'Tista Express',
            'from' => 'Dewanganj Bazar', 'departure' => '15:00',
            'to' => 'Dhaka', 'arrival' => '20:30', 'off_day' => 'Monday',
            'route' => ['Dewanganj Bazar', 'Islampur', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 709, 'name' => 'Parabat Express',
            'from' => 'Dhaka', 'departure' => '06:30',
            'to' => 'Sylhet', 'arrival' => '13:00', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Bhairab Bazar', 'Brahmanbaria', 'Akhaura', 'Kulaura', 'Sreemangal', 'Sylhet'],
        ],
        [
            'no' => 710, 'name' => 'Parabat Express',
            'from' => 'Sylhet', 'departure' => '16:00',
            'to' => 'Dhaka', 'arrival' => '22:40', 'off_day' => 'Monday',
            'route' => ['Sylhet', 'Sreemangal', 'Kulaura', 'Akhaura', 'Brahmanbaria', 'Bhairab Bazar', 'Dhaka'],
        ],
        [
            'no' => 711, 'name' => 'Upakul Express',
            'from' => 'Noakhali', 'departure' => '06:00',
            'to' => 'Dhaka', 'arrival' => '11:20', 'off_day' => 'Thursday',
            'route' => ['Noakhali', 'Maijdee Court', 'Chandpur', 'Laksham', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Narsingdi', 'Dhaka'],
        ],
        [
            'no' => 712, 'name' => 'Upakul Express',
            'from' => 'Dhaka', 'departure' => '15:10',
            'to' => 'Noakhali', 'arrival' => '20:40', 'off_day' => 'Wednesday',
            'route' => ['Dhaka', 'Narsingdi', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Laksham', 'Chandpur', 'Maijdee Court', 'Noakhali'],
        ],
        [
            'no' => 713, 'name' => 'Karatoya Express',
            'from' => 'Santahar', 'departure' => '09:00',
            'to' => 'Burimari', 'arrival' => '15:00', 'off_day' => 'None',
            'route' => ['Santahar', 'Bogra', 'Gaibandha', 'Lalmonirhat', 'Burimari'],
        ],
        [
            'no' => 714, 'name' => 'Karatoya Express',
            'from' => 'Burimari', 'departure' => '15:40',
            'to' => 'Santahar', 'arrival' => '22:00', 'off_day' => 'None',
            'route' => ['Burimari', 'Lalmonirhat', 'Gaibandha', 'Bogra', 'Santahar'],
        ],
        [
            'no' => 717, 'name' => 'Jayantika Express',
            'from' => 'Dhaka', 'departure' => '12:00',
            'to' => 'Sylhet', 'arrival' => '19:40', 'off_day' => 'None',
            'route' => ['Dhaka', 'Bhairab Bazar', 'Brahmanbaria', 'Akhaura', 'Kulaura', 'Sreemangal', 'Sylhet'],
        ],
        [
            'no' => 718, 'name' => 'Jayantika Express',
            'from' => 'Sylhet', 'departure' => '12:00',
            'to' => 'Dhaka', 'arrival' => '19:15', 'off_day' => 'None',
            'route' => ['Sylhet', 'Sreemangal', 'Kulaura', 'Akhaura', 'Brahmanbaria', 'Bhairab Bazar', 'Dhaka'],
        ],
        [
            'no' => 719, 'name' => 'Paharika Express',
            'from' => 'Chattogram', 'departure' => '07:50',
            'to' => 'Sylhet', 'arrival' => '15:50', 'off_day' => 'Monday',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Kulaura', 'Sreemangal', 'Sylhet'],
        ],
        [
            'no' => 720, 'name' => 'Paharika Express',
            'from' => 'Sylhet', 'departure' => '10:30',
            'to' => 'Chattogram', 'arrival' => '18:55', 'off_day' => 'Wednesday',
            'route' => ['Sylhet', 'Sreemangal', 'Kulaura', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 721, 'name' => 'Mohanagar Express',
            'from' => 'Chattogram', 'departure' => '12:30',
            'to' => 'Dhaka', 'arrival' => '18:40', 'off_day' => 'Sunday',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Narsingdi', 'Dhaka'],
        ],
        [
            'no' => 722, 'name' => 'Mohanagar Express',
            'from' => 'Dhaka', 'departure' => '21:20',
            'to' => 'Chattogram', 'arrival' => '06:40', 'off_day' => 'Sunday',
            'route' => ['Dhaka', 'Narsingdi', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 723, 'name' => 'Udayan Express',
            'from' => 'Chattogram', 'departure' => '21:45',
            'to' => 'Sylhet', 'arrival' => '05:45', 'off_day' => 'Wednesday',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Akhaura', 'Brahmanbaria', 'Kulaura', 'Sreemangal', 'Sylhet'],
        ],
        [
            'no' => 724, 'name' => 'Udayan Express',
            'from' => 'Sylhet', 'departure' => '22:00',
            'to' => 'Chattogram', 'arrival' => '05:35', 'off_day' => 'Saturday',
            'route' => ['Sylhet', 'Sreemangal', 'Kulaura', 'Brahmanbaria', 'Akhaura', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 725, 'name' => 'Meghna Express',
            'from' => 'Chattogram', 'departure' => '17:15',
            'to' => 'Chandpur', 'arrival' => '21:00', 'off_day' => 'None',
            'route' => ['Chattogram', 'Feni', 'Cumilla', 'Laksham', 'Chandpur'],
        ],
        [
            'no' => 726, 'name' => 'Meghna Express',
            'from' => 'Chandpur', 'departure' => '05:00',
            'to' => 'Chattogram', 'arrival' => '08:55', 'off_day' => 'None',
            'route' => ['Chandpur', 'Laksham', 'Cumilla', 'Feni', 'Chattogram'],
        ],
        [
            'no' => 727, 'name' => 'Rupsha Express',
            'from' => 'Khulna', 'departure' => '07:15',
            'to' => 'Chilahati', 'arrival' => '16:00', 'off_day' => 'Thursday',
            'route' => ['Khulna', 'Jessore', 'Kushtia', 'Poradaha', 'Rajshahi', 'Natore', 'Bogra', 'Chilahati'],
        ],
        [
            'no' => 728, 'name' => 'Rupsha Express',
            'from' => 'Chilahati', 'departure' => '08:00',
            'to' => 'Khulna', 'arrival' => '16:00', 'off_day' => 'Thursday',
            'route' => ['Chilahati', 'Bogra', 'Natore', 'Rajshahi', 'Poradaha', 'Kushtia', 'Jessore', 'Khulna'],
        ],
        [
            'no' => 729, 'name' => 'Titas Commuter',
            'from' => 'Dhaka', 'departure' => '',
            'to' => 'Brahmanbaria', 'arrival' => '', 'off_day' => '',
            'route' => ['Dhaka', 'Narsingdi', 'Bhairab Bazar', 'Brahmanbaria'],
        ],
        [
            'no' => 730, 'name' => 'Titas Commuter',
            'from' => 'Brahmanbaria', 'departure' => '',
            'to' => 'Dhaka', 'arrival' => '', 'off_day' => '',
            'route' => ['Brahmanbaria', 'Bhairab Bazar', 'Narsingdi', 'Dhaka'],
        ],
        [
            'no' => 731, 'name' => 'Barendra Express',
            'from' => 'Rajshahi', 'departure' => '15:00',
            'to' => 'Chilahati', 'arrival' => '21:50', 'off_day' => 'Sunday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Gaibandha', 'Chilahati'],
        ],
        [
            'no' => 732, 'name' => 'Barendra Express',
            'from' => 'Chilahati', 'departure' => '05:50',
            'to' => 'Rajshahi', 'arrival' => '12:10', 'off_day' => 'Sunday',
            'route' => ['Chilahati', 'Gaibandha', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 733, 'name' => 'Titumir Express',
            'from' => 'Rajshahi', 'departure' => '06:20',
            'to' => 'Chilahati', 'arrival' => '13:10', 'off_day' => 'Wednesday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Gaibandha', 'Chilahati'],
        ],
        [
            'no' => 734, 'name' => 'Titumir Express',
            'from' => 'Chilahati', 'departure' => '14:20',
            'to' => 'Rajshahi', 'arrival' => '21:00', 'off_day' => 'Wednesday',
            'route' => ['Chilahati', 'Gaibandha', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 735, 'name' => 'Sagardari Express',
            'from' => 'Khulna', 'departure' => '16:00',
            'to' => 'Rajshahi', 'arrival' => '21:40', 'off_day' => 'Monday',
            'route' => ['Khulna', 'Jessore', 'Kushtia', 'Poradaha', 'Rajshahi'],
        ],
        [
            'no' => 736, 'name' => 'Sagardari Express',
            'from' => 'Rajshahi', 'departure' => '06:40',
            'to' => 'Khulna', 'arrival' => '12:45', 'off_day' => 'Monday',
            'route' => ['Rajshahi', 'Poradaha', 'Kushtia', 'Jessore', 'Khulna'],
        ],
        [
            'no' => 737, 'name' => 'Ekota Express',
            'from' => 'Dhaka', 'departure' => '10:15',
            'to' => 'Panchagarh', 'arrival' => '20:05', 'off_day' => 'None',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Dinajpur', 'Thakurgaon', 'Panchagarh'],
        ],
        [
            'no' => 738, 'name' => 'Ekota Express',
            'from' => 'Panchagarh', 'departure' => '21:00',
            'to' => 'Dhaka', 'arrival' => '06:10', 'off_day' => 'None',
            'route' => ['Panchagarh', 'Thakurgaon', 'Dinajpur', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 739, 'name' => 'Lalmoni Express',
            'from' => 'Dhaka', 'departure' => '21:45',
            'to' => 'Lalmonirhat', 'arrival' => '07:20', 'off_day' => 'Friday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Gaibandha', 'Lalmonirhat'],
        ],
        [
            'no' => 740, 'name' => 'Lalmoni Express',
            'from' => 'Lalmonirhat', 'departure' => '10:20',
            'to' => 'Dhaka', 'arrival' => '19:55', 'off_day' => 'Friday',
            'route' => ['Lalmonirhat', 'Gaibandha', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 741, 'name' => 'Drutajan Express',
            'from' => 'Dhaka', 'departure' => '20:30',
            'to' => 'Panchagarh', 'arrival' => '06:40', 'off_day' => 'None',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Dinajpur', 'Thakurgaon', 'Panchagarh'],
        ],
        [
            'no' => 742, 'name' => 'Drutajan Express',
            'from' => 'Panchagarh', 'departure' => '07:20',
            'to' => 'Dhaka', 'arrival' => '17:55', 'off_day' => 'None',
            'route' => ['Panchagarh', 'Thakurgaon', 'Dinajpur', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 743, 'name' => 'Brahmaputra Express',
            'from' => 'Dhaka', 'departure' => '18:15',
            'to' => 'Dewanganj', 'arrival' => '23:50', 'off_day' => 'None',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Islampur', 'Dewanganj'],
        ],
        [
            'no' => 744, 'name' => 'Brahmaputra Express',
            'from' => 'Dewanganj', 'departure' => '06:20',
            'to' => 'Dhaka', 'arrival' => '11:10', 'off_day' => 'None',
            'route' => ['Dewanganj', 'Islampur', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 745, 'name' => 'Jamuna Express',
            'from' => 'Dhaka', 'departure' => '16:40',
            'to' => 'Tarakandi', 'arrival' => '22:30', 'off_day' => 'None',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Tarakandi'],
        ],
        [
            'no' => 746, 'name' => 'Jamuna Express',
            'from' => 'Tarakandi', 'departure' => '07:20',
            'to' => 'Dhaka', 'arrival' => '13:10', 'off_day' => 'None',
            'route' => ['Tarakandi', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 747, 'name' => 'Mohanganj Express',
            'from' => 'Dhaka', 'departure' => '14:20',
            'to' => 'Mohanganj', 'arrival' => '20:40', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Islampur', 'Mohanganj'],
        ],
        [
            'no' => 748, 'name' => 'Mohanganj Express',
            'from' => 'Mohanganj', 'departure' => '23:50',
            'to' => 'Dhaka', 'arrival' => '05:00', 'off_day' => 'Wednesday',
            'route' => ['Mohanganj', 'Islampur', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 749, 'name' => 'Egarosindhur Provati',
            'from' => 'Dhaka', 'departure' => '07:15',
            'to' => 'Kishoreganj', 'arrival' => '11:15', 'off_day' => 'Wednesday',
            'route' => ['Dhaka', 'Bhairab Bazar', 'Kishoreganj'],
        ],
        [
            'no' => 750, 'name' => 'Egarosindhur Godhuli',
            'from' => 'Kishoreganj', 'departure' => '14:40',
            'to' => 'Dhaka', 'arrival' => '19:15', 'off_day' => 'None',
            'route' => ['Kishoreganj', 'Bhairab Bazar', 'Dhaka'],
        ],
        [
            'no' => 751, 'name' => 'Egarosindhur Godhuli',
            'from' => 'Dhaka', 'departure' => '18:45',
            'to' => 'Kishoreganj', 'arrival' => '22:35', 'off_day' => 'None',
            'route' => ['Dhaka', 'Bhairab Bazar', 'Kishoreganj'],
        ],
        [
            'no' => 752, 'name' => 'Egarosindhur Provati',
            'from' => 'Kishoreganj', 'departure' => '06:30',
            'to' => 'Dhaka', 'arrival' => '10:35', 'off_day' => 'Wednesday',
            'route' => ['Kishoreganj', 'Bhairab Bazar', 'Dhaka'],
        ],
        [
            'no' => 753, 'name' => 'Silk City Express',
            'from' => 'Dhaka', 'departure' => '14:40',
            'to' => 'Rajshahi', 'arrival' => '20:00', 'off_day' => 'Sunday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 754, 'name' => 'Silk City Express',
            'from' => 'Rajshahi', 'departure' => '07:40',
            'to' => 'Dhaka', 'arrival' => '13:55', 'off_day' => 'Sunday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 755, 'name' => 'Madhumati Express',
            'from' => 'Dhaka', 'departure' => '15:00',
            'to' => 'Rajshahi', 'arrival' => '21:40', 'off_day' => 'Thursday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 756, 'name' => 'Madhumati Express',
            'from' => 'Rajshahi', 'departure' => '06:40',
            'to' => 'Dhaka', 'arrival' => '12:00', 'off_day' => 'Thursday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 757, 'name' => 'Drutojan Express',
            'from' => '', 'departure' => '',
            'to' => '', 'arrival' => '', 'off_day' => '',
            'route' => [],
        ],
        [
            'no' => 758, 'name' => '',
            'from' => '', 'departure' => '',
            'to' => '', 'arrival' => '', 'off_day' => '',
            'route' => [],
        ],
        [
            'no' => 759, 'name' => 'Padma Express',
            'from' => 'Dhaka', 'departure' => '23:10',
            'to' => 'Rajshahi', 'arrival' => '04:40', 'off_day' => 'Tuesday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 760, 'name' => 'Padma Express',
            'from' => 'Rajshahi', 'departure' => '16:00',
            'to' => 'Dhaka', 'arrival' => '21:40', 'off_day' => 'Tuesday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 761, 'name' => 'Sagardari Express',
            'from' => 'Khulna', 'departure' => '15:00',
            'to' => 'Rajshahi', 'arrival' => '21:40', 'off_day' => 'Monday',
            'route' => ['Khulna', 'Jessore', 'Kushtia', 'Poradaha', 'Rajshahi'],
        ],
        [
            'no' => 762, 'name' => 'Sagardari Express',
            'from' => 'Rajshahi', 'departure' => '06:40',
            'to' => 'Khulna', 'arrival' => '12:45', 'off_day' => 'Monday',
            'route' => ['Rajshahi', 'Poradaha', 'Kushtia', 'Jessore', 'Khulna'],
        ],
        [
            'no' => 763, 'name' => 'Chitra Express',
            'from' => 'Khulna', 'departure' => '08:40',
            'to' => 'Dhaka', 'arrival' => '18:40', 'off_day' => 'Monday',
            'route' => ['Khulna', 'Jessore', 'Kushtia', 'Poradaha', 'Rajshahi', 'Bogra', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 764, 'name' => 'Chitra Express',
            'from' => 'Dhaka', 'departure' => '19:00',
            'to' => 'Khulna', 'arrival' => '03:50', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Tangail', 'Bogra', 'Rajshahi', 'Poradaha', 'Kushtia', 'Jessore', 'Khulna'],
        ],
        [
            'no' => 765, 'name' => 'Nilsagar Express',
            'from' => 'Dhaka', 'departure' => '08:00',
            'to' => 'Chilahati', 'arrival' => '17:45', 'off_day' => 'Monday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Gaibandha', 'Chilahati'],
        ],
        [
            'no' => 766, 'name' => 'Nilsagar Express',
            'from' => 'Chilahati', 'departure' => '21:20',
            'to' => 'Dhaka', 'arrival' => '07:10', 'off_day' => 'Sunday',
            'route' => ['Chilahati', 'Gaibandha', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
        [
            'no' => 769, 'name' => 'Dhumketu Express',
            'from' => 'Dhaka', 'departure' => '06:00',
            'to' => 'Rajshahi', 'arrival' => '11:40', 'off_day' => 'Saturday',
            'route' => ['Dhaka', 'Tangail', 'Jamalpur', 'Bogra', 'Natore', 'Rajshahi'],
        ],
        [
            'no' => 770, 'name' => 'Dhumketu Express',
            'from' => 'Rajshahi', 'departure' => '23:20',
            'to' => 'Dhaka', 'arrival' => '04:50', 'off_day' => 'Friday',
            'route' => ['Rajshahi', 'Natore', 'Bogra', 'Jamalpur', 'Tangail', 'Dhaka'],
        ],
    ];
}

/**
 * Filter the schedule by a free-text query.
 * Matches against train number, name, origin and destination.
 * An empty query returns every train.
 */
function train_search(string $query): array
{
    $query = trim($query);
    if ($query === '') {
        return train_schedule();
    }

    // Normalise Bengali digits to ASCII and lowercase for matching.
    $needle = mb_strtolower(bn_to_ascii_digits($query));

    $matches = [];
    foreach (train_schedule() as $train) {
        $haystack = mb_strtolower(implode(' ', [
            (string) $train['no'],
            $train['name'],
            $train['from'],
            $train['to'],
            implode(' ', $train['route']),
        ]));

        if (mb_strpos($haystack, $needle) !== false) {
            $matches[] = $train;
        }
    }
    return $matches;
}

/**
 * Convert Bengali numerals (০-৯) to ASCII (0-9) so "৭০১" finds train 701.
 */
function bn_to_ascii_digits(string $text): string
{
    $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($bn, $en, $text);
}
