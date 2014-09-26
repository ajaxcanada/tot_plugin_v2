<?php















function save_file() {

    define('UPLOADS', 'wp-content/uploads/user_files/2014/09');

    if ($_FILES) {
        $files = $_FILES["kv_multiple_attachments"];
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                $_FILES = array("kv_multiple_attachments" => $file);
                foreach ($_FILES as $file => $array) {
                    $newupload = kv_handle_attachment($file, $pid);
                    //$returns .= $newupload. "  ". $files['name'];
                    $uploaded_file_name .= $newupload;
                    //." <name> ".$files['name'][$key];
                    //<img src='".$uploads['url'] ."/". $attach_id."'>";
                }
            }
        }
    }
    return $uploaded_file_name;
}


function kv_handle_attachment($file_handler, $post_id, $set_thu = false) {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK)
        __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    $attachment = get_attached_file($attach_id); // Gets path to attachment
    $uploads = wp_upload_dir();
    return $attach_id;
     
}


























/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//**************************************
// Attachment Insert Form
//**************************************
function insert_attachment_form($postID) {
?>
	<form id="file-form" name="file-form" method="POST" action="" enctype="multipart/form-data" >
		<input type="file" id="async-upload" name="async-upload" />
		<input type="hidden" name="postID" value="<?php echo $postID; ?>" />
		<?php wp_nonce_field('client-file-upload', 'client-file-upload'); ?>
		<input type="submit" value="Upload" id="submit" name="submit" />
	</form>
<?php } 

//**************************************
// Process Attachment Form
//**************************************
function process_attachment() {
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['client-file-upload'], 'client-file-upload') ) {
        return $post->ID;
    }

    // Is the user allowed to edit the post or page?
	if ( !current_user_can( 'publish_posts', $post->ID ))
		return $post->ID;

	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_FILES )) {
		require_once(ABSPATH . 'wp-admin/includes/admin.php');
		$id = media_handle_upload('async-upload', $_POST['postID']);
		unset($_FILES);
	}
}

$out .= "<form id='file-form' name='file-form' method='POST' action='' enctype='multipart/form-data' >";
		$out .= "<input type='hidden' name='postID' value='<?php echo $postID; ?>' />";
		
		$out .= "<input type='submit' value='Upload' id='submit' name='submit' />";
	$out .= "</form>";