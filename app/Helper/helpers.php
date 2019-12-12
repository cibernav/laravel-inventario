<?php
function active($patch){
    return request()->is($patch)? 'active' : '';
}

