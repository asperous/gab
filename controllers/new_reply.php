<?php


$data = array(
    "text" => htmlentities($_POST['text']),
    "topic_id" => htmlentities($_POST['topic_id']),
    "text_b" => $_POST['text_b'],
    "user" => $_SESSION['user_logged_in'],
    "user_name" => $_SESSION['user_name'],
    "user_email_hash" => $_SESSION['user_email_hash'],
);

if (!$data['text_b'] && $data['text'] && $data['topic_id'] && $data['user']) {

    $this->caching = 0;

    //$errors = validateFields($data, $validators);
    if (empty($errors)) {
        $post_id = forum::post_reply(
            $data['topic_id'],
            $data['user'],
            $data['user_name'],
            $data['user_email_hash'],
            $data['text']);

        $this->clearCache('forum/post.tpl', $data['topic_id']);
        setcookie ("reply_url", "", time() - 3600, "/");
        setcookie ("reply_text", "", time() - 3600, "/");
        $baseurl = $this->baseurl;
        header("Location: {$baseurl}/${data['topic_id']}#post${post_id}");

        if (!$GLOBALS['testing']) exit;
    } else {
        $GLOBALS['cache_id'] = hash("md4", implode("_", $errors)) . "|" . $GLOBALS['cache_id'];
        $this->assign("posterror", true);
        $this->assign("posterrors", $errors);
    }
}
