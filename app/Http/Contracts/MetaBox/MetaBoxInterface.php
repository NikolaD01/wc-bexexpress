<?php

namespace WC_BE\Http\Contracts\MetaBox;

interface MetaBoxInterface
{
    public function addMetaBox();
    public function saveMetaBox($post_id);
}