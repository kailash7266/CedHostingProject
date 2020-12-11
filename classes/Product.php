<?php

// session_start();
class Product
{
    public $pid;
    public $product_parent_id;
    public $product_name;
    public $link;
    public $product_Available;
    public $product_launch_date;

    function fetchproduct($conn)
    {
        $query = "SELECT * from `tbl_product` where `id`='1'";
        if ($result=mysqli_query($conn, $query)) {
            while ($row=mysqli_fetch_assoc($result)) {
                 return $row['prod_name'];
            }
        }

    }

    function addproductcategory($pname, $purl, $pavail, $conn)
    {
        $query = "INSERT INTO `tbl_product`(`prod_parent_id`, `prod_name`, `link`, `prod_available`, `prod_launch_date`) 
        VALUES('1', '{$pname}', '{$purl}', '{$pavail}', NOW())";
        if (mysqli_query($conn, $query) or die(print_r($conn))) {
            return 1;
        } else {
            return 0;
        }
    }

    function fetchdata($conn) 
    {
        $data=array();
        $sql="SELECT * FROM tbl_product WHERE `id`!='1'";
        $result =  mysqli_query($conn, $sql);
        while ($row=mysqli_fetch_assoc($result)) {
            $available=0;
            if ($row['prod_available']==1) {
                $available="Available";
            } else {
                $available="Un-Available";
            }
            $data['data'][] = array($row['id'],  $row['prod_name'], $row['link'], $available, $row['prod_launch_date'],'<input type="button" class="btn btn-primary editbtn"  data-toggle="modal" data-target="#updatecategory" value="Edit" data-eid="'.$row['id'].'" > <input class="btn btn-danger bg-orange deletebtn" type="button" value="delete" data-did="'.$row['id'].'">');
        }
        print_r(json_encode($data));
    }


    function fetchdatawithproductdesc($conn) 
    {
        $data=array();
        //$sql="SELECT * FROM tbl_product WHERE `id`!='1'";
        $sqlquery="SELECT * FROM `tbl_product` INNER JOIN `tbl_product_description` ON `tbl_product`.`id` = `tbl_product_description`.`prod_id`";
        $result =  mysqli_query($conn, $sqlquery);
        $arrdesc=array();
        while ($row=mysqli_fetch_assoc($result)) {
            $arrdesc=json_decode($row['description']);
            $available=0;
            if ($row['prod_available']==1) {
                $available="Available";
            } else {
                $available="Un-Available";
            }
            $data['data'][] = array($row['id'],  $row['prod_name'], $row['link'], $available, $row['description'], $row['mon_price'], $row['annual_price'], $row['sku'], $row['prod_launch_date'], '<input type="button" class="btn btn-primary editbtn"  data-toggle="modal" data-target="#updatecategory" value="Edit" data-eid="'.$row['id'].'" > <input class="btn btn-danger bg-orange deletebtn" type="button" value="delete" data-did="'.$row['id'].'">');
        }
        print_r(json_encode($data));
    }

    function fetchdatanav($conn){
        $data=array();
        $sql="SELECT * FROM tbl_product WHERE `prod_parent_id`='1' AND `prod_available`='1'";
        $result =  mysqli_query($conn, $sql);
        while ($row=mysqli_fetch_assoc($result)) {
            $data[]= array($row['id'],  $row['prod_name'], $row['link']);
        }
        return print_r(json_encode($data));
    }

    function deleteCategory($cid, $conn)
    {
        $sql="DELETE FROM `tbl_product` WHERE `id` = $cid";
        if (mysqli_query($conn, $sql)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function editCategory($cid, $conn)
    {
        $query = "SELECT * from `tbl_product` where `id`='$cid'";
        if ($result=mysqli_query($conn, $query)) {
            while ($row=mysqli_fetch_assoc($result)) {
                 return print_r(json_encode($row));
            }
        }
    }  

    function UpdateCategory($cid, $pname, $purl, $pavail, $conn)
    {
        $sql="UPDATE `tbl_product` SET `prod_name` = '$pname', `link`= '$purl', `prod_available`= '$pavail' where  `id` = '$cid'";
        if (mysqli_query($conn, $sql) or die(print_r($conn))) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function addnewProduct($cid, $productname, $producturl, $monthlyprice, $annualyprice, $skuid, $product_desc_json, $conn)
    {
    $query = "INSERT INTO `tbl_product`(`prod_parent_id`, `prod_name`, `link`, `prod_available`, `prod_launch_date`) 
    VALUES('{$cid}', '{$productname}', '{$producturl}', '1', NOW())";
        if (mysqli_query($conn, $query)) {
            $last_id = $conn->insert_id;
            $query1 = "INSERT INTO `tbl_product_description`(`prod_id`, `description`, `mon_price`, `annual_price`, `sku`) 
            VALUES('{$last_id}', '{$product_desc_json}', '{$monthlyprice}', '{$annualyprice}', '{$skuid}')";
            if (mysqli_query($conn, $query1) or die(print_r($conn))) {
                return 1;
            } else {
                return 0; 
            }
            return 1;
        } else {
            return 0;
        }
    }
}
