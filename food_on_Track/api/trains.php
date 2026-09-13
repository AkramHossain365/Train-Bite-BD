<?php
/**
 * trains.php
 * Search Bangladesh Railway trains by number / name / station.
 *
 * Input : q (optional)  — number, name, origin or destination
 *         no (optional) — exact train number, returns a single train
 * Output: { success, count, trains: [ { no, name, from, departure, to,
 *           arrival, off_day, route: [...] }, ... ] }
 *
 * Examples:
 *   GET trains.php                     -> all trains
 *   GET trains.php?q=suborno           -> matching trains
 *   GET trains.php?q=৭০১                -> Bengali numerals work too
 *   GET trains.php?no=701              -> one specific train
 *   GET trains.php?station=Sylhet      -> trains serving Sylhet
 */
require __DIR__ . '/db.php';
require __DIR__ . '/train_data.php';

$query   = field($_GET, 'q');
$number  = field($_GET, 'no');
$station = field($_GET, 'station');

// Exact train number lookup takes priority.
if ($number !== '') {
    $number = bn_to_ascii_digits($number);
    $found  = null;
    foreach (train_schedule() as $train) {
        if ((string) $train['no'] === $number) {
            $found = $train;
            break;
        }
    }
    if ($found === null) {
        json_error('এই নম্বরের কোনো ট্রেন পাওয়া যায়নি: ' . $number, 404);
    }
    json_ok(['count' => 1, 'trains' => [$found]]);
}

// Station filter: only trains whose route stops at that station.
if ($station !== '') {
    $needle  = mb_strtolower($station);
    $matches = array_values(array_filter(train_schedule(), function ($train) use ($needle) {
        foreach ($train['route'] as $stop) {
            if (mb_strpos(mb_strtolower($stop), $needle) !== false) {
                return true;
            }
        }
        return false;
    }));

    if (!$matches) {
        json_error('এই স্টেশনে কোনো ট্রেন পাওয়া যায়নি: ' . $station, 404);
    }
    json_ok(['count' => count($matches), 'trains' => $matches]);
}

// Free-text search (empty q returns everything).
$trains = train_search($query);

json_ok([
    'count'  => count($trains),
    'trains' => $trains,
]);
