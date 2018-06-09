<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase\FeedItems;

use User;
use Location;
use I18n\L;
use Api\ServerTextBuilder;

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
        return ServerTextBuilder::build()
            ->textBold($this->currentUser->getName())
            ->space()
            ->i18n(L::t_srv_feed_user_attendance_request_1)
            ->space()
            ->text($this->location->getName())
            ->space()
            ->i18n(L::t_srv_feed_user_attendance_request_2)
            ->get();
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
        return classFromArray(['locationId' => $this->location->getId()]);
    }

}