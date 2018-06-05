<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeedItems;

use User;
use Location;
use LocationImage;
use I18n\L;

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
        return [
            ["{$this->currentUser->getName()} ", classFromArray(['fontWeight' => 'bold'])],
            [L::app_feed_friend_added_image . " {$this->location->getName()}"]
        ];
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