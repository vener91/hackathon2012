<!DOCTYPE html>
<html>
    <head>
        <title>Hacker's Net User Mangement</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="js/all.js"></script>
        <script src="js/jquery.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="http://connect.facebook.net/en_US/all.js"></script>
    </head>
    <body>
        <div class="action-bar"></div>
        <div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
        appId      : '330802313684158', // App ID from the App Dashboard
            status     : true, // check the login status upon init?
            cookie     : true, // set sessions cookies to allow your server to access the session?
            xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here
    FB.login(function(response) {
        FB.api('/me/friends', function(response){
            var $friendList = $("#friend-list");
            var $friendSample = $("#friend-sample");
            for (var i = 0; i < response.data.length; i++) {
                $newFriend = $friendSample.clone().removeAttr('id').appendTo($friendList).attr('data-id', response.data[i].id);
                $newFriend.find('img').attr('src', 'http://graph.facebook.com/' + response.data[i].id + '/picture');
                $newFriend.find('.name').text(response.data[i].name);
            }
            FB.api('/me/friendlists', function(response) {
                var fid = null;
                for (var i = 0; i < response.data.length; i++) {
                    if (response.data[i].name == "Deny Social Pass") {
                        fid = response.data[i].id;
                        break;
                    }
                }
                if (fid != null) {
                    FB.api('/' + fid + '/members', function(response){
                        var $friendList = $("#deny-list");
                        var $friendSample = $("#friend-sample");
                        for (var i = 0; i < response.data.length; i++) {
                            $newFriend = $friendSample.clone().removeAttr('id').appendTo($friendList).attr('data-id', response.data[i].id);
                            $newFriend.find('img').attr('src', 'http://graph.facebook.com/' + response.data[i].id + '/picture');
                            $newFriend.find('.name').text(response.data[i].name);
                            //Remove from friend list
                            $('#friend-list > div[data-id="' + response.data[i].id + '"]').remove();
                            
                        }
                        //Set up interactions
                        $('#friend-list, #deny-list').sortable({
                            connectWith: ".connected",
                            receive: function(e, list){
                                var id = $(list.item[0]).attr('data-id');
                                if ($(e.target).attr('id') == 'deny-list'){
                                    //Adding
                                    FB.api('/' + fid + '/members/' + id, 'POST', function(response){
                                        console.info(response);
                                    });
                                } else {
                                    //Removing
                                    FB.api('/' + fid + '/members/' + id, 'DELETE', function(response){
                                        console.info(response);
                                    });
                                }
                                var denyList = [];
                                $("#deny-list > div").each(function(key, element){
                                    denyList.push($(element).attr('data-id')); 
                                });
                                $.post("http://portal.com/denyuser.php", {list: JSON.stringify(denyList)}, function(response){
                                    console.info(response);
                                });
                            }
                        }).disableSelection();

                    });
                }
            });
        });

    }, {scope: 'read_friendlists, manage_friendlists'});

};

$(document).ready(function(){
    fbAsyncInit();
});
</script>
        <div id="title">
            <div>Friend List</div>
            <div>Deny List</div>
        </div>
        <div id="friend-bar" draggable="true">
            <div id="friend-list" class="sortable connected">
            </div>
            <div id="deny-list" class="sortable connected">
            </div>
            <div class="friend" id="friend-sample"><img /><div class="name"></div></div>
        </div>
    </body>
</html>
