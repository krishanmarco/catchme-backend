<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase\FeedItems;
use stdClass;

/**
    FeedItem: {
        id: 1,                                      // (P) (!) Unique feed item identifier
        userId: 1,                                  // (P) (!) The object is destined for this users feed
        time: 10000000000,                          // (P) (!) Seconds in which the feed was generated

        expiry: -1,                                 // (C) (!) Seconds of expiry, if expired the item is not displayed (-1 means never)
        consumeOnView: true,                        // (C) (!) If true the item is deleted from the realtime db once it has been viewed

        leftAvatar: 'http://ctvh.com/left.png'      // (C) (?) Left avatar
        rightAvatar: 'http://ctvh.com/left.png'     // (C) (?) Right avatar
        content: '<b>Hi! </b>how are you today?'    // (C) (!) Middle text (HTML)
        clickAction: 'LocationGoToProfile',         // (C) (?) Action to be triggered when the item is clicked
        actions: [                                  // (C) (?) {ACTION}s. When an action is triggered a feed object is passed in
            'FriendshipRequestAccept',              // (C) (?) {ACTION}
            'FriendshipRequestDeny',                // (C) (?) {ACTION}
            ...                                     // (C) (?) {ACTION}
        ],
        payload: {...}                              // (C) (!) Field that is unique for each feed item
    }
 **/
class FeedItem {
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
     * Array that has all the non-null properties in FeedItemBuilder
     * -------------------------------------------------------------
     * @var array $data*/
    private $data;
    public function get() { return $this->data; }

}




abstract class FeedItemBuilder {

    protected function __construct() {
        $this->time = time();
    }


    /** @var String */
    private $id;

    /** @var int */
    private $time;
    public function getTime() { return $this->time; }



    /** @var int */
    private $expiry;
    protected abstract function setExpiry();

    /** @var boolean */
    private $consumeOnView;
    protected abstract function setConsumeOnView();

    /** @var Object[][] */
    private $content;
    protected abstract function setContent();

    /** @var String */
    private $leftAvatar;
    protected abstract function setLeftAvatar();

    /** @var String */
    private $rightAvatar;
    protected abstract function setRightAvatar();

    /** @var String[] */
    private $actions = [];
    protected abstract function setActions();

    /** @var String */
    private $clickAction;
    protected abstract function setClickAction();

    /** @var stdClass */
    private $payload;
    protected abstract function setPayload();




    /** @return FeedItem */
    public function build($feedId) {
        $this->id = $feedId;
        $this->expiry = $this->setExpiry();
        $this->consumeOnView = $this->setConsumeOnView();
        $this->content = $this->setContent();
        $this->actions = $this->setActions();
        $this->payload = $this->setPayload();
        $this->leftAvatar = $this->setLeftAvatar();
        $this->rightAvatar = $this->setRightAvatar();
        $this->clickAction = $this->setClickAction();
        return new FeedItem(get_object_vars($this));
    }

}