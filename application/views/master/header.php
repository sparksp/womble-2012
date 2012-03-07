<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title><?php echo $title ? "$title - Womble" : 'Womble'; ?></title>

	<meta name="author" content="Phill Sparks">
	<meta name="dcterms.rights" content="Copyright (c) Leicestershire County Scout Council. All rights reserved.">
	<meta name="generator" content="Laravel/2.1.0 (laravel.com)">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">

	<link href="<?php echo URL::to_asset('favicon.ico'); ?>" type="image/x-icon" rel="shortcut icon">
	<link href="<?php echo URL::to_asset('css/womble.min.css'); ?>" rel="stylesheet" type="text/css">
	<?php echo Asset::styles(); ?>

	<style type="text/css"><?php echo \Laravel\Section::yield('styles'); ?></style>
	<?php echo Asset::scripts(); ?>

	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>

	<header class="navbar navbar-fixed-top"><div class="navbar-inner"><div class="container">
		<?php echo HTML::link("/", "Womble", array('class' => 'brand')); ?>
		<nav><?php echo Menu::make(array('class' => 'nav'))
			->add("/activities/canoeing", "Canoeing")
			->add("/activities/caving", "Caving")
			->add("/activities/climbing", "Climbing")
			->add("/activities/biking", "Mountain Biking")
			->add("/activities/walking", "Walking")
			->add("/booking/new", "Book Now", array('class' => 'book-now'))
			->add("/staff", "Who's Who") // pull-right
			->get();
		?></nav>
	</div></div></header>

	<?php echo Section::yield('breadcrumbs'); ?>

	<div id="body" class="container">
		<section>

		<?php if (Session::has('message')): ?>
			<div class="alert alert-info"><?php echo Session::get('message'); ?></div>
		<?php endif; ?>
		<?php if (Session::has('success')): ?>
			<div class="alert alert-success"><?php echo Session::get('success'); ?></div>
		<?php endif; ?>
		<?php if (Session::has('warning')): ?>
			<div class="alert alert-warning"><?php echo Session::get('warning'); ?></div>
		<?php endif; ?>
		<?php if (Session::has('error')): ?>
			<div class="alert alert-error"><?php echo Session::get('error'); ?></div>
		<?php endif; ?>
