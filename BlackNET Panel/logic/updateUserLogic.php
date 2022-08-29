<?php

$settings = new BlackNET\Settings($database);

$user_question = $user->getQuestionByUser($data->username);

$questions = $settings->getPreDefinedQuestions();

$page = "update_user";
