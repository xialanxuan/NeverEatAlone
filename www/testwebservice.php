<?php
/* require the user as the parameter */
{

  /* soak in the passed variable or set our own */
  /* connect to the db */
  $link = mysql_connect('localhost:3306','root','74111') or die('Cannot connect to the DB');
  mysql_select_db('test',$link) or die('Cannot select the DB');
  $id = 1;
  $number_of_posts = 20;
  $format =  'json';
  /* grab the posts from the db */
  $query = "SELECT id, title, guid FROM post WHERE id = $user_id AND state = 'pub' ORDER BY ID DESC";
  $result = mysql_query($query,$link) or die('Errant query:  '.$query);



  /* create one master array of the records */
  $posts = array();
  if(mysql_num_rows($result)) {
    while($post = mysql_fetch_assoc($result)) {
      $posts[] = array('post'=>$post);
    }
  }



  /* output in necessary format */
  if($format == 'json') {
    header('Content-type: application/json');
    echo json_encode(array('posts'=>$posts));
  }
  else {
    header('Content-type: text/xml');
    echo '<posts>';
    foreach($posts as $index => $post) {
      if(is_array($post)) {
        foreach($post as $key => $value) {
          echo '<',$key,'>';
          if(is_array($value)) {
            foreach($value as $tag => $val) {
              echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
            }
          }
          echo '</',$key,'>';
        }
      }
    }
    echo '</posts>';
  }

  /* disconnect from the db */
  @mysql_close($link);
}
?>