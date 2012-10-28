<?php 
if ($_GET['user'] != '' && is_numeric($_GET['user'])) {
    file_put_contents("allow.txt", "{$_GET['user']}\n",FILE_APPEND); 
}
?>
<script>
setTimeout(function(){
    var ww = window.open(window.location, '_self'); ww.close();
}, 1000);
</script>
