<?php

function escape($string){

  return  mysqli_real_escape_string($connection , trim($string));

}

function insert_categories(){

    global $connection;
    if(isset($_POST['submit'])){

        $cat_title = $_POST['cat_title'];
        
        if($cat_title == "" || empty($cat_title)){
            echo'this field should not be empty';
        }else{
            $query = "insert into categories(cat_title) ";
            $query .= " value('{$cat_title}') ";
        
            $add_category = mysqli_query($connection , $query);
        
            if(!$add_category){
                die('error' .mysqli_error($connection));
            }
        
        }
        
        
        }
}
function select_categories(){

    global $connection;

    $query = "select * from categories";
    $result = mysqli_query($connection , $query);
    
    while($row = mysqli_fetch_assoc($result)){
    
        $cat_title = $row['cat_title'];
        $cat_id = $row['cat_id'];
        
     echo   "<tr>";
     echo       "<th>{$cat_id}</th>";
     echo      "<th>{$cat_title}</th>";

        if($_SESSION['role'] !== 'admin'):

           else:
        echo      "<th><a href='categories.php?delete={$cat_id}'>Delete</a></th>";
     echo      "<th><a href='categories.php?edit={$cat_id}'>Edit</a></th>";
          endif;
     
     echo   "</tr>";
    
    
    
    
    }
}
function delete_categories(){

    global $connection;
    if(isset($_GET['delete'])){
        $cat_id = $_GET['delete'];
   
    $query = "delete from categories where cat_id = {$cat_id}";
   $result = mysqli_query($connection , $query);
   header('location: categories.php');
   }

   
}


