<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 13/09/2017 - Fithancer © */

namespace Controllers;

use Api\FormFeaturedAdAdd;
use Firebase\FeaturedAdItems\FeaturedAdItem;
use Firebase\FeaturedAdItems\FeaturedAdItemAttendanceRequest;
use Firebase\FeaturedAdsManager;
use Models\Feed\MultiNotificationManager;
use User as DbUser;
use R;

class ControllerAdmin {

    public function __construct(DbUser $authAdmin) {
        $this->authAdmin = $authAdmin;
    }

    /** @var DbUser $authAdmin */
    private $authAdmin;


    public function sendFeaturedAdAttendanceRequest(FormFeaturedAdAdd $featuredAd) {
// todo: why MultiFeedManager???
        $mfm = new MultiNotificationManager($this->authAdmin);

        // Add the notification item to firebase
        FeaturedAdsManager::build()
            ->postMultipleFeaturedAds(
                new FeaturedAdItemAttendanceRequest($featuredAd),
                $mfm->getNotifiableFriendIds()
            );

        return R::return_ok;
    }


}