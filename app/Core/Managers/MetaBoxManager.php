<?php

namespace WC_BE\Core\Managers;

use WC_BE\Core\Factories\MetaBoxFactory;
use WC_BE\Http\Contracts\MetaBox\MetaBoxInterface;

class MetaBoxManager
{
    public function __construct()
    {
        $this->addAction();
    }
    public function addAction() : void
    {
        $metaBox = $this->getMetaBox();

        if ($metaBox === null) {
            return;
        }

        add_action('add_meta_boxes', [$metaBox, 'addMetaBox']);
        add_action('save_post', [$metaBox, 'saveMetaBox']);
    }

    public function getMetaBox() : ?MetaBoxInterface
    {
        $postType = $this->getWooCommerceOrderPostType();

        if ($postType) {
            return MetaBoxFactory::create($postType);
        }

        return null;
    }

    private function getWooCommerceOrderPostType(): ?string
    {
        $postType = get_post_type();

        if (empty($postType) && isset($_GET['id'], $_GET['page']) && $_GET['page'] === 'wc-orders') {
            $postId = intval($_GET['id']);
            $postType = get_post_type($postId);
        }

        if ($postType === 'shop_order_placehold') {
            $postType = 'shop_order';
        }

        return $postType;
    }
}