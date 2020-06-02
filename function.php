


<?php


function redirect($location){


    header("Location:" . $location);
    exit;

}
function confirmQuery($result) {
    
    global $connection;

    if(!$result ) {
          
          die("QUERY FAILED ." . mysqli_error($connection));
   
          
      }
    

}


function username_exist($username){

    global $connection ; 

    $query = "SELECT user_name from users where user_name = '{$username}'";
    $result = mysqli_query($connection , $query);

    if(!$result ){

        die(mysqli_error($connection));
    }
    
    if(mysqli_num_rows($result) > 0){
        return true ; 

    }else {
        return false ;
    }

}

function email_exist($email){

    global $connection ; 

    $query = "SELECT user_name from users where user_email = '{$email}'";
    $result = mysqli_query($connection , $query);

    if(!$result ){

        die(mysqli_error($connection));
    }
    
    if(mysqli_num_rows($result) > 0){
        return true ; 

    }else {
        return false ;
    }

}


function itIsMethod($method){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){

        return true ;
    }else{
         return false ;
    }
   
}

function isLoggedIn(){

if(isset($_SESSION['role'] )){

    return true ;
}
return false ;


}

function redirected($direction=null){

    if(isLoggedIn()){

        header('location:' .$direction);

    }
}

function login_user($username, $password){

    global $connection;

    $username = trim($username);
    $password = trim($password);

    $username = mysqli_real_escape_string($connection , $username);
    $password = mysqli_real_escape_string($connection , $password);


    $query = "SELECT * FROM users WHERE user_name = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {

        die("QUERY FAILED" . mysqli_error($connection));

    }


    while ($row = mysqli_fetch_array($select_user_query)) {

        $db_user_id = $row['user_id'];
        $db_username = $row['user_name'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];


        if (password_verify($password,$db_user_password)) {
            header('location: admin');
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['role'] = $db_user_role;



            


        } else {


            return false;



        }



    }

    return true;

}


function email_exists($email){

    global $connection;


    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if(mysqli_num_rows($result) > 0) {

        return true;

    } else {

        return false;

    }



}