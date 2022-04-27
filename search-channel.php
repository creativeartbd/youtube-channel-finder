<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', TRUE); 
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'config.php';

$api_keys = $key2;
$params = array(
    'key' => $api_keys,
    'referer' => 'fake-refer',
    'apis' => array(
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos',
        'search.list'=> 'https://www.googleapis.com/youtube/v3/search',
        'channels.list'=> 'https://www.googleapis.com/youtube/v3/channels',
        'playlists.list'=> 'https://www.googleapis.com/youtube/v3/playlists',
        'playlistItems.list'=> 'https://www.googleapis.com/youtube/v3/playlistItems',
        'activities'=> 'https://www.googleapis.com/youtube/v3/activities',
    )
);


$youtube = new Madcoda\Youtube\Youtube($params);
$keyword = trim($_POST['keyword']);
$no_of_result = !empty( $_POST['no_of_result'] ) ? trim($_POST['no_of_result']) : 3;

if(empty($keyword)){
    echo "<div class='alert alert-warning'>";
    echo "Please enter keyword";
    echo "</div>";
} else {

    // $search_video_by_id = $youtube->getVideoInfo('de-EcTKoPx0'); // search video by id 
    // $search_video_in_channel = $youtube->searchChannelVideos('html', 'UCkdPCRKSzZTVB9eo9CDdTQw', 100); // search video in channel and can pass limit
    // $search_channel_by_name = $youtube->getChannelByName($keyword, $params);
    //$search_channel_by_id = $youtube->getChannelById( 'UCkdPCRKSzZTVB9eo9CDdTQw', $params ); // amar course
    // $get_channel_videos = $youtube->searchChannelVideos('Amar Course', 'UCkdPCRKSzZTVB9eo9CDdTQw');
    // echo '<pre>';
    // print_r($search_channel_by_name);
    // die();

    $search_video_by_string = $youtube->search($keyword, $no_of_result); // pass search query and limit
    $count = 1;

    echo "<table class='table table-bordered'>";
        echo "<tr>";
            echo "<th>SL.</th>";
            echo "<th>Channel ID</th>";
            echo "<th>Channel Name</th>";
            echo "<th>Channel Link</th>";
            echo "<th>Channel Thumb</th>";
            echo "<th>Published</th>";
            echo "<th>Action</th>";
        echo "</tr>";

    // echo '<pre>';
    //      print_r( $search_video_by_string );
    // echo '</pre>';
    $store_channel_id = [];

    foreach( $search_video_by_string as $channel ) {

        $channel_id             = $channel->snippet->channelId;
        $channelTitle           = $channel->snippet->channelTitle;
        $channelPublished       = $channel->snippet->publishTime;
        $old_channel_id         = $channel_id;
        
        $search_channel_by_id   = $youtube->getChannelById( $channel_id, $params );
        $channelThumb           = $search_channel_by_id->snippet->thumbnails->high->url;
        $cahnnelPublished       = $search_channel_by_id->snippet->publishedAt; 
        $channelDescription     = $search_channel_by_id->snippet->description;
        
        if( ! in_array( $channel_id, $store_channel_id ) ) {
            echo "<tr>";
                echo "<td>$count</td>";
                echo "<td>$channel_id</td>";
                echo "<td>$channelTitle</td>";
                echo "<td><a href='https://www.youtube.com/channel/{$channel_id}' target='_blank'>Visit Channel</a></td>";
                echo "<td><img src='$channelThumb' width='100'/></td>";
                // print_r($channel);
                echo "<td>$channelPublished</td>";
                echo "<td><a href='#' data-channel-id='$channel_id' data-channel-thumb='$channelThumb' data-channel-published='$channelPublished' data-channel-description='$channelDescription' class='importChannel btn btn-primary btn-sm'>Import Content</a></td>";
            echo "</tr>";
            array_push( $store_channel_id, $channel_id );
        }
        
        $count++;
    }

    echo "</table>";
}
