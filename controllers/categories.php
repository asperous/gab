<?php

$this->assign("forum_section", "cat");
$this->assign("categories", forum::get_categories());