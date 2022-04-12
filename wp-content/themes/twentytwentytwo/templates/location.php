<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
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
/*
function show_other_items($querystr, $con){
    $result = $con->query($querystr);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    $data = $con->query($querystr);
    $data->setFetchMode(PDO::FETCH_ASSOC);
    $i = 0;
    foreach($data as $row)
    {
        foreach ($row as $field => $value)
        {
            if( $field == 'image_path') $image_path = $value;
            if( $field == 'name') $name = $value;
            if( $field == 'price') $price = $value;
            if( $field == 'itemID') $newId = $value;
            $i = $i + 1;
        }
        print "<td> <a href=\"/item-page/?itemid=$newId\"> <img src=\"/$image_path\"><br> $name: $$price</a>  </td>"; 
    }
    if( $i < 1 )
        print "<td> none... </td>";
}
*/
// SET UP DATABASE
$con = new PDO('mysql:host=localhost;dbname=villanova_map', "root", "");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$hall_name = $_GET['location'];
$query = "SELECT * FROM hall WHERE name = '$hall_name'";
$result = $con->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

$data = $con->query($query);
$data->setFetchMode(PDO::FETCH_ASSOC);
// END SET UP


// FETCH DATA FROM DB AND DISPLAY
$image_path_prefix = '../images/';

foreach($data as $location) {
    echo '<h1>'.$location['name'].' Hall</h1>';
    echo '<h3><u>Description: </u><br>' .$location['description'] . '</h3>';
    echo '<h3><u>Campus: </u><br>' .$location['campus'].'</h3>';
    
    echo '<center>';
    echo '<img src="'.$image_path_prefix.$location['image_path'].'">';
}
/*
print "<table> <div class=\"other-items\"><tr> <td colspan=\"4\"> <h3> Other items made by $vendorName </h3> </td> </tr> <tr> ";
show_other_items("SELECT image_path, name, price, itemID FROM Items WHERE vendorID = $vendor AND itemID != $param_id", $con);
print " </tr> </table>";

print "<br> <table> <tr> <td colspan=\"4\"> <h3> Other items in this category </h3> </td> </tr> <tr> ";
show_other_items("SELECT image_path, name, price, itemID FROM Items WHERE category = $category AND itemID != $param_id", $con);

print " </tr> </table></div></div> </div>";

*/

?>
</center>

<?php 
block_footer_area();
?>

</body>
</html>