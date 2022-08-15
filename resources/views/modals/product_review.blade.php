<?php
$apiCore = new \App\Api\Core;

if (!isset($review)) {
    return '';
}

$phone = !empty($review->phone) ? substr_replace($review->phone, "****", -4) : '';
$email = !empty($review->email) ? substr_replace($review->email, "****", -4) : '';
?>

<div class="review_item" data-id="{{$review->id}}">
    <div class="row">
        <div class="col-md-8 mb__10 star_wrapper">
            <div class="rating-group mr__10">
                @for($i=1;$i<=5;$i++)
                    @if($i > $review->star)
                        <label class="rating__label"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                    @else
                        <label class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                    @endif
                @endfor
            </div>

            <div class="rating-group">
            @if ($review->getUser())
                <b class="text-capitalize">{{$review->getUser()->getTitle()}}</b>
                @if ($review->getUser()->chuyenGia())
                    <span class="alert alert-success ml__10 fs-9 text-uppercase text-bold">
                        <i class="fa fa-user mr__5"></i> chuyên gia
                    </span>
                @endif
            @else
                <b>{{$phone . ' - ' . $email}}</b>
            @endif
            </div>
        </div>
        <div class="col-md-4 mb__10 text-right">
            <span class="fs-11">{{$apiCore->timeToString($review->created_at)}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb__10">
            <?php echo nl2br($review->note);?>
        </div>
    </div>
</div>
