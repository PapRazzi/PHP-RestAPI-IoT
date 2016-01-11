<?php

/*
 * Load static version of Slim
*/
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

/*
 * MySQL connection
*/
function getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="test";
    $dbpass="test";
    $dbname="test";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

/**
 * Instantiate a Slim application
 * Enabling debug for testing. 
 */
//$app = new \Slim\Slim();
$app = new \Slim\Slim(array(
    'debug' => true
));

/**
 * Enabling HTTP basic authentication
 */
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "secure" => false,
    "users" => [
        "test" => "test",
    ]
]));

/**
 * Homepage for root directory
 * Will later include the documentation of the REST Api
 */
$app->get('/', function () {
    echo "Hello";
});

/**
 * Route to get a list of temperature entries
 */
$app->get('/temperature', 'getLastTemperature');
/**
 * Route to get one specific temperature entry
 */
$app->get('/temperature/:id',  'getTemperature');
/**
 * Route to add a new temperature entry
 */
$app->post('/temperature', 'addTemperature');
/**
 * Route to get specific temperature entries between dates
 */
$app->get('/temperature/search/:days', 'searchTemperature');

/**
 * Get a list of temperature entries
 */
function getLastTemperature() {
    $sql = "select * FROM temperature ORDER BY timestamp DESC LIMIT 100";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $temps = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"temperature": ' . json_encode($temps) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
 * Get one specific temperature entry
 */
function getTemperature($id) {
    $sql = "SELECT * FROM temperature WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $temp = $stmt->fetchObject();
        $db = null;
        echo json_encode($temp);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
 * Add a new temperature entry
 */
function addTemperature() {
	$app = \Slim\Slim::getInstance();
    $request = $app->request();
    $temp = json_decode($request->getBody());
    $sql = "INSERT INTO temperature (temp, place) VALUES (:temperature, :place)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("temperature", $temp->temp);
        $stmt->bindParam("place", $temp->place);
        $stmt->execute();
        $temp->id = $db->lastInsertId();
        $db = null;
        echo json_encode($temp);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
 * Get specific temperature entries between dates
 */
function searchTemperature($days) {
	$sql = "SELECT id,temp,place,timestamp FROM temperature WHERE timestamp >= (now() - INTERVAL :query DAY)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("query", $days);
        $stmt->execute();
        $temps = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"temperature": ' . json_encode($temps) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/**
 * Run the Slim application
 */
$app->run();
