<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeedItems;

use User;
use Location;
use LocationImage;

final class FeedItemFriendAddedImage extends FeedItemBuilder {

    public function __construct(User $currentUser,
                                Location $location,
                                LocationImage $locationImage) {
        parent::__construct();
        $this->currentUser = $currentUser;
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
        return "<b>{$this->currentUser->getName()}</b> posted a picture at {$this->location->getName()}.<br>Tap to check it out!";
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
        $class = new \stdClass();
        $class->locationId = $this->location->getId();
        return $class;
    }

}