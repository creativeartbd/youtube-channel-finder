$(document).ready(function() {
    
    $('#channel-submit').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "search-channel.php",
            data: $(this).serialize(),
            beforeSend: function () {
                $("#channelsearch").html("Please wait...");
            },
            success: function (result) {
                $("#channelsearch").html(result);
            }    
        });
    });

    $(document).on("click",".importChannel",function(e) {

        e.preventDefault(); 

        var that                = $(this);
        var channel_id          = that.data("channel-id");
        var channel_description = that.data("channel-description");
        var channel_thumb       = that.data("channel-thumb");
        var channel_published   = that.data("channel-published");
        
        $.ajax({
            type: "POST",
            url: "save-channel.php",
            data: {
                'channel_id'            : channel_id,
                'channel_description'   : channel_description,
                'channel_thumb'         : channel_thumb,
                'channel_published'     : channel_published,
            },
            beforeSend: function () {
                that.text("Importing....");
                that.addClass("disabled");
            },
            success: function (result) {
                if(result == 'success') {
                    that.text("Imported");
                    that.addClass("btn-success");
                } else {
                    that.addClass("btn-danger");
                    that.removeClass("disabled");
                    that.text(result);
                }
            }    
        });
    })

});