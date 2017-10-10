<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 - Fithancer © */

namespace Firebase;
use Firebase\FeedItems\FeedItemBuilder;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase;
use User;

class FeedManager {
    const _FIREBASE_DATABASE_URI = FIREBASE_DATABASE_URI;
    const _FIREBASE_API_KEY = FIREBASE_API_KEY;
    const _FIREBASE_PROJECT_ID = FIREBASE_PROJECT_ID;


    const _FIREBASE_ADMIN_SERVICE_CLIENT_ID = FIREBASE_ADMIN_SERVICE_CLIENT_ID;
    const _FIREBASE_ADMIN_SERVICE_CLIENT_EMAIL = FIREBASE_ADMIN_SERVICE_CLIENT_EMAIL;
    const _FIREBASE_ADMIN_SERVICE_PRIVATE_KEY = FIREBASE_ADMIN_SERVICE_PRIVATE_KEY;

    const _USER_FEEDS_PATH = 'usersFeed';
    const _USER_FEED_PATH_TEMPLATE = 'users/{UID}/feed';


    public static function build(User $currentUser) {
        return new FeedManager($currentUser);
    }


    private function __construct(User $currentUser) {
        $this->currentUser = $currentUser;

        $serviceAccount = ServiceAccount::fromArray([
            'project_id' => self::_FIREBASE_PROJECT_ID,
            'client_id' => self::_FIREBASE_ADMIN_SERVICE_CLIENT_ID,
            'client_email' => self::_FIREBASE_ADMIN_SERVICE_CLIENT_EMAIL,
            'private_key' => self::_FIREBASE_ADMIN_SERVICE_PRIVATE_KEY
        ]);

        $this->firebase = (new Factory())
            ->withServiceAccountAndApiKey($serviceAccount, self::_FIREBASE_API_KEY)
            ->withDatabaseUri(self::_FIREBASE_DATABASE_URI)
            ->create();
    }


    /** @var User */
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
            ->getReference(self::_USER_FEEDS_PATH)->push([])->getKey();

        $updateArray = [];
        $updateArray[self::_USER_FEEDS_PATH . '/' . $feedId] = $feedItemBuilder->build($feedId)->get();

        foreach ($toUids as $uid) {
            $userBaseFeedPath = strtr(self::_USER_FEED_PATH_TEMPLATE, ['{UID}' => $uid]);
            $updateArray[$userBaseFeedPath . '/' . $feedId] = $feedId;
        }

        $this->firebase->getDatabase()->getReference()
            ->update($updateArray);
    }

}