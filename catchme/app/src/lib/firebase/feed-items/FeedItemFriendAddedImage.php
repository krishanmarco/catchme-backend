<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase\FeedItems;

use User;
use Location;
use LocationImage;
use I18n\L;
use Api\ServerTextBuilder;

final class FeedItemFriendAddedImage extends FeedItemBuilder {

    public function __construct(User $authUser,
                                Location $location,
                                LocationImage $locationImage) {
        parent::__construct();
        $this->currentUser = $authUser;
        $this->location = $location;
        $this->locationImage = $locationImage;
    }


    /** @var User $requestingUser */
    private $currentUser;
    /** @var Location $location */
    private $location;
    /** @var LocationImage $locationImage */
    private $locationImage;


    public function setExpiry() {
        return $this->getTime() + LOCATION_MEDIA_TTL;
    }

    public function setConsumeOnView() {
        return false;
    }

    public function setContent() {
        return ServerTextBuilder::build()
            ->textBold($this->currentUser->getName())
            ->space()
            ->i18n(L::t_srv_feed_friend_added_image)
            ->space()
            ->text($this->location->getName())
            ->get();
    }

    protected function setLeftAvatar() {
        return $this->currentUser->getPictureUrl();
    }

    protected function setRightAvatar() {
        return $this->locationImage->getUrl();
    }

    public function setActions() {
        return [];
    }

    protected function setClickAction() {
        return [FeedItem::ACTION_GOTO_LOCATION_PROFILE];
    }

    public function setPayload() {
        return classFromArray(['locationId' => $this->location->getId()]);
    }

}