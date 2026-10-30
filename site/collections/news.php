<?php

return function ($site) {
    return $site->find("news")->children()->listed()->flip();
};