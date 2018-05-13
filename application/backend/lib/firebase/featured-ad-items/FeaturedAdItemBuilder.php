<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase\FeaturedAdItems;

/**
    FeaturedAdItem: {
        id: 1,                                      // (P) (!) Unique feed item identifier
        time: 10000000000,                          // (P) (!) Seconds in which the feed was generated

        title: 'AreaDocks - DJ Alvarez'             // (P) (!) Title text
        subTitle: 'Monday 27 October @AreaDocks'    // (P) (?) Subtitle text
        image: 'http://ctvh.com/image.png'          // (P) (!) Background image

        expiry: -1,                                 // (C) (!) Seconds of expiry, if expired the item is not displayed (-1 means never)
        clickAction: 'LocationGoToProfile',         // (C) (?) Action to be triggered when the item is clicked
        actions: [                                  // (C) (?) {ACTION}s. When an action is triggered a feed object is passed in
            'FriendshipRequestAccept',              // (C) (?) {ACTION}
            'FriendshipRequestDeny',                // (C) (?) {ACTION}
            ...                                     // (C) (?) {ACTION}
        ],
        payload: {...}                              // (C) (!) Payload data for each action
    }
 **/
class FeaturedAdItem {
    const EXPIRY_NEVER = -1;

    const ACTION_FRIENDSHIP_REQUEST_ACCEPT = 'FriendshipRequestAccept';
    const ACTION_FRIENDSHIP_REQUEST_DENY = 'FriendshipRequestDeny';
    const ACTION_ATTENDANCE_CONFIRM = 'AttendanceConfirm';
    const ACTION_LOCATION_FOLLOW = 'LocationFollow';
    const ACTION_GOTO_LOCATION_PROFILE = 'GoToLocationProfile';
    const ACTION_GOTO_USER_PROFILE = 'GoToUserProfile';


    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Array that has all the non-null properties in FeaturedAdItemBuilder
     * -------------------------------------------------------------
     * @var array $data*/
    private $data;
    public function get() { return $this->data; }

}




abstract class FeaturedAdItemBuilder {

    protected function __construct() {
        $this->time = time();
    }

    /** @var String $id */
    private $id;

    /** @var int $time */
    private $time;
    public function getTime() { return $this->time; }


    /** @var int $expiry */
    private $expiry;
    protected abstract function setExpiry();

    /** @var String $title */
    private $title;
    protected abstract function setTitle();

    /** @var String $subTitle */
    private $subTitle;
    protected abstract function setSubTitle();

    /** @var String $image */
    private $image;
    protected abstract function setImage();

    /** @var String[] $actions */
    private $actions = [];
    protected abstract function setActions();

    /** @var String $clickAction */
    private $clickAction;
    protected abstract function setClickAction();

    /** @var \stdClass $payload */
    private $payload;
    protected abstract function setPayload();




    /** @return FeaturedAdItem */
    public function build($featuredAdId) {
        $this->id = $featuredAdId;
        $this->expiry = $this->setExpiry();
        $this->title = $this->setTitle();
        $this->subTitle = $this->setSubTitle();
        $this->image = $this->setImage();
        $this->actions = $this->setActions();
        $this->payload = $this->setPayload();
        $this->clickAction = $this->setClickAction();
        return new FeaturedAdItem(get_object_vars($this));
    }

}