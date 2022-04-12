<?php
/**
 * Template Name: Location Page
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty One 1.0
 */

get_header(); ?>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->


<?php

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

// SET UP DATABASE
$con = new PDO('mysql:host=localhost;dbname=gbay_schema', "root", "TI4ddmhIEk9jRJzW");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$param_id = $_GET['itemid'];
$query = "SELECT * FROM Items WHERE itemID = $param_id";
$result = $con->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

$data = $con->query($query);
$data->setFetchMode(PDO::FETCH_ASSOC);
// END SET UP
print "<div class = \"tourist-page\"><div class =\"item-page\">";
print "<table> <tr>";

$counter = 0;
foreach($data as $row)
  {
    $i = 0;
    $item = "";
    $page = "";
    $quant = 0;
    $id = 0;
    $price = 0;
    $category = -1;
    $image_path = 0;    
    foreach ($row as $name=>$value)
    {
        if( $name == 'description')
            $desc = $value;
        if( $name == 'vendorID') {
            $vendor = $value;
        }
        if( $i== 0 ){
            $id = $value;
        }
        if($i == 2){
            $item = $value;
        }
        if($i==3){
            $quant = $value;
        }
        if($i == 4)
            $price = $value;
        if( $i == 6 ){
             $image_path = $value;
        }
        if( $name == "category")
            $category = $value;
        if($i == 7 ){
                
            print  "<td> <img src=\"/$image_path\"> </td> <td> $item: $$price <br> Quantity left in stock: $quant <br><br>";
            if( $quant >= 1)
                print  "<center> <input type=submit value=\"BUY NOW\"><div class=\"item-button-br\"><br><br></div>";
            else
                print  "<center> <input type=submit value=\"OUT OF STOCK\"><div class=\"item-button-br\"><br><br></div>";
            print  "<input type=submit value=\"BID ON PRICE\"><br><br></td> </tr><td colspan=\"2\"> Description: $desc <br> <br> <center> <h3>About the artisan: </h3><br> ";
            
            $queryV = "SELECT * FROM Vendors WHERE vendorID = $vendor";
            $resultV = $con->query($queryV);
            $rowV = $resultV->fetch(PDO::FETCH_ASSOC);
            $dataV = $con->query($queryV);
            $dataV->setFetchMode(PDO::FETCH_ASSOC);

            foreach($dataV as $rowV)
            {
                foreach ($rowV as $fieldV => $valueV)
                {
                    if( $fieldV == "password" || $fieldV == "address" || $fieldV == "vendorID")
                        continue;
                    if( $fieldV == "fname") {
                    print "$valueV "; $vendorName = $valueV;}
                    if ($fieldV == "lname") {
                    print "$valueV <br>"; $vendorName.=" " .$valueV;}
                    if( $fieldV != "fname" && $fieldV != "lname") {
                        if( $fieldV == "prof_img_path"){
                            print "<br><br></td></tr><tr> <td colspan=\"2\"><center><img src=\"/$valueV\">&ensp; 
                                    <center> <p style=\"padding: 0 0 0 0;border-width: 4px; border-color: black; border-style:solid;\"> Map of Artisan Location </p> 
                                    <p 
                                        <li><iframe src=\"https://www.google.com/maps/d/embed?mid=17Std0gU8Je8XlNIW4cnlTP7I5ng3BrRF&hl=en&ehbc=2E312F\" width=\"640\" height=\"480\"></iframe></li>

                                    </p>";


                        }
                        else if( $fieldV == "email")
                            print "<a href=\"mailto:$valueV?subject=GBAY Support\">$valueV</a><br>";
                        else
                            print "$valueV";
                    }
                }
            }
            print "</td>";

        }
        if($counter >=9){
            break;
        }
      $i = $i + 1;
  }
}
print "</tr> </table><br> ";

print "<table> <div class=\"other-items\"><tr> <td colspan=\"4\"> <h3> Other items made by $vendorName </h3> </td> </tr> <tr> ";
show_other_items("SELECT image_path, name, price, itemID FROM Items WHERE vendorID = $vendor AND itemID != $param_id", $con);
print " </tr> </table>";

print "<br> <table> <tr> <td colspan=\"4\"> <h3> Other items in this category </h3> </td> </tr> <tr> ";
show_other_items("SELECT image_path, name, price, itemID FROM Items WHERE category = $category AND itemID != $param_id", $con);

print " </tr> </table></div></div> </div>";
?>


<?php get_footer(); ?>