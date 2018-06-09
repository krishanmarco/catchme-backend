<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase;
use Firebase\FeaturedAdItems\FeaturedAdItemBuilder;
use Kreait\Firebase;

class FeaturedAdsManager {
    const FEATURED_ADS_PATH = 'featuredAds';
    const USER_FEATURED_ADS_PATH_TPL = 'users/{UID}/featuredAds';


    public static function build() {
        return new FeaturedAdsManager();
    }


    private function __construct() {
        $this->firebase = FirebaseHelper::getFirebaseConnection();
    }

    /** @var Firebase */
    private $firebase;


    public function postSingleFeaturedAd(FeaturedAdItemBuilder $featuredAdItemBuilder, $toUid) {
        $this->postMultipleFeaturedAds($featuredAdItemBuilder, [$toUid]);
    }

    /**
     * @param FeaturedAdItemBuilder $featuredAdItemBuilder
     * @param int[] $toUids
     */
    public function postMultipleFeaturedAds(FeaturedAdItemBuilder $featuredAdItemBuilder, array $toUids) {
        $faId = $this->firebase->getDatabase()
            ->getReference(self::FEATURED_ADS_PATH)->push([])->getKey();

        $updateArray = [];
        $updateArray[self::FEATURED_ADS_PATH . '/' . $faId] = $featuredAdItemBuilder->build($faId)->get();

        foreach ($toUids as $uid) {
            $userBaseFAPath = strtr(self::USER_FEATURED_ADS_PATH_TPL, ['{UID}' => $uid]);
            $updateArray[$userBaseFAPath . '/' . $faId] = $faId;
        }

        $this->firebase->getDatabase()->getReference()
            ->update($updateArray);
    }

}