<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 07/10/2017 */

namespace Firebase\FeaturedAdItems;

use Api\FormFeaturedAdAdd;

final class FeaturedAdItemAttendanceRequest extends FeaturedAdItemBuilder {

    public function __construct(FormFeaturedAdAdd $formFeaturedAdAdd) {
        parent::__construct();
        $this->featuredAd = $formFeaturedAdAdd;
    }

    /** @var FormFeaturedAdAdd $featuredAd */
    private $featuredAd;

    public function setExpiry() {
        if (isset($this->featuredAd->expiry))
            return $this->featuredAd->expiry;
        return FeaturedAdItem::EXPIRY_NEVER;
    }

    protected function setTitle() {
        return $this->featuredAd->title;
    }

    protected function setSubTitle() {
        return $this->featuredAd->subTitle;
    }

    protected function setImage() {
        return $this->featuredAd->image;
    }

    public function setActions() {
        return [
            FeaturedAdItem::ACTION_ATTENDANCE_CONFIRM,
            FeaturedAdItem::ACTION_LOCATION_FOLLOW,
        ];
    }

    protected function setClickAction() {
        return [FeaturedAdItem::ACTION_GOTO_LOCATION_PROFILE];
    }

    public function setPayload() {
        return classFromArray(['locationId' => $this->featuredAd->locationId]);
    }


}