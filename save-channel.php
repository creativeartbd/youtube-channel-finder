<?php

error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', TRUE); 
ini_set('error_log', 'error.log');

require 'vendor/autoload.php';
require 'config.php';

$channel_id             = $_REQUEST['channel_id'];
$channel_description    = $_REQUEST['channel_description'];
$channel_thumb          = $_REQUEST['channel_thumb'];
$channel_published      = $_REQUEST['channel_published'];
$api_keys               = $key;

$sql            = "SELECT channel_id FROM i_channels WHERE channel_id = '$channel_id' ";
$result         = mysqli_query($conn, $sql);

if ( mysqli_num_rows($result) > 0 ) {
    echo 'Already Added';
} else {

    $params = array(
        'key' => $api_keys,
        // 'referer' => 'fake-refer',
        'apis' => array(
            'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
            'search.list'=> 'https://www.googleapis.com/youtube/v3/search',
            'channels.list'=> 'https://www.googleapis.com/youtube/v3/channels',
            'playlists.list'=> 'https://www.googleapis.com/youtube/v3/playlists',
            'playlistItems.list'=> 'https://www.googleapis.com/youtube/v3/playlistItems',
            'activities'=> 'https://www.googleapis.com/youtube/v3/activities',
        )
    );

    $youtube                    = new Madcoda\Youtube\Youtube($params);
    $search_video_in_channel    = $youtube->searchChannelVideos(' ', $channel_id, 5 );
    $data = [];

    foreach( $search_video_in_channel as $video ) {
        $data['video_ids'][] = $video->id->videoId;
        $data['channelId'] = $video->snippet->channelId;;
        $data['channelTitle'] = $video->snippet->channelTitle;
    }

    $video_ids          = json_encode($data['video_ids']);
    $channelId          = $data['channelId'];
    $channelTitle       = mysqli_real_escape_string( $conn, $data['channelTitle'] );
    $channelDescription = mysqli_real_escape_string( $conn, $channel_description );
    $channelThumb       = $channel_thumb;
    $cahnnelPublished   = $channel_published; 


    $sql = "INSERT INTO i_channels (channel_id, channel_title, channel_description, channel_published, channel_videos, channel_thumb)
    VALUES ( '$channelId', '$channelTitle', '$channelDescription', '$cahnnelPublished', '$video_ids', '$channelThumb' )";

    if (mysqli_query($conn, $sql)) {
        include "../includes/inc.php";
        $iN = new iN_UPDATES($db);
        $domainName         = $_SERVER['HTTP_HOST'];
        $email_address      = strtolower(str_replace(' ', '_', $channelTitle)).'@'.$domainName;
        $generated_password = rand(10000,90000);
        $password           = $iN->iN_Secure(sha1(md5($generated_password)));
        $username           = strtolower(str_replace(' ','_',$channelTitle));
        $fullname           = $channelTitle;
        $time               = time();

        $sql2 = "INSERT INTO i_users ( i_username, i_user_email, i_user_fullname, user_gender, registered, i_password ) VALUES ( '$username', '$email_address', '$fullname', 'male', '$time', '$password' )";

        if( mysqli_query( $conn, $sql2 ) ) {
            echo "success";
        } else {
            echo mysqli_error($conn);
            echo "error";
        }
    } else {
        echo mysqli_error($conn);
        echo "error";
    }
}