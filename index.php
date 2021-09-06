<?php

header('Content-Type: application/json; charset=utf-8');
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$function = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
$key = array_shift($request);

if($function=="list")
{
    $post_list_url = "https://jsonplaceholder.typicode.com/posts";
    $post_list = file_get_contents($post_list_url);
    $post_list = json_decode($post_list);
    
    $comment_url = "https://jsonplaceholder.typicode.com/comments";
    $comment_list = file_get_contents($comment_url);
    $comment_list = json_decode($comment_list);
    
    foreach($post_list as $post)
    {
        $post_comment = array_filter($comment_list,function($item) use($post){
            return $item->postId == $post->id;
        });
    
        $post->total_number_of_comments = sizeof($post_comment);
    }
    
    usort($post_list,function($a,$b){
        if ($a->total_number_of_comments == $b->total_number_of_comments) {
            return 0;
        }
        return ($a->total_number_of_comments > $b->total_number_of_comments) ? -1 : 1;
    });

    echo json_encode($post_list);
    return;
}
else if($function=="search")
{
    $comment_url = "https://jsonplaceholder.typicode.com/comments";
    $comment_list = file_get_contents($comment_url);
    $comment_list = json_decode($comment_list);

    $result = array_filter($comment_list,function($item) use($key){
        if($item->id==$key)
        {
            return true;
        }

        if($item->postId==$key)
        {
            return true;
        }

        if(strpos($item->name,$key)!==false)
        {
            return true;
        }

        if(strpos($item->email,$key)!==false)
        {
            return true;
        }

        if(strpos($item->body,$key)!==false)
        {
            return true;
        }
    });

    echo json_encode($result);
    return;
}
?>
