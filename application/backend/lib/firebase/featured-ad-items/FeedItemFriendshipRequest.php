<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeedItems;

use User;

final class FeedItemFriendshipRequest extends FeedItemBuilder {

    public function __construct(User $currentUser) {
        parent::__construct();
        $this->currentUser = $currentUser;
    }


    /** @var User $requestingUser */
    private $currentUser;


    public function setExpiry() {
        return FeedItem::EXPIRY_NEVER;
    }

    public function setConsumeOnView() {
        return false;
    }

    public function setContent() {
        return "<b>{$this->currentUser->getName()}</b> wants to catch you!";
    }

    protected function setLeftAvatar() {
        return $this->currentUser->getPictureUrl();
    }

    protected function setRightAvatar() {
        return null;
    }

    public function setActions() {
        return [FeedItem::ACTION_FRIENDSHIP_REQUEST_DENY, FeedItem::ACTION_FRIENDSHIP_REQUEST_ACCEPT];
    }

    protected function setClickAction() {
        return [FeedItem::ACTION_GOTO_USER_PROFILE];
    }

    public function setPayload() {
        $class = new \stdClass();
        $class->connectionId = $this->currentUser->getId();
        return $class;
    }

}