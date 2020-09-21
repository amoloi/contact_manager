<?php
/*
 * AUSU jQuery-Ajax Autosuggest v1.0
 * Demo of a simple server-side request handler
 * Note: This is a very cumbersome code and should only be used as an example
 */

# Establish DB Connection
$con    =   mysql_connect("internal-db.s112924.gridserver.com","db112924","q1w2e3r4");
if (!$con){ die('Could not connect: ' . mysql_error()); }
mysql_select_db("db112924_oslund", $con);

# Assign local variables
$id     =   @$_POST['id'];          // The id of the input that submitted the request.
$data   =   @$_POST['data'];        // The value of the textbox.

if ($id && $data)
{
    if ($id=='countries')
    {
        $query  = "SELECT country_id,country_name
                  FROM tbl_countries
                  WHERE country_name LIKE '%$data%'
                  LIMIT 5";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['country_name'];
            $dataList[] = '<li id="' .$row['country_id'] . '"><a href="#">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="#">No Results</a></li>';
        }
    }
    elseif ($id=='categories')
    {
        $query  = "SELECT category_id,category_name
                  FROM tbl_categories
                  WHERE category_name LIKE '%$data%'
                  LIMIT 5";

        $result = mysql_query($query);

        $dataList = array();

        while ($row = mysql_fetch_array($result))
        {
            $toReturn   = $row['category_name'];
            $dataList[] = '<li id="' .$row['category_id'] . '"><a href="#">' . htmlentities($toReturn) . '</a></li>';
        }

        if (count($dataList)>=1)
        {
            $dataOutput = join("\r\n", $dataList);
            echo $dataOutput;
        }
        else
        {
            echo '<li><a href="#">No Results</a></li>';
        }
    }

}
else
{
    echo 'Request Error';
}
?>
