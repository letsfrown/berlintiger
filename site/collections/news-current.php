<?php

return function ($kirby) {

    $today = new DateTime();
    $oneWeekAgo = $today->modify('-4 week');

    return $kirby->collection("news")->filterBy('date', '>', $oneWeekAgo->format('Y-m-d'));
};
