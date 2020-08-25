<?php

include('library/fetch_db_obj.php');
include('library/display_photos.php');

?>

<div class='photo_gallery'>

<?php

display_photos('user');

?>

</div>
<script type="text/javascript" src="js/likes_comments.js"></script>