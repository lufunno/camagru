<?php

function display_photos($type){
	include('library/fetch_db_obj.php');

if ($type == 'all')
	$nb_photos = $db->count_photos();
else
	$nb_photos = $_SESSION['logged_on_user']->nb_photos;
$limit = 6;//($type == 'all'? 6 : 10);
if (!isset($_GET['page']) || $_GET['page'] == 0)
		$page = 1;
else
	$page = $_GET['page'];
if ($page < 0 || (($page - 1) * $limit) >= $nb_photos)
	$page = 1; // page does not exist instead?
if ($type == 'all')
	$photos = $db->get_photos($page, $limit);
else
	$photos = $db->get_user_photos($page, $limit);
foreach ($photos as $i => $photo) { // include file for comments/like section?
	$is_liked = NULL;
	$comments = $db->get_comments($photo['name']);
	if ($db->does_user_like($_SESSION['logged_on_user']->username, $photo[ 'name']))
		$is_liked="style='background: black;'";
	echo ("
			<div id='gallery_section_" . $photo['name'] . "' class='gallery_section col-lg-3 col-md-5 col-sm-11'>");
				if ($type != 'all')
				echo("	<div class='col-lg-12 col-md-12 col-sm-12'>
							<input type='image' src='img/delete.png'  id='delete_photo_" . $photo['name'] . "' class='delete_photo col-lg-2 col-md-2 col-sm-2'>
			 				<input type='image' src='img/download.png'  id='download_photo_" . $photo['name'] . "' class='download_photo col-lg-2 col-md-2 col-sm-2' action='delete_photo.php'>
			 				</div>
			 		");
			?>
				<div class='image_section'>
		<?php
					echo("<img id='" . $photo['name'] . "' class='col-lg-12 col-md-12 col-sm-12 gallery_img' src='img/user/" . $photo['user_id'] . '/' . $photo['name']. ".png'>
		<div id='comments_" . $photo['name'] . "' class='comment_section'>");
		if ($comments){
			foreach ($comments as $key => $comment){
				echo ("<p class='comment' id='comment_" . $key . "_" . $photo['name'] . "'><span class='comment_title'><span class='comment_username'>"
					. $comment['user_id'] . "</span> said on " . $comment['date'] .  "</span><p class='comment_text'>"
		 			. $comment['comment'] . "</p></p>");
			}
		}

		echo("</div>
				</div>
			<div class='col-lg-12 col-md-12 col-sm-12 like_comment'>
				<div class='like_div col-lg-3 col-md-3 col-sm-3'>
					<input id='like_" . $photo['name'] . "' class='like' type='image' src='img/like.png'" . $is_liked . ">
					<span class='nb_likes' id='nb_likes_" . $photo['name'] . "'>" . strval($db->get_nb_likes($photo['name'])) . "</span>
				</div>
				<div class='comment_buttons col-lg-9 col-md-9 col-sm-9'>
					<p>Photo by " . $photo['user'] . "</p>
					<button class='show_comments' id='show_comments_" . $photo['name'] . "'>
							" . $nb_comm = count($comments) . " comment");
							if ($nb_comm != 1)
								echo "s";
				echo ("</button>
					<button class='hide_comments' id='hide_comments_" . $photo['name'] . "'>Hide comments</button>

				</div>

		</div>

		<form id='add_comment_" . $photo['name'] . "' class='col-lg-12 add_comment form' method='post' action='add_comment.php?photo=" . $photo['name'] . "&page=" . $page . "'>
					<textarea maxlength=255 name='comment' value='clear' id='to_add_comment_" . $photo['name'] . "'></textarea>
					<input type='submit' name='add_comment_$i' value='Comment'>
				</form>
				</div>");
	}
	echo "</div><div class='next_prev'>";
	echo $page . '</br>';
	$get = ($type == 'all') ? "" : "act=pht&";
	if (($page - 1) > 0)
		echo ("<a href='?" . $get . "page=" . ($page - 1) . "'> Previous</a>");
	if (($page * $limit) <= $nb_photos)
		echo("<a href='?" . $get . "page=" . ($page + 1) . "'> Next</a>");
	echo "</div>";

}

?>