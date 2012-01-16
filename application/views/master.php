<?php 

echo View::make('master.header', array('title' => $title))->render();
echo $content;
echo View::make('master.footer')->render();
