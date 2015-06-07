<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
function millisecond()
{
    return ceil(microtime(true) * 1000);
}