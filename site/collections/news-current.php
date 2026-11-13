<?php

return function ($kirby) {

    $fourWeeksAgo = (new DateTime())->modify('-4 weeks');

    return $kirby->collection('news')->filterBy('date', '>', $fourWeeksAgo->format('Y-m-d'));
};
