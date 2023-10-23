<?php

/***
 * @param $html
 * @return string
 *
 *
 */
function escapar($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}