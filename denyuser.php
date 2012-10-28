<?php 
$list = json_decode($_POST['list'], true);
if ($_POST['list'] != '' && $list != NULL) {
    file_put_contents("deny.txt", implode("\n", $list)); 
}
?>
<script>
setTimeout(function(){
    var ww = window.open(window.location, '_self'); ww.close();
}, 1000);
</script>
