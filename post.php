<?php
include "includes/header.php"
?>
    <!-- Navigation -->
  <?php
include "includes/navigation.php"
?>

  <?php
if(isset($_POST['liked'])){

$post_id = $_POST['postId'];
$user_id = $_POST['userId'];


$query = "SELECT * from posts where post_id= $post_id";
$result= mysqli_query($connection , $query);
$post = mysqli_fetch_assoc($result);
$likes = $post['likes'];


mysqli_query($connection , "UPDATE posts set likes=$likes + 1 where post_id=$post_id");

mysqli_query($connection , "INSERT into likes(user_id , post_id) values($user_id , $post_id)");

}

if(isset($_POST['unliked'])){

$post_id = $_POST['postId'];
$user_id = $_POST['userId'];


$query = "SELECT * from posts where post_id= $post_id";
$result= mysqli_query($connection , $query);
$post = mysqli_fetch_assoc($result);
$likes = $post['likes'];


mysqli_query($connection , "UPDATE posts set likes=$likes- 1 where post_id=$post_id");

mysqli_query($connection , "DELETE from  likes where post_id=$post_id and user_id=$user_id" );


}

?>



<!-- Page Content -->
<div class="container">
  <div class="row">
    <!-- Blog Entries Column -->
    <div class="col-md-8">

    <h1 class="page-header">
        Page Heading
        <small>Secondary Text</small>
      </h1>

  <?php

if(isset($_GET['p_id'])){

  $p_id = $_GET['p_id'];

  $querys = "UPDATE posts set post_views_count = post_views_count + 1 where post_id = $p_id";
  $results = mysqli_query($connection , $querys);


$query = "select * from posts where post_id= $p_id ";
$result = mysqli_query($connection , $query);

while($row = mysqli_fetch_assoc($result)){

  $post_title = $row['post_title'];
  $post_author = $row['post_author'];
  $post_date = $row['post_date'];
  $post_image = $row['post_image'];
  $post_content = $row['post_content'];

?>


    
  
      <!-- First Blog Post -->
      
      <h2>
      <?php echo $post_title ?>

      </h2>
    
      
    
      <p class="lead">by <a href="author.php?author=<?php echo $post_author ?>"><?php echo $post_author ?></a></p>
      <p>
        <span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?>
      </p>
      <div> <i class="fas fa-cog"></i><a href="admin/posts.php?source=edit_post&p_id=<?php echo $p_id?>"> Edit</a></div>
      <hr />
      <img
        class="img-responsive"
        src="img/<?php echo $post_image ?>"
        alt=""
      />
      <hr />
      <p>
      <?php echo $post_content ?>
      </p>
     
      <hr />
      <div>
      <span style="margin-right: 20px;" ><i class="far fa-thumbs-up"></i> Likes: 10  </span>
      <span > <i class="far fa-comment"></i> comment: 2</span>
      </div>
      <hr/>
      <div>

      <p ><a class='<?php echo userLiked($p_id) ? 'unlike' : 'like' ?>' style="font-size: 18px; " href=""><i class="far fa-thumbs-<?php echo userLiked($p_id) ? 'down' : 'up' ?>"></i> <?php echo userLiked($p_id) ? 'Unlike' : 'Like' ?></a></p>
      </div>
     


<?php


}



}else{

  header('location:index.php') ;
}

?>

      <!-- Blog Comments -->


<?php

if(isset($_POST['create_comment'])){

  $post_id = $_GET['p_id'];

$comment_author = $_POST['comment_author'];
$comment_email = $_POST['comment_email'];
$comment_content = $_POST['comment_content'];

$query = "insert into comments (comment_post_id,  comment_author,  comment_email,comment_content,  comment_status ,comment_date )  ";
$query .= " values ({$post_id} ,'{$comment_author}','{$comment_email}','{$comment_content}','unapproved' , now() ) ";


$creat_comment = mysqli_query($connection , $query);
if(!$creat_comment){
  die(mysqli_error($connection));
}

$query = "update posts set post_comment_count =  post_comment_count + 1  where post_id ={$post_id}";
$update_count = mysqli_query($connection , $query);



}
?>

      

          <!-- Comments Form -->
          <div class="well">
            <h4>Leave a Comment:</h4>
            <form  action='' method='post' role="form">
              <div class="form-group">
              <label for="comment_author">Author</label>
                <input type="text" class="form-control"  name="comment_author" required>             
              </div>
              <div class="form-group">
              <label for="comment_email">Email</label>

                <input type="email" class="form-control"  name="comment_email" required>             
              </div>
            
              <div class="form-group">
              <label for="comment">Your Comment</label>

                <textarea class="form-control" name='comment_content' rows="3" ></textarea>
              </div>
              <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
            </form>
          </div>

          <hr />

          <!-- Posted Comments -->

          <!-- Comment -->


          <?php
$post_id = $_GET['p_id'];

$query = "select * from comments where comment_post_id= {$post_id} ";
$query .= "and comment_status = 'approved' ";
$query .= "order by comment_id desc  ";
$result = mysqli_query($connection , $query);



while($row = mysqli_fetch_assoc($result)){


$comment_author = $row['comment_author'];
$comment_date = $row['comment_date'];
$comment_content = $row['comment_content'];


?>



          <div class="media">
            <a class="pull-left" href="#">
              <img
                class="media-object"
                src="http://placehold.it/64x64"
                alt=""
              />
            </a>
            <div class="media-body">
              <h4 class="media-heading">
               <?php echo $comment_author?>
                <small><?php echo $comment_date?></small>
              </h4>
              <?php echo $comment_content?>
            </div>
          </div>

   <?php
   }?> 
    
      <!-- Second Blog Post -->
    
      <!-- Pager -->
    
    </div>
    <!-- Blog Sidebar Widgets Column -->
  
    <?php
include "includes/sidebar.php"
?>
      </div>
      <!-- /.row -->

      <hr />





<?php
include "includes/footer.php"
?>

<script >

$(document).ready(function(){

  let postId = <?php echo $post_id ?>;
  let userId = <?php echo loggedInUser() ?>;

$('.like').click(function(){


 $.ajax({
  
  url:"/cms/post.php?p_id=<?php echo $post_id ?>",
  type:'POST',
  data:{
    liked:1,
    postId: postId,
    userId: userId,

  }
 })
})
$('.unlike').click(function(){


 $.ajax({
  
  url:"/cms/post.php?p_id=<?php echo $post_id ?>",
  type:'POST',
  data:{
    unliked:1,
    postId: postId,
    userId: userId,

  }
 })
})


})

</script>