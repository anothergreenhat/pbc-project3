<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
    <style>
        img {
            width: 1000px;
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">

<?php
/**
 * Template Name: Location Page
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty Two 1.0
 */

//block_header_area();
?>
<br>

<?php

$hall_name = null;
$campus = null;

$non_hall_names = array('Church' => 'St. Thomas of Villanova Church', 'Soccer' => 'Soccer Complex', 'Tennis' => 'Tennis Courts', 'Softball' => 'Softball Field', 
                        'Finneran' => 'Finneran Pavillion', 'Mendel' => 'Mendel Science Center', 'South' => 'South Campus Dorms', 'West' => 'West Campus Apartments',
                        'Commons' => 'The Commons Apartments', 'Mullen' => 'The Mullen Center for the Performing Arts', 'StMarys' => 'St. Mary\'s Hall',
                        'Law' => 'Charles Widger School of Law', 'CEER' => 'CEER', 'Connelly' => 'Connelly Center', 'Nevin' => 'Jake Nevin Field House',
                        'Stadium' => 'Villanova Stadium', 'Refectory' => 'The Refectory', 'Davis' => 'The Davis Center for Athletics');

function get_unique_location($data, $non_hall_names) {
    $image_path_prefix = '../images/';
    foreach($data as $location) {
        $campus = $location['campus'];
        $hall_name = $location['name'];
        if (array_key_exists($hall_name, $non_hall_names) ) 
            $hall_name = $non_hall_names[$hall_name];
        else
            $hall_name .= ' Hall';

        echo '<h1>'.$hall_name.'</h1>';

        echo '<center>';
        echo '<img src="'.$image_path_prefix.$location['image_path'].'"></h4>';
        echo '</center>';

        echo '<h4><u>Description: </u><br>' .$location['description'] . '</h4>';
        echo '<h4><u>Campus: </u><br>' .$campus;
    }
    return $campus;
}

function get_locations_by_campus($data, $campus, $non_hall_names) {
    echo "<h1>";
    echo $campus; ?> Campus Locations: </h1><?php
    foreach($data as $location) {

        $hall_name = $location['name'];
        echo '<h2><a href="?location='.$hall_name.'"<h2>';

        if (array_key_exists($hall_name, $non_hall_names) ) 
            $hall_name = $non_hall_names[$hall_name];
        else
            $hall_name = $location['name'] . ' Hall';
        echo $hall_name.'</h2></h2>';
    }
    echo '<br><br><br>';
}

// SET UP DATABASE
$con = new PDO('mysql:host=localhost;dbname=villanova_map', "root", "");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$is_get_by_location = isset($_GET['location']);
$is_get_by_campus = isset($_GET['campus']);


if ( $is_get_by_location ) {
    $hall_name = $_GET['location'];
    $query = "SELECT * FROM hall WHERE name = '$hall_name' order by name ASC"; 
}
else if ( $is_get_by_campus) {
    $campus = $_GET['campus'];
    $query = "SELECT * FROM hall WHERE campus = '$campus' order by name ASC";
}
else {
    $query = null;
    echo '<h1>Select a Campus: </h1>';
    ?> <h2><a href="?campus=Main">Main</a> &nbsp; &nbsp; <?php
    ?> <a href="?campus=West">West</a> &nbsp; &nbsp; <?php
    ?> <a href="?campus=South">South</a> </h2><?php
    echo "<br><br><br><br>";
}

if( isset($query) ) {
    $result = $con->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $data = $con->query($query);
    $data->setFetchMode(PDO::FETCH_ASSOC);
}
// END SET UP


// PAGE START 
if( isset($data) && $data->rowCount() == 0) {
    echo '<h1>404 Not Found </h1>';
    echo '<h4>The resource you are looking for on this map does not exist or hasn\'t been implemented yet.</h4>';
    echo '<h4>Contact us at <a href="mailto:jmadera1@villanova.edu">map@villanova.edu</a> to have it added to the database.</h4>';
    echo '<br><br><br><br>';
}
else {
    if( $is_get_by_location )
        $campus = get_unique_location($data, $non_hall_names);
    else if ( $is_get_by_campus) {
        get_locations_by_campus($data, $campus, $non_hall_names);
    }
}

?>
</center>

<h5>
<br> 
<a href="/wordpress">Return to Map</a>
<?php if ( $is_get_by_campus || $is_get_by_location ) { ?>
    &emsp;<a href="/wordpress/places/">Return to Places</a>
<br> <br>
<?php } ?>


<?php if ( $is_get_by_location) { ?>
<a href="?campus=<?php echo $campus; ?>">Return to <?php echo $campus; ?> Campus locations</a> 
<?php } ?>



</h5>


<?php 
block_footer_area();
?>

</body>
</html>