<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase;

use Firebase\FeedItems\FeedItemBuilder;
use Kreait\Firebase;
use User as DbUser;

class FeedManager {
    const FEEDS_PATH = 'usersFeed';
    const USER_FEEDS_PATH_TPL = 'users/{UID}/feed';


    public static function build(DbUser $currentUser) {
        return new FeedManager($currentUser);
    }


    private function __construct(DbUser $currentUser) {
        $this->currentUser = $currentUser;
        $this->firebase = FirebaseHelper::getFirebaseConnection();
    }


    /** @var DbUser */
    private $currentUser;

    /** @var Firebase */
    private $firebase;


    public function postSingleFeed(FeedItemBuilder $feedItemBuilder, $toUid) {
        $this->postMultipleFeeds($feedItemBuilder, [$toUid]);
    }


    /**
     * @param FeedItemBuilder $feedItemBuilder
     * @param int[] $toUids
     */
    public function postMultipleFeeds(FeedItemBuilder $feedItemBuilder, array $toUids) {

        $feedId = $this->firebase->getDatabase()
            ->getReference(self::FEEDS_PATH)->push([])->getKey();

        $updateArray = [];
        $updateArray[self::FEEDS_PATH . '/' . $feedId] = $feedItemBuilder->build($feedId)->get();

        foreach ($toUids as $uid) {
            $userBaseFeedPath = strtr(self::USER_FEEDS_PATH_TPL, ['{UID}' => $uid]);
            $updateArray[$userBaseFeedPath . '/' . $feedId] = $feedId;
        }

        $this->firebase->getDatabase()->getReference()
            ->update($updateArray);
    }

}