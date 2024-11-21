<?php

namespace WC_BE\Core\Contracts;

interface MetaBoxInterface
{
    public function addMetaBox();
    public function saveMetaBox($post_id);
}