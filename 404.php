<?php
$rPath = $_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];
if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
    $rPath .= '?' . $_SERVER['REDIRECT_QUERY_STRING'];
}
header('Location: http://portal.com?r=' . urlencode($rPath));
?>
