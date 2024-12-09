<?php
/**
 * Created by PhpStorm.
 * User: asif.ghulamrasool
 * Date: 11/19/2024
 * Time: 11:58 PM
 */

$url = 'https://restcountries.com/v3.1/independent?status=true';

try {
    // Fetch data from API
    $response = file_get_contents($url);

    if ($response === FALSE) {
        throw new Exception("Error fetching the API data.");
    }

    // Decode JSON response
    $data = json_decode($response, true);

    foreach ($data as $d){
        $name = $d['name']['common'];
        $code = $d['altSpellings'][0];
        $placeholder = $d['idd']['root'].$d['idd']['suffixes'][0];

    }

    // Display data (for debugging)
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}