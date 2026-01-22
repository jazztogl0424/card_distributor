<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Remarks 5c: "Input value does not exist or value is invalid"
// Remarks 7d: Irregular processing. 7e: "Irregularity occurred"

function sendError($message, $terminateMessage = "Irregularity occurred") {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => $message, "terminate" => $terminateMessage]);
    exit;
}

// Input: Number of people (numerical value)
// Read from GET parameter 'n' for simplicity, or POST. Let's support GET 'n'.
$n = isset($_GET['n']) ? $_GET['n'] : null;

// Validation
if ($n === null || trim($n) === '') {
    sendError("Input value does not exist or value is invalid");
}

if (!is_numeric($n)) {
    sendError("Input value does not exist or value is invalid");
}

$n = intval($n);

// 5d: Any number less than 0 is an invalid value.
// Note: Prompt says "Any number less than 0 is an invalid value." What about 0?
// Usually n=0 people is invalid for distribution? 
// "Input value does not exist or value is invalid"
if ($n < 0) {
    sendError("Input value does not exist or value is invalid");
}

// If n=0, distribute to 0 people? Prompt says "n(number) people".
// Let's assume n=0 is valid but results in empty distribution, or maybe invalid?
// "Any number less than 0 is an invalid value" implies 0 is valid.
// But distributing to 0 people results in nothing.

// 5e: Greater than 53 are normal values and cards must be distributed...

// Logic
$suits = ['S', 'H', 'D', 'C'];
$ranks = range(1, 13);
$deck = [];

foreach ($suits as $suit) {
    foreach ($ranks as $rank) {
        $displayRank = (string)$rank;
        switch ($rank) {
            case 1: $displayRank = 'A'; break;
            case 10: $displayRank = 'X'; break;
            case 11: $displayRank = 'J'; break;
            case 12: $displayRank = 'Q'; break;
            case 13: $displayRank = 'K'; break;
        }
        $deck[] = "{$suit}-{$displayRank}";
    }
}

// Shuffle randomly
// Remarks 2: "given to n people randomly"
shuffle($deck);

$distribution = [];
for ($i = 0; $i < $n; $i++) {
    $distribution[$i] = [];
}

if ($n > 0) {
    // Distribute cards
    // "The card distributed to the first person... second person..."
    // Standard dealing: Card 1 to P1, Card 2 to P2...
    $personIndex = 0;
    foreach ($deck as $card) {
        $distribution[$personIndex][] = $card;
        $personIndex = ($personIndex + 1) % $n;
    }
}

// Output Formatting
// "separated (comma), [LF] is not allowed"
// We will return an array of strings, where each string is the CSV for that person.
$output = [];
foreach ($distribution as $hand) {
    $output[] = implode(",", $hand);
}

echo json_encode(["status" => "success", "data" => $output]);
?>
