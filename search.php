<?php include_once "../includes/inc.php"; 

if( !isset( $_SESSION['iuid'] ) &&  $_SESSION['userType'] !== 2 ) {
    header("Location:https://phpstack-539799-2547821.cloudwaysapps.com/youtube-scrap");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube Profile Scrapper</title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Youtube Channel Search</h2>
            <form action="" id="channel-submit">
                <table class="table table-bordered">
                    <tr>
                        <td><input type="text" name="keyword" placeholder="Enter your keyword" class="form-control"></td>
                        <td colspan="2"><input type="submit" value="Search Channel" class="btn btn-success"></td>
                        <td>
                            <select name="no_of_result" id="" class="form-control">
                                <option value="">Select no of result to show</option>
                                <?php
                                for( $i = 1; $i <= 50; $i++ ) {
                                    echo "<option value='$i'>$i</option>";
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
            <div id="channelsearch"></div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="main.js"></script>
</body>
</html>