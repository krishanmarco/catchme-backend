<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeedItems;

use User;
use I18n\L;
use Api\ServerTextBuilder;

final class FeedItemFriendshipAccept extends FeedItemBuilder {

    public function __construct(User $authUser) {
        parent::__construct();
        $this->authUser = $authUser;
    }

    /** @var User */
    private $authUser;

    public function setExpiry() {
        return FeedItem::EXPIRY_NEVER;
    }

    public function setConsumeOnView() {
        return true;
    }

    public function setContent() {
        return ServerTextBuilder::build()
            ->textBold($this->authUser->getName())
            ->space()
            ->i18n(L::t_srv_feed_friendship_accept)
            ->get();
    }

    protected function setLeftAvatar() {
        return $this->authUser->getPictureUrl();
    }

    protected function setRightAvatar() {
        return null;
    }

    public function setActions() {
        return [];
    }

    protected function setClickAction() {
        return [FeedItem::ACTION_GOTO_USER_PROFILE];
    }

    public function setPayload() {
        return classFromArray(['connectionId' => $this->authUser->getId()]);
    }

}