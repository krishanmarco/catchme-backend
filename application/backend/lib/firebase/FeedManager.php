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
    const _FIREBASE_CLIENT_ID = FIREBASE_CLIENT_ID;
    const _FIREBASE_CLIENT_EMAIL = FIREBASE_CLIENT_EMAIL;
    const _FIREBASE_PRIVATE_KEY = FIREBASE_ADMIN_PRIVATE_KEY;

    const _USER_FEEDS_PATH = 'usersFeed';


    public static function build(User $currentUser) {
        return new FeedManager($currentUser);
    }


    private function __construct(User $currentUser) {
        $this->currentUser = $currentUser;

        $serviceAccount = ServiceAccount::fromArray([
            'project_id' => self::_FIREBASE_PROJECT_ID,
            'client_id' => self::_FIREBASE_CLIENT_ID,
            'client_email' => self::_FIREBASE_CLIENT_EMAIL,
            'private_key' => self::_FIREBASE_PRIVATE_KEY
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
        $this->firebase->getDatabase()->getReference()
            ->update($this->buildUpdateItem($feedItemBuilder, $toUid));
    }


    /**
     * @param FeedItemBuilder $feedItemBuilder
     * @param int[] $toUids
     */
    public function postMultipleFeeds(FeedItemBuilder $feedItemBuilder, array $toUids) {
        $updateArray = [];

        foreach ($toUids as $userId)
            $this->buildUpdateItem($feedItemBuilder, $userId, $updateArray);

        $this->firebase->getDatabase()->getReference()
            ->update($updateArray);
    }


    /** @return array */
    private function buildUpdateItem(FeedItemBuilder $feedItemBuilder, $toUid, array $toArray = []) {
        $database = $this->firebase->getDatabase();

        $feedPath = self::_USER_FEEDS_PATH . '/' . $feedItemBuilder->getDestinationId();

        // Get the users feed databasem, add an empty item to the
        // users feed ($ref) and get the automatically generated id
        $itemKey = $database->getReference($feedPath)->push([])->getKey();

        // Build and add the update item to the accumulator ($toArray) and return
        $toArray[$feedPath . "/${itemKey}"] = $feedItemBuilder->buildForUser($itemKey, $toUid)->get();
        return $toArray;
    }



}