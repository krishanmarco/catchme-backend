<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeedItems;

use User;
use Location;

final class FeedItemUserAttendanceRequest extends FeedItemBuilder {

    public function __construct(User $authUser, Location $location) {
        parent::__construct();
        $this->currentUser = $authUser;
        $this->location = $location;
    }


    /** @var User $currentUser */
    private $currentUser;
    /** @var Location $location */
    private $location;


    public function setExpiry() {
        return FeedItem::EXPIRY_NEVER;
    }

    public function setConsumeOnView() {
        return false;
    }

    public function setContent() {
        return [
            ["{$this->currentUser->getName()} ", classFromArray(['fontWeight' => 'bold'])],
            ["will be at {$this->location->getName()}, will you be there too?"],
        ];
    }

    protected function setLeftAvatar() {
        return $this->currentUser->getPictureUrl();
    }

    protected function setRightAvatar() {
        return null;
    }

    public function setActions() {
        return [FeedItem::ACTION_ATTENDANCE_CONFIRM];
    }

    protected function setClickAction() {
        return [FeedItem::ACTION_GOTO_LOCATION_PROFILE];
    }

    public function setPayload() {
        return classFromArray([
            'locationId' => $this->location->getId()
        ]);
    }
    
}